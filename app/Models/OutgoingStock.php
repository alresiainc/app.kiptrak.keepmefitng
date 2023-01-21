<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class OutgoingStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unique_key = $model->createUniqueKey(Str::random(30));
        });
    }

    //check if unique_key exists
    private function createUniqueKey($string){
        if (static::whereUniqueKey($unique_key = $string)->exists()) {
            $random = rand(1000, 9000);
            $unique_key = $string.''.$random;
            return $unique_key;
        }
        return $string;
    }

    public function outgoingStockTotal()
    {
        return $this->quantity_removed - $this->quantity_returned;
    }

    //product
    public function outgoingStockRevenue()
    {
        return ($this->quantity_removed - $this->quantity_returned) * $this->product->price;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');  
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');  
    }

    public function orderLabel()
    {
        return $this->belongsTo(OrderLabel::class, 'order_label_id');  
    }
}
