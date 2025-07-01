<?php

namespace App\Livewire\Pages\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\DeliveryLocations\DeliveryRegion;
use App\Models\DeliveryLocations\DeliveryArea;
use App\Models\ContactMessage;
use App\Enums\USER_ROLES;
use Carbon\Carbon;

class Admin extends Component
{
    public function render()
    {
        $count_super_admins = User::where('role', USER_ROLES::SUPER_ADMIN)->count();
        $count_admins = User::where('role', USER_ROLES::ADMIN)->count();
        $count_users = User::where('role', !USER_ROLES::SUPER_ADMIN)->count();

        $count_messages = ContactMessage::count();
        $count_unread_messages = ContactMessage::where('is_read', false)->count();

        $count_regions = DeliveryRegion::count();
        $count_areas = DeliveryArea::count();

        // $gross_sales = Sale::sum('total_amount');
        // $net_sales = Sale::sum('total_amount') - Sale::sum('discount');
        // $cost_of_sales = OrderItems::sum('buying_price');
        // $gross_profit = $net_sales - $cost_of_sales;

        $gross_sales = 1780980;
        $net_sales = 1580000;
        $cost_of_sales = 580000;
        $gross_profit = $net_sales - $cost_of_sales;

        // // Sales for each month of the current year
        // $monthly_sales = Sale::selectRaw("MONTH(created_at) as month, SUM(total_amount) as total_sales")
        //     // ->where('status', 'processed')
        //     // ->where('paid', true)
        //     ->whereYear('created_at', Carbon::now()->year)
        //     ->groupBy('month')
        //     ->orderBy('month')
        //     ->pluck('total_sales', 'month');

        // // Initialize an array to hold sales data for each month of the year
        // $sales_data = [];

        // // Loop through each month of the year and get sales data
        // for ($month = 1; $month <= 12; $month++) {
        //     if (isset($monthly_sales[$month])) {
        //         $sales_data[] = $monthly_sales[$month];
        //     } else {
        //         $sales_data[] = 0;
        //     }
        // }

        // $locations_data = OrderDelivery::select('location', \DB::raw('COUNT(*) as total_orders'))
        // ->groupBy('location')
        // ->orderBy('total_orders', 'desc')
        // ->get();

        // // Map the data for cities and orders
        // $locations_labels = $locations_data->pluck('location')->toArray();
        // $locations_orders = $locations_data->pluck('total_orders')->toArray();



        // Mocked static monthly sales data for testing (indexed by month: 1=Jan, 12=Dec)
        $monthly_sales = collect([
            1 => 1500.00,   // January
            2 => 2300.50,   // February
            3 => 1800.75,   // March
            4 => 2500.00,   // April
            5 => 3000.00,   // May
            6 => 2700.25,   // June
            7 => 0.00,      // July (no sales)
            8 => 3100.00,   // August
            9 => 2000.00,   // September
            10 => 3300.00,  // October
            11 => 30500.00,  // November
            12 => 150000.00,  // December
        ]);

        // Generate full array of sales data per month (0 if not in mocked data)
        $sales_data = [];

        for ($month = 1; $month <= 12; $month++) {
            $sales_data[] = $monthly_sales[$month] ?? 0;
        }

        // Static mock data representing total orders per location
        $locations_data = collect([
            ['location' => 'Nairobi', 'total_orders' => 125],
            ['location' => 'Mombasa', 'total_orders' => 95],
            ['location' => 'Kisumu', 'total_orders' => 80],
            ['location' => 'Eldoret', 'total_orders' => 60],
            ['location' => 'Thika', 'total_orders' => 45],
            ['location' => 'Nakuru', 'total_orders' => 30],
            ['location' => 'Kiambu', 'total_orders' => 150],
            ['location' => 'Turkana', 'total_orders' => 30],
        ]);

        // Map into chart-ready arrays
        $locations_labels = $locations_data->pluck('location')->toArray();
        $locations_orders = $locations_data->pluck('total_orders')->toArray();

        return view('livewire.pages.dashboard.admin', compact(
            'count_super_admins',
            'count_admins',
            'count_users',

            'count_messages',
            'count_unread_messages',

            'count_regions',
            'count_areas',

            'gross_sales',
            'net_sales',
            'cost_of_sales',
            'gross_profit',

            'sales_data',
            'locations_labels',
            'locations_orders',
        ));
    }
}
