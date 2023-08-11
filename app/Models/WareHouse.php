<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WareHouse extends Model
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

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');    
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');  
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');  
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_warehouses', 'warehouse_id', 'product_id');    
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'warehouse_id');  
    }

    //incoming products i.e products recieved into this warehouse
    public function productQtyInWarehouse($product_id)
    {
       $product = ProductWarehouse::where(['warehouse_id'=>$this->id, 'product_id'=>$product_id])->first();
       return isset($product) ? $product->product_qty : 0;
    }

    public function productQtySoldInWarehouse($product_id) {
        //warehouse orders
        $orders = $this->orders()->where('status', 'delivered_and_remitted')->get();
        $sum_outgoingStocks = count($orders) > 0 ? OutgoingStock::whereIn('order_id', $orders->pluck('id'))->where('product_id', $product_id)
            ->where('customer_acceptance_status', 'accepted')->sum('quantity_removed') : 0;
        return $sum_outgoingStocks; 
    }

    public function stock_available_by_warehouse($product_id) {

        $stock_available = 0;
        $sum_incomingStocks = $this->productQtyInWarehouse($product_id);

        //outgoingstocks
        $delivered_order_ids = $this->orders()->where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->pluck('package_bundle'); //[[{}], [{}], [{}]]

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        // Now, use array_column to get the product_id values
        $productIds = array_column($flattenedArray, 'product_id'); //["1","2","3","4","5","6","7"]

        // Check if the $product_id is contained in the extracted array
        if (!in_array($product_id, $productIds)) {
            // Product with the given $product_id does not exist in the array
            $stock_available = $sum_incomingStocks;
        } else {

            $quantity_removed = 0;
            $quantity_returned = 0;
            if (count($accepted_outgoing_stock) > 0) {
                foreach ($flattenedArray as $key => $package) {
                    if ($package['customer_acceptance_status'] == 'accepted' && $package['product_id'] == $product_id ) {
                        $quantity_removed += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0; //sum
                        $quantity_returned += isset($package['quantity_returned']) ? (int) $package['quantity_returned'] : 0; //sum
                    }
                }
            }

            $sum_outgoingStocks = count($delivered_order_ids) > 0 ? $quantity_removed - $quantity_returned : 0;

            $stock_available = $sum_incomingStocks - $sum_outgoingStocks;
        }

        return $stock_available;
    }

}
