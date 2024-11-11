<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\ThankYou;
use App\Models\OutgoingStock;
use App\Models\FormHolder;
use App\Models\Order;
use App\Models\Product;

class ThankYouSettingController extends Controller
{
    public function thankYouTemplates()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $thankYouTemplates = ThankYou::all();
        return view('pages.settings.thankYou.allThankYou', \compact('authUser', 'user_role', 'thankYouTemplates'));
    }

    public function addThankYouTemplate()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        return view('pages.settings.thankYou.addThankYou', \compact('authUser', 'user_role'));
    }

    public function addThankYouTemplatePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'heading_text' => 'required|string',
            'subheading_text' => 'required|string',
            'template_name' => 'required|string|unique:thank_yous',
        ]);

        $data = $request->all();

        $thankYou = new ThankYou();

        $thankYou->template_name = $data['template_name']; //unique

        $thankYou->body_bg_color = $data['body_bg_color'];
        $thankYou->body_border_style = $data['body_border_style'];
        $thankYou->body_border_color = $data['body_border_color'];
        $thankYou->body_border_thickness = $data['body_border_thickness'];
        $thankYou->body_border_radius = $data['body_border_radius'];
        $thankYou->heading_text = $data['heading_text'];

        $thankYou->heading_text_style = $data['heading_text_style'];
        $thankYou->heading_text_align = $data['heading_text_align'];
        $thankYou->heading_text_color = $data['heading_text_color'];
        $thankYou->heading_text_weight = $data['heading_text_weight'];
        $thankYou->heading_text_size = $data['heading_text_size'];

        $thankYou->subheading_text = $data['subheading_text'];
        $thankYou->subheading_text_style = $data['subheading_text_style'];
        $thankYou->subheading_text_color = $data['subheading_text_color'];
        $thankYou->subheading_text_weight = $data['subheading_text_weight'];
        $thankYou->subheading_text_size = $data['subheading_text_size'];
        $thankYou->subheading_text_align = $data['subheading_text_align'];

        $thankYou->button_text = $data['button_text'];
        $thankYou->button_bg_color = $data['button_bg_color'];
        $thankYou->button_text_style = $data['button_text_style'];
        $thankYou->button_text_align = $data['button_text_align'];
        $thankYou->button_text_color = $data['button_text_color'];
        $thankYou->button_text_weight = $data['button_text_weight'];
        $thankYou->button_text_size = $data['button_text_size'];

        $thankYou->onhover_button_bg_color = $data['onhover_button_bg_color'];
        $thankYou->onhover_button_border_color = $data['onhover_button_border_color'];
        $thankYou->onhover_button_text_style = $data['onhover_button_text_style'];
        $thankYou->onhover_button_text_color = $data['onhover_button_text_color'];
        $thankYou->onhover_button_text_weight = $data['onhover_button_text_weight'];
        $thankYou->onhover_button_text_size = $data['onhover_button_text_size'];

        $thankYou->created_by = $authUser->id;
        $thankYou->status = 'true';

        $thankYou->save();

        return back()->with('success', 'Template Built Successfully!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //shown from frontend
    public function showThankYouTemplate($unique_key, $current_order_id = "")
    {
        // $authUser = auth()->user();
        // $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $thankYouTemplate = ThankYou::where('unique_key', $unique_key);
        if (!$thankYouTemplate->exists()) {
            abort(404);
        }
        $thankYou = $thankYouTemplate->first();
        $current_order_id = isset($thankYou->current_order_id) ? $thankYou->current_order_id : "";

        $customer = ''; // to check against when the thankyou pg will be rendered

        $order = '';
        $orderId = '';
        $qty_total = 0;
        $order_total_amount = 0;
        $grand_total = 0;
        $mainProducts_outgoingStocks = '';
        $orderbumpProduct_revenue = 0;
        $orderbump_outgoingStock = '';
        $upsellProduct_revenue = 0;
        $upsell_outgoingStock = '';
        if ($current_order_id !== "") {
            $order = Order::where('id', $current_order_id)->first();
            if (!isset($order)) {
                \abort(404);
            }
            if (isset($order->customer)) {
                $order->outgoingStock()->where(['customer_acceptance_status' => NULL])
                    ->update(['customer_acceptance_status' => 'rejected', 'quantity_returned' => 1, 'reason_returned' => 'declined']);

                $formHolder = $order->formHolder;

                //update formholder or order
                if (isset($formHolder->staff_assigned_id) && !isset($order->staff_assigned_id)) {
                    $order->update(['staff_assigned_id' => $formHolder->staff_assigned_id]);
                }

                //get customer
                $customer =  $order->customer;
                $orderId = $order->orderId($order);

                $mainProduct_revenue = 0;  //price * qty
                $qty_main_product = 0;
                //  $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
                //  'customer_acceptance_status'=>'accepted'])->get();

                //  if ( count($mainProducts_outgoingStocks) > 0 ) {
                //      foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                //         if(isset($main_outgoingStock->product)) {
                //             $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
                //         }
                //      }
                //  }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
                foreach ($outgoingStockPackageBundle as &$main_outgoingStock) {
                    if (($main_outgoingStock['reason_removed'] == 'as_order_firstphase') && ($main_outgoingStock['customer_acceptance_status'] == 'accepted')) {
                        $product = Product::where('id', $main_outgoingStock['product_id'])->first();
                        if (isset($product)) {
                            //array_push($mainProducts_outgoingStocks, array('product' => $product)); 
                            $main_outgoingStock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                            $mainProduct_revenue = $mainProduct_revenue + ($product->sale_price * $main_outgoingStock['quantity_removed']);
                            $qty_main_product += $main_outgoingStock['quantity_removed'];
                        }
                    }
                }
                $mainProducts_outgoingStocks = $mainProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : collect([]);
                //////////////////////////////////////////////////////////////

                //orderbump
                $orderbumpProduct_revenue = 0; //price * qty
                $orderbump_outgoingStock = '';
                $qty_orderbump = 0;
                //  if (isset($formHolder->orderbump_id)) {
                //      $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
                //      if (isset($orderbump_outgoingStock->product) && $orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
                //          $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
                //      }
                //  }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]

                if (isset($formHolder->orderbump_id)) {
                    foreach ($outgoingStockPackageBundle as &$orderbump_stock) {
                        if (($orderbump_stock['reason_removed'] == 'as_orderbump') && ($orderbump_stock['customer_acceptance_status'] == 'accepted')) {
                            $product = Product::where('id', $orderbump_stock['product_id'])->first();
                            if (isset($product)) {
                                $orderbump_stock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                                $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($product->sale_price * $orderbump_stock['quantity_removed']);
                                $qty_orderbump += $orderbump_stock['quantity_removed'];
                            }
                        }
                    }
                }
                $orderbump_outgoingStock = $orderbumpProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : '';

                //upsell
                $upsellProduct_revenue = 0; //price * qty
                $upsell_outgoingStock = '';
                $qty_upsell = 0;
                //  if (isset($formHolder->upsell_id)) {
                //      $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
                //      if (isset($upsell_outgoingStock->product) && $upsell_outgoingStock->customer_acceptance_status == 'accepted') {
                //          $upsellProduct_revenue += $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
                //      }
                //  }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]

                if (isset($formHolder->upsell_id)) {
                    foreach ($outgoingStockPackageBundle as &$upsell_stock) {
                        if (($upsell_stock['reason_removed'] == 'as_upsell') && ($upsell_stock['customer_acceptance_status'] == 'accepted')) {
                            $product = Product::where('id', $upsell_stock['product_id'])->first();
                            if (isset($product)) {
                                $upsell_stock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                                $upsellProduct_revenue = $upsellProduct_revenue + ($product->sale_price * $upsell_stock['quantity_removed']);
                                $qty_upsell += $upsell_stock['quantity_removed'];
                            }
                        }
                    }
                }
                $upsell_outgoingStock = $upsellProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : '';

                //order total amt
                $order_total_amount = $mainProduct_revenue + $orderbumpProduct_revenue + $upsellProduct_revenue;
                $grand_total = $order_total_amount; //might include discount later

                $orderId = ''; //used in thankYou section
                if (isset($order->id)) {
                    $orderId = $order->orderId($order);
                }

                //package or product qty. sum = 0, if it doesnt exist
                //  $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
                //  $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
                //  $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
                $qty_total = $qty_main_product + $qty_orderbump + $qty_upsell;
            }
        }

        return view('pages.settings.thankYou.singleThankYou', \compact(
            'thankYou',
            'order',
            'orderId',
            'customer',
            'qty_total',
            'order_total_amount',
            'grand_total',
            'mainProducts_outgoingStocks',
            'orderbumpProduct_revenue',
            'orderbump_outgoingStock',
            'upsellProduct_revenue',
            'upsell_outgoingStock'
        ));
    }

    //returns redirect-away to template_external_url
    public function singleThankYouTemplate($unique_key, $current_order_id = "")
    {
        // $authUser = auth()->user();
        // $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $thankYouTemplate = ThankYou::where('unique_key', $unique_key);
        if (!$thankYouTemplate->exists()) {
            abort(404);
        }
        $thankYou = $thankYouTemplate->first();

        //update $thankYou tbl
        if ($current_order_id !== "") {
            $thankYou->current_order_id = $current_order_id;
            $thankYou->save();
        }

        $template_name = $thankYou->template_name;
        $current_order_id = isset($thankYou->current_order_id) ? $thankYou->current_order_id : "";

        $customer = ''; // to check against when the thankyou pg will be rendered

        $order = '';
        $orderId = '';
        $qty_total = 0;
        $order_total_amount = 0;
        $order_discount_amount = 0;
        $grand_total = 0;
        $mainProducts_outgoingStocks = '';
        $orderbumpProduct_revenue = 0;
        $orderbump_outgoingStock = '';
        $upsellProduct_revenue = 0;
        $upsell_outgoingStock = '';
        $discount = 0;
        if ($current_order_id !== "") {
            $order = Order::where('id', $current_order_id)->first();
            if (!isset($order)) {
                \abort(404);
            }
            if (isset($order->customer)) {
                // $order->outgoingStocks()->where(['customer_acceptance_status'=>NULL])
                // ->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>1, 'reason_returned'=>'declined']);

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
                $package_bundle_1 = [];
                //loop to get $package_bundle_1 array, if customer is null
                foreach ($outgoingStockPackageBundle as &$main_outgoingStock) {
                    if ($main_outgoingStock['customer_acceptance_status'] == null) {

                        $package_bundles = [
                            'customer_acceptance_status' => 'rejected',
                            'reason_removed' => 'as_order_firstphase',
                            'quantity_returned' => 1,
                            'reason_returned' => 'declined',
                        ];
                        $package_bundle_1[] = $package_bundles;
                    }
                }

                //loop to get new copy of $outgoingStockPackageBundle array
                foreach ($outgoingStockPackageBundle as $key => &$value) {
                    // Update values with similar keys
                    if (isset($package_bundle_1[$key])) {
                        $value = array_merge($value, $package_bundle_1[$key]);
                    }
                }
                //pudate db column with new copy of $outgoingStockPackageBundle
                $order->outgoingStock()->update(['package_bundle' => $outgoingStockPackageBundle]);

                $formHolder = $order->formHolder;

                //update formholder or order
                if (isset($formHolder->staff_assigned_id) && !isset($order->staff_assigned_id)) {
                    $order->update(['staff_assigned_id' => $formHolder->staff_assigned_id]);
                }

                //get customer
                $customer =  $order->customer;
                $orderId = $order->orderId($order);

                //mainProduct_revenue
                $mainProduct_revenue = 0;  //price * qty
                $qty_main_product = 0;
                // $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
                // 'customer_acceptance_status'=>'accepted'])->get();

                // if ( count($mainProducts_outgoingStocks) > 0 ) {
                //     foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                //         if(isset($main_outgoingStock->product)) {
                //             $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
                //         }
                //     }
                // }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
                foreach ($outgoingStockPackageBundle as &$main_outgoingStock) {
                    if (($main_outgoingStock['reason_removed'] == 'as_order_firstphase') && ($main_outgoingStock['customer_acceptance_status'] == 'accepted')) {
                        $product = Product::where('id', $main_outgoingStock['product_id'])->first();
                        if (isset($product)) {
                            //array_push($mainProducts_outgoingStocks, array('product' => $product)); 
                            $main_outgoingStock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                            $mainProduct_revenue = $mainProduct_revenue + ($product->sale_price * $main_outgoingStock['quantity_removed']);
                            $qty_main_product += $main_outgoingStock['quantity_removed'];

                            $order_discount_amount += (int)(new \App\Helpers\helper())->stockDiscount(
                                $main_outgoingStock['amount_accrued'] ?? 0,
                                $main_outgoingStock['discount_amount'] ?? 0,
                                $main_outgoingStock['discount_type'] ?? 'fixed',
                            );
                        }
                    }
                }
                $mainProducts_outgoingStocks = $mainProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : collect([]);
                //////////////////////////////////////////////////////////////

                //orderbump
                $orderbumpProduct_revenue = 0; //price * qty
                $orderbump_outgoingStock = '';
                $qty_orderbump = 0;
                // if (isset($formHolder->orderbump_id)) {
                //     $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
                //     if (isset($orderbump_outgoingStock->product) && $orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
                //         $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
                //     }
                // }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]

                if (isset($formHolder->orderbump_id)) {
                    foreach ($outgoingStockPackageBundle as &$orderbump_stock) {
                        if (($orderbump_stock['reason_removed'] == 'as_orderbump') && ($orderbump_stock['customer_acceptance_status'] == 'accepted')) {
                            $product = Product::where('id', $orderbump_stock['product_id'])->first();
                            if (isset($product)) {
                                $orderbump_stock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                                $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($product->sale_price * $orderbump_stock['quantity_removed']);
                                $qty_orderbump += $orderbump_stock['quantity_removed'];

                                $order_discount_amount += (int)(new \App\Helpers\helper())->stockDiscount(
                                    $orderbump_stock['amount_accrued'] ?? 0,
                                    $orderbump_stock['discount_amount'] ?? 0,
                                    $orderbump_stock['discount_type'] ?? 'fixed',
                                );
                            }
                        }
                    }
                }
                $orderbump_outgoingStock = $orderbumpProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : '';

                //upsell
                $upsellProduct_revenue = 0; //price * qty
                $upsell_outgoingStock = '';
                $qty_upsell = 0;
                // if (isset($formHolder->upsell_id)) {
                //     $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
                //     if (isset($upsell_outgoingStock->product) && $upsell_outgoingStock->customer_acceptance_status == 'accepted') {
                //         $upsellProduct_revenue += $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
                //     }
                // }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]

                if (isset($formHolder->upsell_id)) {
                    foreach ($outgoingStockPackageBundle as &$upsell_stock) {
                        if (($upsell_stock['reason_removed'] == 'as_upsell') && ($upsell_stock['customer_acceptance_status'] == 'accepted')) {
                            $product = Product::where('id', $upsell_stock['product_id'])->first();
                            if (isset($product)) {
                                $upsell_stock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                                $upsellProduct_revenue = $upsellProduct_revenue + ($product->sale_price * $upsell_stock['quantity_removed']);
                                $qty_upsell += $upsell_stock['quantity_removed'];

                                $order_discount_amount += (int)(new \App\Helpers\helper())->stockDiscount(
                                    $upsell_stock['amount_accrued'] ?? 0,
                                    $upsell_stock['discount_amount'] ?? 0,
                                    $upsell_stock['discount_type'] ?? 'fixed',
                                );
                            }
                        }
                    }
                }
                $upsell_outgoingStock = $upsellProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : '';

                //order total amt
                $order_total_amount = $mainProduct_revenue + $orderbumpProduct_revenue + $upsellProduct_revenue;
                $discount = $order_total_amount - $order_discount_amount;
                $grand_total = $order_total_amount; //might include discount later


                $orderId = ''; //used in thankYou section
                if (isset($order->id)) {
                    $orderId = $order->orderId($order);
                }

                //package or product qty. sum = 0, if it doesnt exist
                // $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
                // $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
                // $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
                $qty_total = $qty_main_product + $qty_orderbump + $qty_upsell;
            }
        }
        // $url = $thankYou->template_external_url;

        // // $redirectUrl = Redirect::away($url . '?templ=' . urlencode($template_name));
        // $redirectUrl = Redirect::away($url); 
        // return $redirectUrl;

        // Existing external URL
        $url = $thankYou->template_external_url;
        if ($url) {
            # code...



            // Parse the URL to modify its query parameters
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'] ?? '', $queryParams);

            // Add or update the order ID parameter
            $queryParams['kiptrak-backend-order-id'] = $current_order_id; // Replace $current_order_id with your actual order ID
            $queryParams['kiptrak-backend-form-unique-key'] = $formHolder->unique_key;
            // Build the updated query string
            $updatedQueryString = http_build_query($queryParams);

            // Reconstruct the full URL with the updated query string
            $updatedUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] .
                (isset($parsedUrl['path']) ? $parsedUrl['path'] : '') .
                '?' . $updatedQueryString;

            // Redirect to the updated URL
            $redirectUrl = Redirect::away($updatedUrl);
            return $redirectUrl;
        }
        // dd($url);

        // dd($discount);

        return view('pages.settings.thankYou.singleThankYou', \compact(
            'thankYou',
            'order',
            'orderId',
            'customer',
            'qty_total',
            'order_total_amount',
            'order_discount_amount',
            'grand_total',
            'mainProducts_outgoingStocks',
            'orderbumpProduct_revenue',
            'orderbump_outgoingStock',
            'upsellProduct_revenue',
            'upsell_outgoingStock',
            'discount'
        ));
    }

    public function editThankYouTemplate($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $thankYouTemplate = ThankYou::where('unique_key', $unique_key);
        if (!$thankYouTemplate->exists()) {
            abort(404);
        }
        $thankYou = $thankYouTemplate->first();

        return view('pages.settings.thankYou.editThankYou', \compact('authUser', 'user_role', 'thankYou'));
    }

    public function editThankYouTemplatePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $thankYouTemplate = ThankYou::where('unique_key', $unique_key);
        if (!$thankYouTemplate->exists()) {
            abort(404);
        }
        $thankYou = $thankYouTemplate->first();

        $request->validate([
            'heading_text' => 'required|string',
            'subheading_text' => 'required|string',
            'template_name' => 'required|string',
        ]);

        $data = $request->all();

        $thankYou->template_name = $data['template_name']; //unique

        $thankYou->body_bg_color = $data['body_bg_color'];
        $thankYou->body_border_style = $data['body_border_style'];
        $thankYou->body_border_color = $data['body_border_color'];
        $thankYou->body_border_thickness = $data['body_border_thickness'];
        $thankYou->body_border_radius = $data['body_border_radius'];

        $thankYou->heading_text = $data['heading_text'];
        $thankYou->heading_text_style = $data['heading_text_style'];
        $thankYou->heading_text_align = $data['heading_text_align'];
        $thankYou->heading_text_color = $data['heading_text_color'];
        $thankYou->heading_text_weight = $data['heading_text_weight'];
        $thankYou->heading_text_size = $data['heading_text_size'];

        $thankYou->subheading_text = $data['subheading_text'];
        $thankYou->subheading_text_style = $data['subheading_text_style'];
        $thankYou->subheading_text_color = $data['subheading_text_color'];
        $thankYou->subheading_text_weight = $data['subheading_text_weight'];
        $thankYou->subheading_text_size = $data['subheading_text_size'];
        $thankYou->subheading_text_align = $data['subheading_text_align'];

        $thankYou->button_text = $data['button_text'];
        $thankYou->button_bg_color = $data['button_bg_color'];
        $thankYou->button_text_style = $data['button_text_style'];
        $thankYou->button_text_align = $data['button_text_align'];
        $thankYou->button_text_color = $data['button_text_color'];
        $thankYou->button_text_weight = $data['button_text_weight'];
        $thankYou->button_text_size = $data['button_text_size'];

        $thankYou->onhover_button_bg_color = $data['onhover_button_bg_color'];
        $thankYou->onhover_button_border_color = $data['onhover_button_border_color'];
        $thankYou->onhover_button_text_style = $data['onhover_button_text_style'];
        $thankYou->onhover_button_text_color = $data['onhover_button_text_color'];
        $thankYou->onhover_button_text_weight = $data['onhover_button_text_weight'];
        $thankYou->onhover_button_text_size = $data['onhover_button_text_size'];

        $thankYou->created_by = $authUser->id;
        $thankYou->status = 'true';

        $thankYou->save();

        return back()->with('success', 'Template Updated Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function thankYouEmbedded($unique_key)
    {
        // $authUser = auth()->user();
        // $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $thankYouTemplate = ThankYou::where('unique_key', $unique_key);
        if (!$thankYouTemplate->exists()) {
            abort(404);
        }
        $thankYou = $thankYouTemplate->first();
        $current_order_id = isset($thankYou->current_order_id) ? $thankYou->current_order_id : "";

        $customer = ''; // to check against when the thankyou pg will be rendered

        $order = '';
        $orderId = '';
        $qty_total = 0;
        $order_total_amount = 0;
        $grand_total = 0;
        $mainProducts_outgoingStocks = '';
        $orderbumpProduct_revenue = 0;
        $orderbump_outgoingStock = '';
        $upsellProduct_revenue = 0;
        $upsell_outgoingStock = '';
        if ($current_order_id !== "") {
            $order = Order::where('id', $current_order_id)->first();
            if (!isset($order)) {
                \abort(404);
            }
            if (isset($order->customer)) {
                // $order->outgoingStocks()->where(['customer_acceptance_status'=>NULL])
                // ->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>1, 'reason_returned'=>'declined']);

                /////////////////////////
                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
                $package_bundle_1 = [];

                // Loop through the $outgoingStockPackageBundle array with access to keys
                foreach ($outgoingStockPackageBundle as $key => $value) {
                    if (empty($value['customer_acceptance_status'])) {
                        // Merge the data from $package_bundle_1 into the $outgoingStockPackageBundle
                        $outgoingStockPackageBundle[$key]['customer_acceptance_status'] = 'rejected';
                        $outgoingStockPackageBundle[$key]['reason_removed'] = 'as_order_firstphase';
                        $outgoingStockPackageBundle[$key]['quantity_returned'] = 1;
                        $outgoingStockPackageBundle[$key]['reason_returned'] = 'declined';
                    }
                }

                //pudate db column with new copy of $outgoingStockPackageBundle
                $order->outgoingStock()->update(['package_bundle' => $outgoingStockPackageBundle]);
                ////////////////////////

                $formHolder = $order->formHolder;

                //get customer
                $customer =  $order->customer;
                $orderId = $order->orderId($order);

                $mainProduct_revenue = 0;  //price * qty
                $qty_main_product = 0;
                // $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
                // 'customer_acceptance_status'=>'accepted'])->get();

                // if ( count($mainProducts_outgoingStocks) > 0 ) {
                //     foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                //         if(isset($main_outgoingStock->product)) {
                //             $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
                //         }
                //     }
                // }

                ////////////////
                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]
                foreach ($outgoingStockPackageBundle as $key => &$main_outgoingStock) {

                    if (($main_outgoingStock['reason_removed'] == 'as_order_firstphase') && ($main_outgoingStock['customer_acceptance_status'] == 'accepted')) {
                        $product = Product::where('id', $main_outgoingStock['product_id'])->first();
                        if (isset($product)) {
                            //array_push($mainProducts_outgoingStocks, array('product' => $product)); 
                            $main_outgoingStock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                            $mainProduct_revenue = $mainProduct_revenue + ($product->sale_price * $main_outgoingStock['quantity_removed']);
                            $qty_main_product += $main_outgoingStock['quantity_removed'];
                        }
                    } else {
                        // Remove the element from the array if the condition is not met
                        unset($outgoingStockPackageBundle[$key]);
                    }
                }
                //convert to array to array-of-object
                $mainProducts_outgoingStocks = $mainProduct_revenue > 0 ? json_decode(json_encode($outgoingStockPackageBundle)) : collect([]);
                ////////////////

                //orderbump
                $orderbumpProduct_revenue = 0; //price * qty
                $orderbump_outgoingStock = '';
                $qty_orderbump = 0;
                // if (isset($formHolder->orderbump_id)) {
                //     $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
                //     if (isset($orderbump_outgoingStock->product) && $orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
                //         $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
                //     }
                // }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]

                if (isset($formHolder->orderbump_id)) {
                    foreach ($outgoingStockPackageBundle as $key => &$orderbump_stock) {
                        if (($orderbump_stock['reason_removed'] == 'as_orderbump') && ($orderbump_stock['customer_acceptance_status'] == 'accepted')) {
                            $product = Product::where('id', $orderbump_stock['product_id'])->first();
                            if (isset($product)) {
                                $orderbump_stock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                                $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($product->sale_price * $orderbump_stock['quantity_removed']);
                                $qty_orderbump += $orderbump_stock['quantity_removed'];
                            }
                        } else {
                            // Remove the element from the array if the condition is not met
                            unset($outgoingStockPackageBundle[$key]);
                        }
                    }
                }
                $orderbump_outgoingStock = $orderbumpProduct_revenue > 0 ? json_decode(json_encode(array_merge(...array_values($outgoingStockPackageBundle)))) : '';

                //upsell
                $upsellProduct_revenue = 0; //price * qty
                $upsell_outgoingStock = '';
                $qty_upsell = 0;
                // if (isset($formHolder->upsell_id)) {
                //     $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
                //     if (isset($upsell_outgoingStock->product) && $upsell_outgoingStock->customer_acceptance_status == 'accepted') {
                //         $upsellProduct_revenue += $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
                //     }
                // }

                $outgoingStockPackageBundle = $order->outgoingStock->package_bundle; //[{}, {}]

                if (isset($formHolder->upsell_id)) {
                    foreach ($outgoingStockPackageBundle as $key => &$upsell_stock) {
                        if (($upsell_stock['reason_removed'] == 'as_upsell') && ($upsell_stock['customer_acceptance_status'] == 'accepted')) {
                            $product = Product::where('id', $upsell_stock['product_id'])->first();
                            if (isset($product)) {
                                $upsell_stock['product'] = $product; //append 'product' key to $outgoingStockPackageBundle array
                                $upsellProduct_revenue = $upsellProduct_revenue + ($product->sale_price * $upsell_stock['quantity_removed']);
                                $qty_upsell += $upsell_stock['quantity_removed'];
                            }
                        } else {
                            // Remove the element from the array if the condition is not met
                            unset($outgoingStockPackageBundle[$key]);
                        }
                    }
                }
                $upsell_outgoingStock = $upsellProduct_revenue > 0 ? json_decode(json_encode(array_merge(...array_values($outgoingStockPackageBundle)))) : '';

                //order total amt
                $order_total_amount = $mainProduct_revenue + $orderbumpProduct_revenue + $upsellProduct_revenue;
                $grand_total = $order_total_amount; //might include discount later

                $orderId = ''; //used in thankYou section
                if (isset($order->id)) {
                    $orderId = $order->orderId($order);
                }

                //package or product qty. sum = 0, if it doesnt exist
                // $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
                // $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
                // $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
                $qty_total = $qty_main_product + $qty_orderbump + $qty_upsell;
            }
        }

        return view('pages.settings.thankYou.thankYouEmbedded', \compact(
            'thankYou',
            'order',
            'orderId',
            'customer',
            'qty_total',
            'order_total_amount',
            'grand_total',
            'mainProducts_outgoingStocks',
            'orderbumpProduct_revenue',
            'orderbump_outgoingStock',
            'upsellProduct_revenue',
            'upsell_outgoingStock'
        ));
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
