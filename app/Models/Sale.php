<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->unique_key = $model->createUniqueKey(Str::random(30));
    //     });
    // }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // $model->unique_key = $model->createUniqueKey(Str::random(30));
            // $model->url = 'order-form/'.$model->unique_key;
            // $model->save();

            $string = Str::random(30);
            $randomStrings = static::where('unique_key', 'like', $string.'%')->pluck('unique_key');

            $code = 'kp-' . date("his");
            $randomCodes = static::where('sale_code', 'like', $code.'%')->pluck('sale_code');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));

            do {
                $randomCode = $code.rand(100000, 999999);
            } while ($randomCodes->contains($randomCode));
    
            $model->unique_key = $randomString;
            $model->sale_code = $randomCode;

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

    public function saleDate(){
        $time = strtotime($this->purchase_date);
        $newformat = date('D, jS M Y',$time);
        return $newformat;
    }

    public function amountPaidAccrued($sale_code){
        //$amountPaid = $this->where('sale_code', $sale_code)->sum('amount_paid');
        $sale = $this->where('sale_code', $sale_code);
        $amountPaid = $sale->sum('amount_paid');

        if ($sale->first()->sales->count() > 0) {
            $amountPaid += $sale->first()->sales->sum('amount_paid');
        }
        return $amountPaid;
    }

    public function amountDueAccrued($sale_code){
        // $amountDue = $this->where('sale_code', $sale_code)->sum('amount_due');
        $sale = $this->where('sale_code', $sale_code);
        $amountDue = $sale->sum('amount_due');

        if ($sale->first()->sales->count() > 0) {
            $amountDue += $sale->first()->sales->sum('amount_due');
        }
        return $amountDue;
    }

    public function outgoingStock()
    {
        return $this->belongsTo(OutgoingStock::class, 'outgoing_stock_id');  
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');    
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');  
    }

    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id');  
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');  
    }

    //$cat->categories as subcat
    public function sales()
    {
        return $this->hasMany(Sale::class, 'parent_id', 'id'); //mapping categories to its 'parent_id'
    }
}
