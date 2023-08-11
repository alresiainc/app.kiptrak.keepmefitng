<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\OutgoingStock;
use App\Models\Product;

class Helper
{
    public function orderSalesRevenue($delivered_and_remitted_orders) 
    {
        $sales_paid = 0;
        
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

         // Flatten the multidimensional array into a single array
         $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

         if (count($accepted_outgoing_stock) > 0) {
             // foreach ($accepted_outgoing_stock as $packages) {
                 foreach ($flattenedArray as $package) {
                     if ($package['customer_acceptance_status'] == 'accepted') {
                         $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                     }
                 }
             // }
         }
 
         return $sales_paid;
    }

    public function totalSalesRevenue()
    {
        $sales_paid = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
                foreach ($flattenedArray as $package) {
                    if ($package['customer_acceptance_status'] == 'accepted') {
                        $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                    }
                }
            // }
        }

        return $sales_paid;
    }

    public function totalSalesCount()
    {
        $sales_count = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->pluck('id');
        // $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->where('customer_acceptance_status', 'accepted');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        // return count($accepted_outgoing_stock);
        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
                foreach ($flattenedArray as $package) {
                    if ($package['customer_acceptance_status'] == 'accepted') {
                        $sales_count += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0;
                    }
                }
            // }
        }
        return $sales_count;
        
    }

    //warehouse revenue
    public function totalSalesRevenueByWarehouse($warehouse_id)
    {
        $sales_paid = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->where('warehouse_id', $warehouse_id)->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
                foreach ($flattenedArray as $package) {
                    if ($package['customer_acceptance_status'] == 'accepted') {
                        $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                    }
                }
            // }
        }

        return $sales_paid;
    }

    //duration revenue
    public function totalSalesRevenueByDuration($duration_start = "", $duration_end = "")
    {
        $sales_paid = 0;

        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->whereBetween('created_at', [$duration_start, $duration_end])->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
                foreach ($flattenedArray as $package) {
                    if ($package['customer_acceptance_status'] == 'accepted') {
                        $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                    }
                }
            // }
        }

        return $sales_paid;
    }

    //duration-warehouse revenue
    public function totalSalesRevenueByDurationWarehouse($duration_start = "", $duration_end = "", $warehouse_id = "")
    {
        $sales_paid = 0;
        
        $delivered_and_remitted_orders = Order::where('status', 'delivered_and_remitted')->where('warehouse_id', $warehouse_id)
        ->whereBetween('created_at', [$duration_start, $duration_end])->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_and_remitted_orders)->pluck('package_bundle'); //[[{}], [{}], [{}]] multidimensional

        //return $productIds = array_column($accepted_outgoing_stock->toArray(), 'product_id');

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]

        if (count($accepted_outgoing_stock) > 0) {
            // foreach ($accepted_outgoing_stock as $packages) {
                foreach ($flattenedArray as $package) {
                    if ($package['customer_acceptance_status'] == 'accepted') {
                        $sales_paid += isset($package['amount_accrued']) ? (int) $package['amount_accrued'] : 0;
                    }
                }
            // }
        }

        return $sales_paid;
    }

    public function stock_available2($product_id)
    {
        //product stock available
        $stock_available = 0;
        $product = Product::where('id', $product_id)->first();
        $sum_incomingStocks = $product->incomingStocks->sum('quantity_added');

        //outgoingstocks
        $delivered_order_ids = Order::where('status', 'delivered_and_remitted')->pluck('id');
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
                    if ( (!isset($package['isCombo'])) && ($package['isCombo'] !== 'true') ) {
                        
                        if ( ($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $product_id) ) {
                            $quantity_removed += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0; //sum
                            $quantity_returned += isset($package['quantity_returned']) ? (int) $package['quantity_returned'] : 0; //sum
                        }
                    }
                    if ( (isset($package['isCombo'])) && ($package['isCombo'] == 'true') ) {
                        $comboOutgoingStocksArray[] = $package;
                    }
                    // if ( ($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $this->id) && (isset($package['isCombo'])) && ($package['isCombo'] == 'true') ) {
                    //     $product = Product::where('id', $this->id)->first();
                    //     $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $this->id)) > 0 ? 
                    //     collect($product->comboProducts())->where('id', $this->id)->first()->quantity_combined : 0;
                    //     return 'dwn';
                    // }
                }
                $comboOutgoingStocks = count($comboOutgoingStocksArray) > 0 ? array_merge(...$comboOutgoingStocksArray) : '' ;
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
                     
                   $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $product_id)) > 0 ? 
                    collect($product->comboProducts())->where('id', $product_id)->first()->quantity_combined : 0;
                    
                } 
            }
            
            //incase of combo
            
            
            $stock_available = $sum_incomingStocks - $sum_outgoingStocks;
        }
        return $sum_outgoingStocks;
        return $stock_available;
    }

    public function stock_available($product_id)
    {
        //product stock available
        $stock_available = 0;
        $product = Product::where('id', $product_id)->first();
        $sum_incomingStocks = $product->incomingStocks->sum('quantity_added');

        //outgoingstocks
        $delivered_order_ids = Order::where('status', 'delivered_and_remitted')->pluck('id');
        $accepted_outgoing_stock = OutgoingStock::whereIn('order_id', $delivered_order_ids)->pluck('package_bundle'); //[[{}], [{}], [{}]]

        // Flatten the multidimensional array into a single array
        $flattenedArray = array_merge(...$accepted_outgoing_stock); //[{}, {}]


        // Now, use array_column to get the product_id values
        $productIds = array_column($flattenedArray, 'product_id'); //["1","2","3","4","5","6","7"]
        
        // Product with the given $product_id exists in the array
        $quantity_removed = 0;
        $quantity_returned = 0;
        $sum_outgoingStocks = 0;
        $comboOutgoingStocksArray = [];
        $comboOutgoingStocks = '';
        if (count($accepted_outgoing_stock) > 0) {
            
            foreach ($flattenedArray as $key => $package) {
                if ( (!isset($package['isCombo'])) && ($package['isCombo'] !== 'true') ) {
                    
                    if ( ($package['customer_acceptance_status'] == 'accepted') && ($package['product_id'] == $product_id) ) {
                        $quantity_removed += isset($package['quantity_removed']) ? (int) $package['quantity_removed'] : 0; //sum
                        $quantity_returned += isset($package['quantity_returned']) ? (int) $package['quantity_returned'] : 0; //sum
                    }
                }
                if ( (isset($package['isCombo'])) && ($package['isCombo'] == 'true') ) {
                    if ( $package['customer_acceptance_status'] == 'accepted' ) {
                        $comboOutgoingStocksArray[] = $package;
                    }
                    
                }
                
            }
            $comboOutgoingStocks = count($comboOutgoingStocksArray) > 0 ? array_merge(...$comboOutgoingStocksArray) : '' ;
        }
        
        $sum_outgoingStocks = count($delivered_order_ids) > 0 ? $quantity_removed - $quantity_returned : 0;
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        //incase of combo
        if ($comboOutgoingStocks !== '') {
            $comboOutgoingStocksProducts = array_column($comboOutgoingStocksArray, 'product_id');
            $comboProducts = Product::whereIn('id', $comboOutgoingStocksProducts)->get(); //combo products
        
            foreach ($comboProducts as $key => $product) {
                    
                $sum_outgoingStocks += count(collect($product->comboProducts())->where('id', $product_id)) > 0 ? 
                collect($product->comboProducts())->where('id', $product_id)->first()->quantity_combined : 0;
                
            } 
        }
        
        $stock_available = $sum_incomingStocks - $sum_outgoingStocks;
    
        return $stock_available;
    }
}