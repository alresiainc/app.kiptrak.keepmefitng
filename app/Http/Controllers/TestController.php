<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

use App\Models\Permission;

class TestController extends Controller
{
    
    //after clicking first main btn, ajax
    public function saveNewFormFromCustomer2(Request $request)
    {
        // $authUser = auth()->user();
        // $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        //delete cartabandoned

        $cartAbandon = CartAbandon::where('id', $data['cartAbandoned_id']);
        if($cartAbandon->exists()) {
            $cartAbandon->delete();
        }
        
        $formHolder = FormHolder::where('unique_key', $data['unique_key'])->first();
        $order = $formHolder->order;

        //cos order was created initially @ newFormBuilderPost, incase the form originted from edited or duplicate form
        if (isset($order->customer_id)) {
    
            //save Order
            $newOrder = new Order();
            $newOrder->form_holder_id = $formHolder->id;
            $newOrder->source_type = 'form_holder_module';
            $newOrder->status = 'new';
            $newOrder->save();

            //making a copy from the former outgoingStocks, in the case of dealing with an edited or duplicated form
            // $outgoingStocks = $order->outgoingStocks;
            
            // foreach($outgoingStocks as $i => $outgoingStock)
            // {
            //     //make copy of rows, and create new records
            //     if(isset($outgoingStock->product)) {
            //         $outgoingStocks[$i]->order_id = $newOrder->id;
            //         $outgoingStocks[$i]->quantity_returned = 0;
            //         $outgoingStocks[$i]->quantity_removed = 1;
            //         $outgoingStocks[$i]->amount_accrued = $outgoingStock->product->sale_price;
            //         $outgoingStocks[$i]->isCombo = isset($outgoingStock->product->combo_product_ids) ? 'true' : null;
    
            //         $x[$i] = (new OutgoingStock())->create($outgoingStock->only(['product_id', 'order_id', 'quantity_removed', 'amount_accrued',
            //         'reason_removed', 'quantity_returned', 'created_by', 'status']));
            //     }
            // }
            // return $x;

            //making a copy from the former outgoingStocks, in the case of dealing with an edited or duplicated form

            //update $outgoingStockPackageBundle array
            $outgoingStock = $order->outgoingStock;
            // Check if $outgoingStock exists and is not null
            if ($outgoingStock) {
                // Convert the 'package_bundle' JSON into an associative array
                $packageBundle = $outgoingStock->package_bundle;

                // Loop through each package in the 'package_bundle' array
                foreach ($packageBundle as &$item) {
                    // Extract the 'product_id' from the package data
                    $productId = $item['product_id'];

                    // Find the related Product using 'Product::find($productId)'
                    $product = Product::find($productId);

                    // Check if a valid Product is found
                    if ($product) {
                        // Update the packageBundle data with additional information
                        $item['quantity_returned'] = 0;
                        $item['quantity_removed'] = 1;
                        $item['amount_accrued'] = $product->sale_price;
                        $item['isCombo'] = isset($product->combo_product_ids) ? 'true' : null;
                    }
                }

                // Convert the updated $packageBundle back to a JSON string
                // and update the 'package_bundle' property of $outgoingStock
                $outgoingStock->package_bundle = $packageBundle;

                // Create a new OutgoingStock record using the updated package data
                $newOutgoingStock = OutgoingStock::create([
                    'order_id' => $newOrder->id,
                    'created_by' => $outgoingStock->created_by,
                    'status' => $outgoingStock->status,
                    'package_bundle' => $outgoingStock->package_bundle,
                ]);

                // Return the new OutgoingStock object that we just created
                //return $newOutgoingStock;
            }

            // If $outgoingStock is not set or doesn't exist, we return null
            //return null;

            //////////////////////////////////////////////////////////
            //making a copy from the former outgoingStocks, in the case of dealing with an edited or duplicated form

            // $orderOutgoingStock = $order->outgoingStock;

            //update $outgoingStockPackageBundle array
            // $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
            // $package_bundle_1 = [];
            // //loop to get $package_bundle_1 array, if customer is null
            // foreach ($outgoingStockPackageBundle as &$main_outgoingStock) {
            //     if ( $main_outgoingStock['customer_acceptance_status'] == null ) {

            //         $package_bundles = [
            //             'quantity_returned'=>0,
            //             'quantity_removed'=>1,
            //         ];
            //         $package_bundle_1[] = $package_bundles;
            //     }
            // }

            // //loop to get new copy of $outgoingStockPackageBundle array
            // foreach ($outgoingStockPackageBundle as $key => &$value) {
            //     // Update values with similar keys
            //     if (isset($package_bundle_1[$key])) {
            //         $value = array_merge($value, $package_bundle_1[$key]);
            //     }
            // }
            ///////////////////////////////////////////////////////////

            // $newOutgoingStock = new OutgoingStock();
            // $newOutgoingStock->order_id = $newOrder->id;
            // $newOutgoingStock->package_bundle = $outgoingStockPackageBundle;
            // $newOutgoingStock->created_by = $order->outgoingStock->created_by;
            // $newOutgoingStock->status = $order->outgoingStock->status;
            // $newOutgoingStock->save();
        
            ///////////////////////////////////////////////////////////////////////////

            $mainProduct_revenue = 0;  //price * qty
            $qty_main_product = 0;
            // $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
            // 'customer_acceptance_status'=>'accepted'])->get();

            $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
            foreach ($outgoingStockPackageBundle as &$main_outgoingStock) {
                if ( ($main_outgoingStock['reason_removed'] == 'as_order_firstphase') && ($main_outgoingStock['customer_acceptance_status'] == 'accepted') ) {
                    $product = Product::where('id', $main_outgoingStock['product_id'])->first();
                    if (isset($product)) {
                        //array_push($mainProducts_outgoingStocks, array('product' => $product)); 
                        $main_outgoingStock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                        $mainProduct_revenue = $mainProduct_revenue + ($product->sale_price * $main_outgoingStock['quantity_removed']);
                        $qty_main_product += $main_outgoingStock['quantity_removed'];
                    }
                }
            }
    
            //convert to array to array-of-object
            $mainProducts_outgoingStocks = $mainProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : collect([]);
            ///////////////////////////////////////////////////////////////

            // $order = $order;
            $package_bundle_1 = [];
            //updated package in outgoingstock, created above
            foreach ($data['product_packages'] as $key => $product_id) {
                $data['product_id'] = $product_id;
                if (!empty($product_id)) {

                    $idPriceQty = explode('-', $product_id);
                    $productId = $idPriceQty[0];
                    $saleUnitPrice = $idPriceQty[1];
                    $qtyRemoved = $idPriceQty[2];

                    //accepted updated
                    $amount_accrued = $qtyRemoved * $saleUnitPrice;
                    // OutgoingStock::where(['product_id'=>$productId, 'order_id'=>$newOrder->id, 'reason_removed'=>'as_order_firstphase'])
                    // ->update(['quantity_removed'=>$qtyRemoved, 'amount_accrued'=>$amount_accrued, 'customer_acceptance_status'=>'accepted']);

                    // Create a new package array for each product ID
                    $package_bundles = [
                        'product_id'=>$productId,
                        'quantity_removed'=>$qtyRemoved,
                        'amount_accrued'=>$amount_accrued,
                        'customer_acceptance_status'=>'accepted',
                    ];
                    $package_bundle_1[] = $package_bundles;
                    
                    //rejected or declined updated
                    // $rejected_products = OutgoingStock::where('product_id', '!=', $productId)->where('order_id', $newOrder->id)
                    // ->where('reason_removed','as_order_firstphase')->get();
                    // foreach ($rejected_products as $key => $rejected) {
                    //     $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                    // }
                    
                } 
            }

            //now update each row package_bundle
            $outgoingStockPackageBundle = OutgoingStock::where('order_id', $newOrder->id)->first()->package_bundle;

            foreach ($outgoingStockPackageBundle as &$package_bundle) {
                // Find the corresponding package_bundle in $package_bundle_1 based on product_id
                $matching_package = collect($package_bundle_1)->firstWhere('product_id', $package_bundle['product_id']);
            
                // If a matching package is found, update the row in $outgoingStockPackageBundle
                if ($matching_package && $package_bundle['reason_removed']=='as_order_firstphase') {
                    // Merge the matching keys and values from $matching_package into $package_bundle
                    $package_bundle = array_merge($package_bundle, array_intersect_key($matching_package, $package_bundle));
                }
            }
        
            // Now $outgoingStockPackageBundle has the updated data
            //return $outgoingStockPackageBundle;

            //update outgoingStock
            OutgoingStock::where(['order_id'=>$newOrder->id])->update(['package_bundle' => $outgoingStockPackageBundle]);
                    
            $customer = new Customer();
            $customer->order_id = $newOrder->id;
            $customer->form_holder_id = $formHolder->id;
            $customer->firstname = $data['firstname'];
            $customer->lastname = $data['lastname'];
            $customer->phone_number = $data['phone_number'];
            $customer->whatsapp_phone_number = $data['whatsapp_phone_number'];
            $customer->email = $data['active_email'];
            $customer->city = $data['city'];
            $customer->state = $data['state'];
            $customer->delivery_address = $data['address'];
            $customer->delivery_duration = $data['delivery_duration'];
            $customer->created_by = 1;
            $customer->status = 'true';
            $customer->save();

            //update order status
            //DB::table('orders')->update(['customer_id'=>$customer->id, 'status'=>'new']);
            $newOrder = Order::find($newOrder->id);
            $newOrder->customer_id = $customer->id;
            $newOrder->status = 'new';
            $newOrder->expected_delivery_date = Carbon::parse($customer->created_at->addDays($customer->delivery_duration))->format('Y-m-d');
            $newOrder->save();

            $has_orderbump = isset($formHolder->orderbump_id) ? true : false;
            $has_upsell = isset($formHolder->upsell_id) ? true : false;
            $data['has_orderbump'] = $has_orderbump; 
            $data['has_upsell'] = $has_upsell;
            $data['order_id'] = $newOrder->id;

            //call notify fxn
            if ($has_orderbump==false && $has_upsell==false) {
                $this->invoiceData($formHolder, $customer, $newOrder);
            }

            return response()->json([
                'status'=>true,
                'data'=>$data,
            ]);

        } else {
            
            //update package in OutgoingStock
            $package_bundle_1 = [];
            foreach ($data['product_packages'] as $key => $product_id) {
                $data['product_id'] = $product_id;
                if (!empty($product_id)) {

                    $idPriceQty = explode('-', $product_id);
                    $productId = $idPriceQty[0];
                    $saleUnitPrice = $idPriceQty[1];
                    $qtyRemoved = $idPriceQty[2];

                    //accepted updated
                    $amount_accrued = $qtyRemoved * $saleUnitPrice;
                    //accepted updated
                    // OutgoingStock::where(['product_id'=>$productId, 'order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])
                    // ->update(['quantity_removed'=>$qtyRemoved, 'amount_accrued'=>$amount_accrued, 'customer_acceptance_status'=>'accepted']);

                    // Create a new package array for each product ID
                    $package_bundles = [
                        'product_id'=>$productId,
                        'quantity_removed'=>$qtyRemoved,
                        'amount_accrued'=>$amount_accrued,
                        'customer_acceptance_status'=>'accepted',
                    ];
                    $package_bundle_1[] = $package_bundles;
                    
                    //rejected or declined updated
                    // $rejected_products = OutgoingStock::where('product_id', '!=', $productId)->where('order_id', $order->id)
                    // ->where('reason_removed','as_order_firstphase')->get();
                    // foreach ($rejected_products as $key => $rejected) {
                    //     $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                    // }
                    
                } 
            }

            //now update each row package_bundle
            $outgoingStockPackageBundle = OutgoingStock::where('order_id', $order->id)->first()->package_bundle;

            foreach ($outgoingStockPackageBundle as &$package_bundle) {
                // Find the corresponding package_bundle in $package_bundle_1 based on product_id
                $matching_package = collect($package_bundle_1)->firstWhere('product_id', $package_bundle['product_id']);
            
                // If a matching package is found, update the row in $outgoingStockPackageBundle
                if ($matching_package && $package_bundle['reason_removed']=='as_order_firstphase') {
                    // Merge the matching keys and values from $matching_package into $package_bundle
                    $package_bundle = array_merge($package_bundle, array_intersect_key($matching_package, $package_bundle));
                }
            }
        
            // Now $outgoingStockPackageBundle has the updated data
            //return $outgoingStockPackageBundle;

            //update outgoingStock
            OutgoingStock::where(['order_id'=>$order->id])->update(['package_bundle' => $outgoingStockPackageBundle]);
        
            $customer = new Customer();
            $customer->order_id = $order->id;
            $customer->form_holder_id = $formHolder->id;
            $customer->firstname = $data['firstname'];
            $customer->lastname = $data['lastname'];
            $customer->phone_number = $data['phone_number'];
            $customer->whatsapp_phone_number = $data['whatsapp_phone_number'];
            $customer->email = $data['active_email'];
            $customer->city = $data['city'];
            $customer->state = $data['state'];
            $customer->delivery_address = $data['address'];
            $customer->delivery_duration = $data['delivery_duration'];
            $customer->created_by = 1;
            $customer->status = 'true';
            $customer->save();

            //update order status
            //DB::table('orders')->update(['customer_id'=>$customer->id, 'status'=>'new']);
            $order->customer_id = $customer->id;
            $order->status = 'new';
            $order->expected_delivery_date = Carbon::parse($customer->created_at->addDays($customer->delivery_duration))->format('Y-m-d');
            $order->save();
            
            $has_orderbump = isset($formHolder->orderbump_id) ? true : false;
            $has_upsell = isset($formHolder->upsell_id) ? true : false;
            $data['has_orderbump'] = $has_orderbump; 
            $data['has_upsell'] = $has_upsell;
            $data['order_id'] = $order->id;

            $data['order'] = $order->outgoingStock->package_bundle;
            
            //call notify fxn
            if ($has_orderbump==false && $has_upsell==false) {
                $this->invoiceData($formHolder, $customer, $order);
            }

            return response()->json([
                'status'=>true,
                'data'=>$data,
            ]);

        }
    }    

