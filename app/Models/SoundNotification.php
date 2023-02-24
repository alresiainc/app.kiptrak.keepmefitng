<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SoundNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            
            $string = Str::random(30);
            $randomStrings = static::where('unique_key', 'like', $string.'%')->pluck('unique_key');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));
    
            $model->unique_key = $randomString;

        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');  
    }

    public static function newOrders()
    {
        $newOrders = collect([])->all();
        $order_ids = static::where('id', '>=', 1)->pluck('order_id');
        if ($order_ids->count() > 0) {
            $newOrders = Order::whereIn('id', $order_ids)->where('status', 'new')->get();
            return $newOrders;
        }
        return $newOrders;
    }

    public static function pendingOrders()
    {
        $pendingOrders = collect([])->all();
        $order_ids = static::where('id', '>=', 1)->pluck('order_id');
        if ($order_ids->count() > 0) {
            $pendingOrders = Order::whereIn('id', $order_ids)->where('status', 'pending')->get();
            return $pendingOrders;
        }
        return $pendingOrders;
    }
}
