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

    public function purchaseDate(){
        $time = strtotime($this->purchase_date);
        $newformat = date('D, jS M Y',$time);
        return $newformat;
    }

    public function amountPaidAccrued($purchase_code){
        $amountPaid = $this->where('purchase_code', $purchase_code)->sum('amount_paid');
        return $amountPaid;
    }

    public function amountDueAccrued($purchase_code){
        $amountDue = $this->where('purchase_code', $purchase_code)->sum('amount_due');
        return $amountDue;
    }

    //ORM
    //$cat->categories as subcat
    // public function purchases()
    // {
    //     return $this->hasMany(Purchase::class, 'parent_id', 'id'); //mapping categories to its 'parent_id'
    // }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');  
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');  
    }
}