    //after clicking first main btn, ajax
    public function saveNewFormFromCustomer(Request $request)
    {
        // $authUser = auth()->user();
        // $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        //delete cartabandoned

        $cartAbandon = CartAbandon::where('id', $data['cartAbandoned_id']);
        if($cartAbandon->exists()) {
            $cartAbandon->delete();
        }
        
        $formHolder = FormHolder::where('unique_key', $data['unique_key'])->first();
        $order = $formHolder->order;

        //cos order was created initially @ newFormBuilderPost, incase the form originted from edited or duplicate form
        if (isset($order->customer_id)) {
    
            //save Order
            $newOrder = new Order();
            $newOrder->form_holder_id = $formHolder->id;
            $newOrder->source_type = 'form_holder_module';
            $newOrder->status = 'new';
            $newOrder->save();

            //making a copy from the former outgoingStocks, in the case of dealing with an edited or duplicated form
            // $outgoingStocks = $order->outgoingStocks;
            
            // foreach($outgoingStocks as $i => $outgoingStock)
            // {
            //     //make copy of rows, and create new records
            //     if(isset($outgoingStock->product)) {
            //         $outgoingStocks[$i]->order_id = $newOrder->id;
            //         $outgoingStocks[$i]->quantity_returned = 0;
            //         $outgoingStocks[$i]->quantity_removed = 1;
            //         $outgoingStocks[$i]->amount_accrued = $outgoingStock->product->sale_price;
            //         $outgoingStocks[$i]->isCombo = isset($outgoingStock->product->combo_product_ids) ? 'true' : null;
    
            //         $x[$i] = (new OutgoingStock())->create($outgoingStock->only(['product_id', 'order_id', 'quantity_removed', 'amount_accrued',
            //         'reason_removed', 'quantity_returned', 'created_by', 'status']));
            //     }
            // }
            // return $x;

            //making a copy from the former outgoingStocks, in the case of dealing with an edited or duplicated form

            //update $outgoingStockPackageBundle array
            $outgoingStock = $order->outgoingStock;
            // Check if $outgoingStock exists and is not null
            if ($outgoingStock) {
                // Convert the 'package_bundle' JSON into an associative array
                $packageBundle = $outgoingStock->package_bundle;

                // Loop through each package in the 'package_bundle' array
                foreach ($packageBundle as &$item) {
                    // Extract the 'product_id' from the package data
                    $productId = $item['product_id'];

                    // Find the related Product using 'Product::find($productId)'
                    $product = Product::find($productId);

                    // Check if a valid Product is found
                    if ($product) {
                        // Update the packageBundle data with additional information
                        $item['quantity_returned'] = 0;
                        $item['quantity_removed'] = 1;
                        $item['amount_accrued'] = $product->sale_price;
                        $item['isCombo'] = isset($product->combo_product_ids) ? 'true' : null;
                    }
                }

                // Convert the updated $packageBundle back to a JSON string
                // and update the 'package_bundle' property of $outgoingStock
                $outgoingStock->package_bundle = $packageBundle;

                // Create a new OutgoingStock record using the updated package data
                $newOutgoingStock = OutgoingStock::create([
                    'order_id' => $newOrder->id,
                    'created_by' => $outgoingStock->created_by,
                    'status' => $outgoingStock->status,
                    'package_bundle' => $outgoingStock->package_bundle,
                ]);

                // Return the new OutgoingStock object that we just created
                //return $newOutgoingStock;
            }

            // If $outgoingStock is not set or doesn't exist, we return null
            //return null;

            //////////////////////////////////////////////////////////
            //making a copy from the former outgoingStocks, in the case of dealing with an edited or duplicated form

            // $orderOutgoingStock = $order->outgoingStock;

            //update $outgoingStockPackageBundle array
            // $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
            // $package_bundle_1 = [];
            // //loop to get $package_bundle_1 array, if customer is null
            // foreach ($outgoingStockPackageBundle as &$main_outgoingStock) {
            //     if ( $main_outgoingStock['customer_acceptance_status'] == null ) {

            //         $package_bundles = [
            //             'quantity_returned'=>0,
            //             'quantity_removed'=>1,
            //         ];
            //         $package_bundle_1[] = $package_bundles;
            //     }
            // }

            // //loop to get new copy of $outgoingStockPackageBundle array
            // foreach ($outgoingStockPackageBundle as $key => &$value) {
            //     // Update values with similar keys
            //     if (isset($package_bundle_1[$key])) {
            //         $value = array_merge($value, $package_bundle_1[$key]);
            //     }
            // }
            ///////////////////////////////////////////////////////////

            // $newOutgoingStock = new OutgoingStock();
            // $newOutgoingStock->order_id = $newOrder->id;
            // $newOutgoingStock->package_bundle = $outgoingStockPackageBundle;
            // $newOutgoingStock->created_by = $order->outgoingStock->created_by;
            // $newOutgoingStock->status = $order->outgoingStock->status;
            // $newOutgoingStock->save();
        
            ///////////////////////////////////////////////////////////////////////////

            $mainProduct_revenue = 0;  //price * qty
            $qty_main_product = 0;
            // $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
            // 'customer_acceptance_status'=>'accepted'])->get();

            $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
            foreach ($outgoingStockPackageBundle as &$main_outgoingStock) {
                if ( ($main_outgoingStock['reason_removed'] == 'as_order_firstphase') && ($main_outgoingStock['customer_acceptance_status'] == 'accepted') ) {
                    $product = Product::where('id', $main_outgoingStock['product_id'])->first();
                    if (isset($product)) {
                        //array_push($mainProducts_outgoingStocks, array('product' => $product)); 
                        $main_outgoingStock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                        $mainProduct_revenue = $mainProduct_revenue + ($product->sale_price * $main_outgoingStock['quantity_removed']);
                        $qty_main_product += $main_outgoingStock['quantity_removed'];
                    }
                }
            }
    
            //convert to array to array-of-object
            $mainProducts_outgoingStocks = $mainProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : collect([]);
            ///////////////////////////////////////////////////////////////

            // $order = $order;
            $package_bundle_1 = [];
            //updated package in outgoingstock, created above
            foreach ($data['product_packages'] as $key => $product_id) {
                $data['product_id'] = $product_id;
                if (!empty($product_id)) {

                    $idPriceQty = explode('-', $product_id);
                    $productId = $idPriceQty[0];
                    $saleUnitPrice = $idPriceQty[1];
                    $qtyRemoved = $idPriceQty[2];

                    //accepted updated
                    $amount_accrued = $qtyRemoved * $saleUnitPrice;
                    // OutgoingStock::where(['product_id'=>$productId, 'order_id'=>$newOrder->id, 'reason_removed'=>'as_order_firstphase'])
                    // ->update(['quantity_removed'=>$qtyRemoved, 'amount_accrued'=>$amount_accrued, 'customer_acceptance_status'=>'accepted']);

                    // Create a new package array for each product ID
                    $package_bundles = [
                        'product_id'=>$productId,
                        'quantity_removed'=>$qtyRemoved,
                        'amount_accrued'=>$amount_accrued,
                        'customer_acceptance_status'=>'accepted',
                    ];
                    $package_bundle_1[] = $package_bundles;
                    
                    //rejected or declined updated
                    // $rejected_products = OutgoingStock::where('product_id', '!=', $productId)->where('order_id', $newOrder->id)
                    // ->where('reason_removed','as_order_firstphase')->get();
                    // foreach ($rejected_products as $key => $rejected) {
                    //     $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                    // }
                    
                } 
            }

            //now update each row package_bundle
            $outgoingStockPackageBundle = OutgoingStock::where('order_id', $newOrder->id)->first()->package_bundle;

            foreach ($outgoingStockPackageBundle as &$package_bundle) {
                // Find the corresponding package_bundle in $package_bundle_1 based on product_id
                $matching_package = collect($package_bundle_1)->firstWhere('product_id', $package_bundle['product_id']);
            
                // If a matching package is found, update the row in $outgoingStockPackageBundle
                if ($matching_package && $package_bundle['reason_removed']=='as_order_firstphase') {
                    // Merge the matching keys and values from $matching_package into $package_bundle
                    $package_bundle = array_merge($package_bundle, array_intersect_key($matching_package, $package_bundle));
                }
            }
        
            // Now $outgoingStockPackageBundle has the updated data
            //return $outgoingStockPackageBundle;

            //update outgoingStock
            OutgoingStock::where(['order_id'=>$newOrder->id])->update(['package_bundle' => $outgoingStockPackageBundle]);
                    
            $customer = new Customer();
            $customer->order_id = $newOrder->id;
            $customer->form_holder_id = $formHolder->id;
            $customer->firstname = $data['firstname'];
            $customer->lastname = $data['lastname'];
            $customer->phone_number = $data['phone_number'];
            $customer->whatsapp_phone_number = $data['whatsapp_phone_number'];
            $customer->email = $data['active_email'];
            $customer->city = $data['city'];
            $customer->state = $data['state'];
            $customer->delivery_address = $data['address'];
            $customer->delivery_duration = $data['delivery_duration'];
            $customer->created_by = 1;
            $customer->status = 'true';
            $customer->save();

            //update order status
            //DB::table('orders')->update(['customer_id'=>$customer->id, 'status'=>'new']);
            $newOrder = Order::find($newOrder->id);
            $newOrder->customer_id = $customer->id;
            $newOrder->status = 'new';
            $newOrder->expected_delivery_date = Carbon::parse($customer->created_at->addDays($customer->delivery_duration))->format('Y-m-d');
            $newOrder->save();

            $has_orderbump = isset($formHolder->orderbump_id) ? true : false;
            $has_upsell = isset($formHolder->upsell_id) ? true : false;
            $data['has_orderbump'] = $has_orderbump; 
            $data['has_upsell'] = $has_upsell;
            $data['order_id'] = $newOrder->id;

            //call notify fxn
            if ($has_orderbump==false && $has_upsell==false) {
                $this->invoiceData($formHolder, $customer, $newOrder);
            }

            return response()->json([
                'status'=>true,
                'data'=>$data,
            ]);

        } else {
            
            //update package in OutgoingStock
            $package_bundle_1 = [];
            foreach ($data['product_packages'] as $key => $product_id) {
                $data['product_id'] = $product_id;
                if (!empty($product_id)) {

                    $idPriceQty = explode('-', $product_id);
                    $productId = $idPriceQty[0];
                    $saleUnitPrice = $idPriceQty[1];
                    $qtyRemoved = $idPriceQty[2];

                    //accepted updated
                    $amount_accrued = $qtyRemoved * $saleUnitPrice;
                    //accepted updated
                    // OutgoingStock::where(['product_id'=>$productId, 'order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])
                    // ->update(['quantity_removed'=>$qtyRemoved, 'amount_accrued'=>$amount_accrued, 'customer_acceptance_status'=>'accepted']);

                    // Create a new package array for each product ID
                    $package_bundles = [
                        'product_id'=>$productId,
                        'quantity_removed'=>$qtyRemoved,
                        'amount_accrued'=>$amount_accrued,
                        'customer_acceptance_status'=>'accepted',
                    ];
                    $package_bundle_1[] = $package_bundles;
                    
                    //rejected or declined updated
                    // $rejected_products = OutgoingStock::where('product_id', '!=', $productId)->where('order_id', $order->id)
                    // ->where('reason_removed','as_order_firstphase')->get();
                    // foreach ($rejected_products as $key => $rejected) {
                    //     $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                    // }
                    
                } 
            }

            //now update each row package_bundle
            $outgoingStockPackageBundle = OutgoingStock::where('order_id', $order->id)->first()->package_bundle;

            foreach ($outgoingStockPackageBundle as &$package_bundle) {
                // Find the corresponding package_bundle in $package_bundle_1 based on product_id
                $matching_package = collect($package_bundle_1)->firstWhere('product_id', $package_bundle['product_id']);
            
                // If a matching package is found, update the row in $outgoingStockPackageBundle
                if ($matching_package && $package_bundle['reason_removed']=='as_order_firstphase') {
                    // Merge the matching keys and values from $matching_package into $package_bundle
                    $package_bundle = array_merge($package_bundle, array_intersect_key($matching_package, $package_bundle));
                }
            }
        
            // Now $outgoingStockPackageBundle has the updated data
            //return $outgoingStockPackageBundle;

            //update outgoingStock
            OutgoingStock::where(['order_id'=>$order->id])->update(['package_bundle' => $outgoingStockPackageBundle]);
        
            $customer = new Customer();
            $customer->order_id = $order->id;
            $customer->form_holder_id = $formHolder->id;
            $customer->firstname = $data['firstname'];
            $customer->lastname = $data['lastname'];
            $customer->phone_number = $data['phone_number'];
            $customer->whatsapp_phone_number = $data['whatsapp_phone_number'];
            $customer->email = $data['active_email'];
            $customer->city = $data['city'];
            $customer->state = $data['state'];
            $customer->delivery_address = $data['address'];
            $customer->delivery_duration = $data['delivery_duration'];
            $customer->created_by = 1;
            $customer->status = 'true';
            $customer->save();

            //update order status
            //DB::table('orders')->update(['customer_id'=>$customer->id, 'status'=>'new']);
            $order->customer_id = $customer->id;
            $order->status = 'new';
            $order->expected_delivery_date = Carbon::parse($customer->created_at->addDays($customer->delivery_duration))->format('Y-m-d');
            $order->save();
            
            $has_orderbump = isset($formHolder->orderbump_id) ? true : false;
            $has_upsell = isset($formHolder->upsell_id) ? true : false;
            $data['has_orderbump'] = $has_orderbump; 
            $data['has_upsell'] = $has_upsell;
            $data['order_id'] = $order->id;

            $data['order'] = $order->outgoingStock->package_bundle;
            
            //call notify fxn
            if ($has_orderbump==false && $has_upsell==false) {
                $this->invoiceData($formHolder, $customer, $order);
            }

            return response()->json([
                'status'=>true,
                'data'=>$data,
            ]);

        }
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
