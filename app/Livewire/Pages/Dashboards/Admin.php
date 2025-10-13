<?php

namespace App\Livewire\Pages\Dashboards;

use Livewire\Component;
use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Sales\Order;
use App\Models\Sales\OrderDelivery;
use App\Models\Sales\OrderItem;
use App\Models\Payments\Payment;
use App\Models\Products\Product;
use App\Models\Products\ProductCategory;
use App\Models\DeliveryLocations\DeliveryRegion;
use App\Models\DeliveryLocations\DeliveryArea;
use App\Models\Blogs\Blog;
use App\Models\Blogs\BlogCategory;
use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Admin extends Component
{
    protected $listeners = ['refreshCharts' => '$refresh'];

    public function hydrate()
    {
        $this->dispatch('charts-updated');
    }

    public function getOrderTrend()
    {
        $now = Carbon::now();

        $current_period_start = Carbon::create($now->year, 1, 1);
        $current_period_end = $now;

        $previous_period_start = $current_period_start->copy()->subYear();
        $previous_period_end = $current_period_end->copy()->subYear();

        $current_period_orders = Order::whereBetween('created_at', [$current_period_start, $current_period_end])->count();
        $previous_period_orders = Order::whereBetween('created_at', [$previous_period_start, $previous_period_end])->count();
        
        if ($previous_period_orders == 0) return 0;

        return (($current_period_orders - $previous_period_orders) / $previous_period_orders) * 100;
    }

    // Gross Sales Trend (all sales, including unpaid)
    public function getGrossSalesTrend()
    {
        $now = Carbon::now();

        $current_period_start = Carbon::create($now->year, 1, 1);
        $current_period_end = $now;

        $previous_period_start = $current_period_start->copy()->subYear();
        $previous_period_end = $current_period_end->copy()->subYear();

        $current_period_gross = Order::whereBetween('created_at', [$current_period_start, $current_period_end])
            ->sum('total_amount');
        $previous_period_gross = Order::whereBetween('created_at', [$previous_period_start, $previous_period_end])
            ->sum('total_amount');
        
        if ($previous_period_gross == 0) return 0;

        return (($current_period_gross - $previous_period_gross) / $previous_period_gross) * 100;
    }

    // Net Sales Trend (Gross Sales minus discounts)
    public function getNetSalesTrend()
    {
        $now = Carbon::now();

        $current_period_start = Carbon::create($now->year, 1, 1);
        $current_period_end = $now;

        $previous_period_start = $current_period_start->copy()->subYear();
        $previous_period_end = $current_period_end->copy()->subYear();

        // Current period
        $current_period_data = Order::whereBetween('orders.created_at', [$current_period_start, $current_period_end])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('SUM(total_amount) as gross_sales, SUM(order_items.discount) as total_discount')
            ->first();
        
        $current_net_sales = ($current_period_data->gross_sales ?? 0) - ($current_period_data->total_discount ?? 0);

        // Previous period
        $previous_period_data = Order::whereBetween('orders.created_at', [$previous_period_start, $previous_period_end])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('SUM(total_amount) as gross_sales, SUM(order_items.discount) as total_discount')
            ->first();
        
        $previous_net_sales = ($previous_period_data->gross_sales ?? 0) - ($previous_period_data->total_discount ?? 0);
        
        if ($previous_net_sales == 0) return 0;

        return (($current_net_sales - $previous_net_sales) / $previous_net_sales) * 100;
    }

    // Gross Profit Trend (Net Sales minus Cost of Sales)
    public function getGrossProfitTrend()
    {
        $now = Carbon::now();

        $current_period_start = Carbon::create($now->year, 1, 1);
        $current_period_end = $now;

        $previous_period_start = $current_period_start->copy()->subYear();
        $previous_period_end = $current_period_end->copy()->subYear();

        // Current period
        $current_period_data = Order::whereBetween('orders.created_at', [$current_period_start, $current_period_end])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('SUM(orders.total_amount) - SUM(order_items.discount) as net_sales, SUM(order_items.production_cost) as cost_of_sales')
            ->first();
        
        $current_gross_profit = ($current_period_data->net_sales ?? 0) - ($current_period_data->cost_of_sales ?? 0);

        // Previous period
        $previous_period_data = Order::whereBetween('orders.created_at', [$previous_period_start, $previous_period_end])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('SUM(orders.total_amount) - SUM(order_items.discount) as net_sales, SUM(order_items.production_cost) as cost_of_sales')
            ->first();
        
        $previous_gross_profit = ($previous_period_data->net_sales ?? 0) - ($previous_period_data->cost_of_sales ?? 0);

        if ($previous_gross_profit == 0) return 0;

        return (($current_gross_profit - $previous_gross_profit) / $previous_gross_profit) * 100;
    }

    public function getTopProducts($limit = 5)
    {
        return OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->product->title ?? 'Unknown Product',
                    'sold' => $item->total_sold,
                    'revenue' => $item->total_sold * ($item->product->selling_price ?? 0)
                ];
            });
    }

    public function getRecentOrders($limit = 5)
    {
        return Order::with(['user', 'orderDelivery'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user->full_name ?? 'Guest',
                    'amount' => $order->total_amount,
                    'status' => $order->status,
                    'order_number' => $order->order_number ?? 'Unknown Order No.',
                    'date' => $order->created_at->format('M j, Y')
                ];
            });
    }

    public function render()
    {
        $user_counts = User::selectRaw("
            COUNT(CASE WHEN role = ? THEN 1 END) as super_admins,
            COUNT(CASE WHEN role = ? THEN 1 END) as admins,
            COUNT(CASE WHEN role NOT IN (?, ?) THEN 1 END) as users
        ", [
            UserRoles::SUPER_ADMIN->value,
            UserRoles::ADMIN->value,
            UserRoles::SUPER_ADMIN->value,
            UserRoles::ADMIN->value
        ])->first();

        $count_orders = Order::whereHas('orderDelivery')->count();
        $count_products = Product::count();
        $count_product_categories = ProductCategory::count();
        $count_delivery_locations = DeliveryRegion::count();
        $count_delivery_areas = DeliveryArea::count();
        $count_blogs = Blog::count();
        $count_blog_categories = BlogCategory::count();
        $count_messages = ContactMessage::count();

        // ACCURATE ACCOUNTING-BASED FINANCIAL METRICS
        $gross_sales = Order::sum('total_amount');
        $total_discounts = OrderItem::sum('discount');
        $net_sales = $gross_sales - $total_discounts;
        $cost_of_sales = OrderItem::sum('buying_price');
        $gross_profit = $net_sales - $cost_of_sales;

        // Charts data
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $monthly_sales = Order::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, SUM(total_amount) as total_sales")
                ->whereRaw("strftime('%Y', created_at) = ?", [Carbon::now()->year])
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_sales', 'month');
        } else {
            $monthly_sales = Order::selectRaw("MONTH(created_at) as month, SUM(total_amount) as total_sales")
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_sales', 'month');
        }

        $sales_data = [];
        for ($month = 1; $month <= 12; $month++) {
            $sales_data[] = $monthly_sales[$month] ?? 0;
        }

        $locations_data = OrderDelivery::select('location', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('location')
            ->orderBy('total_orders', 'desc')
            ->get();

        $locations_labels = $locations_data->pluck('location')->toArray();
        $locations_orders = $locations_data->pluck('total_orders')->toArray();

        return view('livewire.pages.dashboards.admin', [
            'count_super_admins' => $user_counts->super_admins,
            'count_admins' => $user_counts->admins,
            'count_users' => $user_counts->users,
            'count_orders' => $count_orders,
            'count_products' => $count_products,
            'count_product_categories' => $count_product_categories,
            'count_delivery_locations' => $count_delivery_locations,
            'count_delivery_areas' => $count_delivery_areas,
            'count_blogs' => $count_blogs,
            'count_blog_categories' => $count_blog_categories,
            'count_messages' => $count_messages,

            // Accounting Financial Metrics
            'gross_sales' => $gross_sales,
            'net_sales' => $net_sales,
            'cost_of_sales' => $cost_of_sales,
            'gross_profit' => $gross_profit,

            // Chart data
            'sales_data' => $sales_data,
            'locations_labels' => $locations_labels,
            'locations_orders' => $locations_orders,

            // Trend metrics
            'order_trend' => $this->getOrderTrend(),
            'gross_sales_trend' => $this->getGrossSalesTrend(),
            'net_sales_trend' => $this->getNetSalesTrend(),
            'gross_profit_trend' => $this->getGrossProfitTrend(),

            'top_products' => $this->getTopProducts(),
            'recent_orders' => $this->getRecentOrders(),
        ]);
    }
}
