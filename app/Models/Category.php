<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

class Category extends Model
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

    public function sales($unique_key) {
        $category = Category::where('unique_key', $unique_key)->first();
        $products = $category->products->pluck('id'); //[1,3,4]
        $sales = Sale::whereIn('sales.product_id', $products)->get();
        return $sales->count();
    }

    public function purchases($unique_key) {
        $category = Category::where('unique_key', $unique_key)->first();
        $products = $category->products->pluck('id'); //[1,3,4]
        $purchases = Purchase::whereIn('purchases.product_id', $products)->get();
        return $purchases->count();
    }

    //customers by category of products bought
    public function customers($unique_key) {
        $category = Category::where('unique_key', $unique_key)->first();
        $products = $category->products->pluck('id'); //[1,3,4]
        $customers = Sale::whereIn('sales.product_id', $products)->select('customer_id')->groupBy('customer_id')->get();
        //dd($customers);
        return $customers->count();
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');    
    }

    public function products() {
        return $this->hasMany(Product::class, 'category_id');    
    }

    public function revenue($start_date="", $end_date="", $warehouse_id="") {
        
        $revenue = 0;
    
        //date only
        if ($start_date == "" && $end_date == "" && $warehouse_id == "") {
            $products = $this->products->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('product_id', $products)->where('customer_acceptance_status', 'accepted');
            $revenue += $accepted_outgoing_stock->sum('amount_accrued');
        }

        //date only
        if ($start_date != "" && $end_date != "" && $warehouse_id == "") {
            $products = $this->products()->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->pluck('id');
            $accepted_outgoing_stock = OutgoingStock::whereIn('product_id', $products)->where('customer_acceptance_status', 'accepted');
            $revenue += $accepted_outgoing_stock->sum('amount_accrued');
        }
        return $revenue;
    }
}
