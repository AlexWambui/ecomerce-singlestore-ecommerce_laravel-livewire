<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payments\Payment;
use App\Models\User;
use Carbon\Carbon;

class Order extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($order) {
            $order->orderItems()->delete();

            $order->orderDelivery()->delete();

            $order->payment()->delete();
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderDelivery()
    {
        return $this->hasOne(OrderDelivery::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday($query) {
        return $query->whereDate('orders.created_at', Carbon::today());
    }

    public function scopeYesterday($query) {
        return $query->whereDate('orders.created_at', Carbon::yesterday());
    }

    public function scopeThisWeek($query) {
        return $query->whereBetween('orders.created_at', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek(),
        ]);
    }

    public function scopeThisMonth($query) {
        return $query->whereMonth('orders.created_at', Carbon::now()->month)
            ->whereYear('orders.created_at', Carbon::now()->year);
    }
}
