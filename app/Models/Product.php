<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     $model->unique_key = $model->createUniqueKey(Str::random(30));
        // });

        static::creating(function ($model) {
            // $model->unique_key = $model->createUniqueKey(Str::random(30));
            // $model->url = 'order-form/'.$model->unique_key;
            // $model->save();

            $string = Str::random(30);
            $randomStrings = static::where('unique_key', 'like', $string.'%')->pluck('unique_key');

            $code = 'kp-' . date("his");
            $randomCodes = static::where('code', 'like', $code.'%')->pluck('code');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));

            do {
                $randomCode = $code.rand(100000, 999999);
            } while ($randomCodes->contains($randomCode));
    
            $model->unique_key = $randomString;
            $model->code = $randomCode;
            // $model->url = 'order-form/'.$model->unique_key;

        });
    }

    //check if unique_key exists
    // private function createUniqueKey($string){
    //     if (static::whereUniqueKey($unique_key = $string)->exists()) {
    //         $random = rand(1000, 9000);
    //         $unique_key = $string.''.$random;
    //         return $unique_key;
    //     }

    //     return $string;
    // }

    public function currencySymbol($currency)
    {
        $currency_symbol = substr($currency, strrpos($currency, '-') + 1); //after the last "-"
        return $currency_symbol;
    }

    public function productById($id)
    {
        $product = $this->where('id',$id)->first();
        return $product;
    }

    public function incomingStocks()
    {
        return $this->hasMany(IncomingStock::class, 'product_id');  
    }

    public function outgoingStocks()
    {
        return $this->hasMany(OutgoingStock::class, 'product_id');  
    }

    public function stock_available()
    {
        //product stock available
        $stock_available = 0;
        $sum_incomingStocks = $this->incomingStocks->sum('quantity_added');
        if (count($this->outgoingStocks) == 0) {
            $stock_available = $sum_incomingStocks;
        } else {
            $sum_outgoingStocks = $this->outgoingStocks->sum->outgoingStockTotal();
            $stock_available = $sum_incomingStocks - $sum_outgoingStocks;
        }
        return $stock_available;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');  
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');  
    }

    public function warehouses() {
        return $this->belongsToMany(WareHouse::class, 'product_warehouses', 'product_id', 'warehouse_id');    
    }

}
