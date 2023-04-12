<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Purchase extends Model
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
            $randomCodes = static::where('purchase_code', 'like', $code.'%')->pluck('purchase_code');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));

            do {
                $randomCode = $code.rand(100000, 999999);
            } while ($randomCodes->contains($randomCode));
    
            $model->unique_key = $randomString;
            $model->purchase_code = $randomCode;

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

    public function purchaseDate(){
        $time = strtotime($this->purchase_date);
        $newformat = date('D, jS M Y',$time);
        return $newformat;
    }

    public function amountPaidAccrued($purchase_code){
        // $amountPaid = $this->where('purchase_code', $purchase_code)->sum('amount_paid');
        $purchase = $this->where('purchase_code', $purchase_code);
        $amountPaid = $purchase->sum('amount_paid');

        if ($purchase->first()->purchases->count() > 0) {
            $amountPaid += $purchase->first()->purchases->sum('amount_paid');
        }
        return $amountPaid;
    }

    public function amountDueAccrued($purchase_code){
        //$amountDue = $this->where('purchase_code', $purchase_code)->sum('amount_due');
        $purchase = $this->where('purchase_code', $purchase_code);
        $amountDue = $purchase->sum('amount_due');

        if ($purchase->first()->purchases->count() > 0) {
            $amountDue += $purchase->first()->purchases->sum('amount_due');
        }
        return $amountDue;
    }

    //ORM
    //$cat->categories as subcat
    // public function purchases()
    // {
    //     return $this->hasMany(Purchase::class, 'parent_id', 'id'); //mapping categories to its 'parent_id'
    // }
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');    
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');  
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');  
    }

    //$cat->categories as subcat
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'parent_id', 'id'); //mapping categories to its 'parent_id'
    }

    public function incomingStock()
    {
        return $this->belongsTo(IncomingStock::class, 'incoming_stock_id');  
    }

    public function next(){
        // get next user
        return Purchase::where('id', '>', $this->id)->orderBy('id','asc')->first();
    
    }
    public  function previous(){
        // get previous  user
        return Purchase::where('id', '<', $this->id)->orderBy('id','desc')->first();
    
    }
}
