<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $randomStrings = static::where('unique_key', 'like', $string . '%')->pluck('unique_key');

            $code = 'kp-' . date("his");
            $randomCodes = static::where('code', 'like', $code . '%')->pluck('code');

            do {
                $randomString = $string . rand(100000, 999999);
            } while ($randomStrings->contains($randomString));

            do {
                $randomCode = $code . rand(100000, 999999);
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
        $product = $this->where('id', $id)->first();
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

        //outgoingstocks
        $delivered_order_ids = Order::where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->pluck('package_bundle'); //[[{}], [{}], [{}]]

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        // Now, use array_column to get the product_id values
        $productIds = array_column($flattenedArray, 'product_id'); //["1","2","3","4","5","6","7"]

        // Product with the given $product_id exists in the array
        //$sum_outgoingStocks = $this->outgoingStocks->sum->outgoingStockTotal();

        // return count($accepted_outgoing_stock);
        $quantity_removed = 0;
        $quantity_returned = 0;
        $sum_outgoingStocks = 0;
        $comboOutgoingStocksArray = [];
        $comboOutgoingStocks = '';
        if (count($accepted_outgoing_stock) > 0) {

            foreach ($flattenedArray as $key => $package) {
                if ((!isset($package['isCombo'])) && ($package['isCombo'] !== 'true')) {

                    if (($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $this->id)) {
                        $quantity_removed += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0; //sum
                        $quantity_returned += isset($package['quantity_returned']) ? (int) $package['quantity_returned'] : 0; //sum
                    }
                }
                if ((isset($package['isCombo'])) && ($package['isCombo'] == 'true')) {
                    $comboOutgoingStocksArray[] = $package;
                }
                // if ( ($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $this->id) && (isset($package['isCombo'])) && ($package['isCombo'] == 'true') ) {
                //     $product = Product::where('id', $this->id)->first();
                //     $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $this->id)) > 0 ? 
                //     collect($product->comboProducts())->where('id', $this->id)->first()->quantity_combined : 0;
                //     return 'dwn';
                // }
            }
            $comboOutgoingStocks = count($comboOutgoingStocksArray) > 0 ? array_merge(...$comboOutgoingStocksArray) : '';
        }
        //return count($accepted_outgoing_stock);

        // $sum_outgoingStocks = count($delivered_order_ids) > 0 ?
        // $this->outgoingStocks()->whereIn('order_id', $delivered_order_ids)->sum(DB::raw('quantity_removed - quantity_returned')) : 0;

        $sum_outgoingStocks = count($delivered_order_ids) > 0 ? $quantity_removed - $quantity_returned : 0;
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        //incase of combo
        // $comboOutgoingStocks = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('isCombo','true')->get();

        ///previous code
        // if (count($comboOutgoingStocks) > 0) {
        //     $comboOutgoingStocksProducts = $comboOutgoingStocks->pluck('product_id'); //['4'] combo product id. we need to get out the pro
        //     $comboProducts = Product::whereIn('id', $comboOutgoingStocksProducts)->get(); //combo products

        //     foreach ($comboProducts as $key => $product) {
        //         $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $this->id)) > 0 ? 
        //         collect($product->comboProducts())->where('id', $this->id)->first()->quantity_combined : 0;
        //     } 
        // }

        if ($comboOutgoingStocks !== '') {
            //$comboOutgoingStocksProducts = $comboOutgoingStocks->pluck('product_id'); //['4'] combo product id. we need to get out the pro
            $comboOutgoingStocksProducts = array_column($comboOutgoingStocksArray, 'product_id');
            $comboProducts = Product::whereIn('id', $comboOutgoingStocksProducts)->get(); //combo products

            foreach ($comboProducts as $key => $product) {

                $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $this->id)) > 0 ?
                    collect($product->comboProducts())->where('id', $this->id)->first()->quantity_combined : 0;
            }
        }

        $stock_available = $sum_incomingStocks - $sum_outgoingStocks;


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

    public function warehouses()
    {
        return $this->belongsToMany(WareHouse::class, 'product_warehouses', 'product_id', 'warehouse_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id');
    }

    //product revenue, sold_amt, gross_profit
    public function revenue($staff_assigned_id = "", $start_date = "", $end_date = "")
    {
        //
        $revenue = 0;
        //all empty
        if ($staff_assigned_id == "" && $start_date == "" && $end_date == "") {
            $delivered_order_ids = Order::where('status', 'delivered_and_remitted')->pluck('id');

            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('product_id', $this->id)->where('customer_acceptance_status', 'accepted');
            $revenue += $accepted_outgoing_stock->sum('amount_accrued');
        }
        //staff only
        if ($staff_assigned_id != "" && $start_date == "" && $end_date == "") {
            $delivered_order_ids = Order::where('staff_assigned_id', $staff_assigned_id)->where('status', 'delivered_and_remitted')->pluck('id');

            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('product_id', $this->id)->where('customer_acceptance_status', 'accepted');
            $revenue += $accepted_outgoing_stock->sum('amount_accrued');
        }
        //date only
        if ($staff_assigned_id == "" && $start_date != "" && $end_date != "") {
            $delivered_order_ids = Order::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('status', 'delivered_and_remitted')->pluck('id');

            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('product_id', $this->id)->where('customer_acceptance_status', 'accepted');
            $revenue += $accepted_outgoing_stock->sum('amount_accrued');
        }
        //all inclusive
        if ($staff_assigned_id != "" && $start_date != "" && $end_date != "") {
            $delivered_order_ids = Order::whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->where('staff_assigned_id', $staff_assigned_id)->where('status', 'delivered_and_remitted')->pluck('id');

            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('product_id', $this->id)->where('customer_acceptance_status', 'accepted');
            $revenue += $accepted_outgoing_stock->sum('amount_accrued');
        }


        return $revenue;
    }

    //sold_qty
    public function soldQty($staff_assigned_id = "")
    {
        //
        $soldQty = 0;
        if ($staff_assigned_id == "") {
            $delivered_order_ids = Order::where('status', 'delivered_and_remitted')->pluck('id');

            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('product_id', $this->id)->where('customer_acceptance_status', 'accepted');
            $soldQty += $accepted_outgoing_stock->sum('quantity_removed');
        } else {
            $delivered_order_ids = Order::where('staff_assigned_id', $staff_assigned_id)->where('status', 'delivered_and_remitted')->pluck('id');

            $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->where('product_id', $this->id)->where('customer_acceptance_status', 'accepted');
            $soldQty += $accepted_outgoing_stock->sum('quantity_removed');
        }

        return $soldQty;
    }

    public function comboOriginalSalePrice()
    {
        //
        $originalSalePrice = 0;

        $salePrice_after_discount = $this->sale_price;
        $discount_type = $this->discount_type;
        $discount = $this->discount;
        if ($discount_type == 'fixed') {
            $originalSalePrice = $salePrice_after_discount + $discount;
        } else {
            $discount_percent = $discount / 100;
            $divided_by = 1 - $discount_percent;
            $originalSalePrice = $salePrice_after_discount / $divided_by;
        }

        return $originalSalePrice;
    }

    public function comboProducts()
    {
        $products = [];
        $product_idQtys = unserialize($this->combo_product_ids); //['4-2', '1-2']
        foreach ($product_idQtys as $key => $idQty) {
            $explode = explode('-', $idQty); //
            $id = $explode[0];
            $qty = $explode[1];
            $product = Product::find($id);
            $products[] = $product;
            $products[$key]->quantity_combined = $qty; //append key
        }
        return $products;
    }
    public function getProductsAttributes()
    {
        $products = [];
        $product_idQtys = unserialize($this->combo_product_ids); // e.g., ['4-2', '1-2']
        $product_idQtys = is_array($product_idQtys) ? $product_idQtys : [];

        foreach ($product_idQtys as $idQty) {
            $explode = explode('-', $idQty);

            if (count($explode) === 2) { // Check if we have exactly 2 elements (ID and quantity)
                $id = $explode[0];
                $qty = $explode[1];

                $product = Product::find($id);
                if ($product) {
                    $product->quantity_combined = $qty; // Append the quantity attribute
                    $products[] = $product; // Add the product to the array
                }
            }
        }

        return $products;
    }


    //used in orderbump modal


}
