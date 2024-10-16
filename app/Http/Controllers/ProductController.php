<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\IncomingStock;
use App\Models\OutgoingStock;
use App\Models\Country;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\WareHouse;
use App\Models\ProductWarehouse;
use App\Models\GeneralSetting;


class ProductController extends Controller
{
    public function addProduct()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        // $pro = Product::find('1');
        // return unserialize($pro->features) == [null] ? 'noting' : 'yes';
        // $currencies = array(
        //  array("nationality" => "Nigerian", "currency" => "Naira", "symbol" => "₦",),
        //  array("nationality" => "Ghanian", "currency" => "Cedis", "symbol" => "GH₵",),
        //  array("nationality" => "Kenyan", "currency" => "Shilling", "symbol" => "KES",),
        //  array("nationality" => "US", "currency" => "Dollar", "symbol" => "$",),
        //  array("nationality" => "UK", "currency" => "Pound", "symbol" => "£",),
        // );

        $units = array(
            array("name" => "Kilogram", "symbol" => "Kg",),
            array("name" => "Gram", "symbol" => "g",),
            array("name" => "Milligram", "symbol" => "mg",),
            array("name" => "Volume", "symbol" => "Vol",),
            array("name" => "Litre", "symbol" => "L",),
            array("name" => "Centilitre", "symbol" => "Cl",),
            array("name" => "Sachet", "symbol" => "Sachet",),
            array("name" => "Container", "symbol" => "Container",),
            array("name" => "Bottle", "symbol" => "Bottle",),
            array("name" => "Packet", "symbol" => "Packet",),
            array("name" => "Item", "symbol" => "Item",),
        );
        $countries = Country::all();
        $categories = Category::all();
        $warehouses = WareHouse::all();
        $agents = User::where('type', 'agent')->get();

        return view('pages.products.addProduct', compact('authUser', 'user_role', 'countries', 'units', 'categories', 'warehouses', 'agents'));
    }

    public function addProductPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|numeric',
            'category' => 'required',
            // 'color' => 'nullable|string',
            // 'size' => 'nullable|string',
            'currency' => 'required',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric|gt:purchase_price',
            'code' => 'nullable|string|unique:products',
            // 'features' => 'nullable|array',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp',
        ]);

        $data = $request->all();
        $product = new Product();
        $product->name = $data['name'];
        // $product->quantity = $data['quantity']; //handled by inComingStocks
        $product->category_id = $data['category'];
        // $product->warehouse_id = !empty($data['warehouse']) ? $data['warehouse'] : null;
        $product->color = !empty($data['color']) ? $data['color'] : null;
        $product->size = !empty($data['size']) ? $data['size'] : null;
        $product->country_id = $data['currency']; //country_id
        $product->purchase_price = $data['purchase_price'];
        $product->sale_price = $data['sale_price'];

        // if (empty($data['code'])) {
        //     $count = Product::count() + 1;
        //     $product->code = $count.'PRD'.$code;
        // }else{
        //     $product->code = $data['code'];
        // }

        $product->features = !empty($data['features']) ? serialize($data['features']) : null;

        // $product->warehouse_id =  !empty($data['warehouse_id']) ? $data['warehouse_id'] : null;
        $product->created_by = $authUser->id;
        $product->status = 'true';

        //image
        $imageName = time() . '.' . $request->image->extension();
        //store products in folder
        $request->image->storeAs('products', $imageName, 'public');
        $product->image = $imageName;
        $product->save();

        //warehouse, qty updated here
        if (!empty($data['warehouse'])) {
            $product_warehouse = new ProductWarehouse();
            $warehouse = WareHouse::find($data['warehouse']);
            $product_warehouse->product_id = $product->id;
            $product_warehouse->product_qty = $data['quantity'];
            $product_warehouse->warehouse_id = $data['warehouse'];
            $product_warehouse->warehouse_type = $warehouse->type;
            $product_warehouse->save();
        }

        //incomingstocks, qty updated here
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = $product->id;
        $incomingStock->quantity_added = $data['quantity'];
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = $authUser->id;
        $incomingStock->status = 'true';
        $incomingStock->save();

        //Purchase
        $purchase = new Purchase();
        $purchase_code = 'kpa-' . date("Ymd") . '-' . date("his");
        $purchase->purchase_code = $purchase_code;

        $purchase->product_id = $product->id;
        $purchase->product_qty_purchased = $data['quantity'];
        $purchase->incoming_stock_id = $incomingStock->id;

        $purchase->product_purchase_price = $data['purchase_price']; //per unit
        $purchase->amount_due = $data['quantity'] * $data['purchase_price'];
        $purchase->amount_paid = $data['quantity'] * $data['purchase_price']; //u cant owe as d admin

        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system';

        $purchase->created_by = $authUser->id;
        $purchase->status = 'received';
        $purchase->save();

        $product->update(['purchase_id' => $purchase->id]);

        return back()->with('success', 'Product Created Successfully');
    }

    //allProducts
    public function allProducts()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $products = Product::whereNull('combo_product_ids')->orderBy('id', 'DESC')->get();

        return view('pages.products.allProducts', compact('authUser', 'user_role', 'products'));
    }

    //singleProduct
    public function singleProduct($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $product = Product::where('unique_key', $unique_key)->first();
        if (!isset($product)) {
            abort(404);
        }

        //$currency_symbol = substr($product->country_id, strrpos($product->country_id, '-') + 1);
        $generalSetting = GeneralSetting::where('id', '>', 0)->first();
        $currency_symbol = $generalSetting->country->symbol;
        $features = unserialize($product->features) == [null] ? '' : unserialize($product->features);

        //stock_available
        $stock_available = $product->stock_available();

        $warehouses = $product->warehouses;

        return view('pages.products.singleProduct', compact('authUser', 'user_role', 'product', 'currency_symbol', 'features', 'stock_available', 'warehouses'));
    }

    //editProduct
    public function editProduct($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $product = Product::where('unique_key', $unique_key)->first();
        if (!isset($product)) {
            abort(404);
        }

        $product_in_warehouse = ProductWarehouse::where('product_id', $product->id);

        $currency_nationality = $product->country->name;
        $currency_symbol = $product->country->symbol;

        $countries = Country::all();
        $features = unserialize($product->features) == [null] ? '' : unserialize($product->features);

        //stock_available
        $stock_available = $product->stock_available();

        $categories = Category::all();
        $warehouses = Warehouse::all();

        $agents = User::where('type', 'agent')->get();

        return view('pages.products.editProduct', compact(
            'authUser',
            'user_role',
            'product',
            'product_in_warehouse',
            'currency_symbol',
            'features',
            'countries',
            'currency_nationality',
            'stock_available',
            'categories',
            'warehouses',
            'agents'
        ));
    }

    public function editProductPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $product = Product::where('unique_key', $unique_key)->first();
        if (!isset($product)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|numeric',
            'category' => 'required',
            // 'color' => 'nullable|string',
            // 'size' => 'nullable|string',
            'currency' => 'required',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric|gt:purchase_price',
            'code' => 'nullable|string',
            // 'features' => 'nullable|array',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp',
        ]);

        $data = $request->all();

        $product->name = $data['name'];
        // $product->quantity = $data['quantity'];
        $product->category_id = $data['category'];
        $product->color = !empty($data['color']) ? $data['color'] : null;
        $product->size = !empty($data['size']) ? $data['size'] : null;
        $product->country_id = $data['currency'];
        $product->purchase_price = $data['purchase_price'];
        $product->sale_price = $data['sale_price'];
        // $product->code = !empty($data['code']) ? $data['code'] : null;

        $product->features = !empty($data['features']) ? serialize($data['features']) : null;

        // $product->warehouse_id =  !empty($data['warehouse_id']) ? $data['warehouse_id'] : null;
        $product->created_by = $authUser->id;
        $product->status = 'true';

        //image
        if ($request->image) {
            $oldImage = $product->image; //1.jpg
            if (Storage::disk('uploads')->exists('products/' . $oldImage)) {
                Storage::disk('uploads')->delete('products/' . $oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time() . '.' . $request->image->extension();
            //store products in folder
            $request->image->storeAs('products', $imageName, 'uploads');
            $product->image = $imageName;
        }

        $product->save();

        if (!empty($data['quantity']) && $data['quantity'] != 0) {
            //incomingStock
            if ($data['quantity'] > 0) {
                //incomingstocks
                $incomingStock = new IncomingStock();
                $incomingStock->product_id = $product->id;
                $incomingStock->quantity_added = $data['quantity'];
                $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
                $incomingStock->created_by = $authUser->id;
                $incomingStock->status = 'true';
                $incomingStock->save();

                //Purchase
                $purchase = new Purchase();
                $purchase_code = 'kpa-' . date("Ymd") . '-' . date("his");
                $purchase->purchase_code = $purchase_code;

                $purchase->product_id = $product->id;
                $purchase->product_qty_purchased = $data['quantity'];
                $purchase->incoming_stock_id = $incomingStock->id;

                $purchase->product_purchase_price = $data['purchase_price']; //per unit
                $purchase->amount_due = $data['quantity'] * $data['purchase_price'];
                $purchase->amount_paid = $data['quantity'] * $data['purchase_price']; //u cant owe as d admin

                $purchase->payment_type = 'cash';
                $purchase->note = 'Product added from system';

                $purchase->created_by = $authUser->id;
                $purchase->status = 'received';
                $purchase->save();

                $product->update(['purchase_id' => $purchase->id]);

                $productWarehouse = ProductWarehouse::where('product_id', $product->id)->first();
                if (isset($productWarehouse)) {
                    $qty = $productWarehouse->product_qty + $data['quantity'];
                    $productWarehouse->update(['product_qty' => $qty]);
                }
            }

            //outgoingStock
            if ($data['quantity'] < 0) {

                //reduce purchase
                $purchases = Purchase::where('product_id', $product->id);
                $line_items = $purchases->orderBy('id', 'DESC')->get(['id', 'product_qty_purchased', 'incoming_stock_id']);

                //$quantity_removed = abs($data['quantity']);
                $quantity_removed = abs($data['quantity']);

                //loop through each $line_items
                $bucket_sum = 0;
                $result = [];

                //loop array until it stops at a particular pt
                foreach ($line_items as $key => $row) {
                    $result[$key] = $row;
                    $bucket_sum += $row->product_qty_purchased;
                    if ($bucket_sum >= $quantity_removed) {
                        // $bucket_sum = 0;
                        ++$key;
                        break;
                    }
                }
                //return $result; //array so far

                //extract id columns
                $purchase_column_ids = array_column($result, 'id');
                $incoming_stock_column_ids = array_column($result, 'incoming_stock_id');

                if ($bucket_sum == $quantity_removed) {

                    //purchase side
                    Purchase::whereIn('id', $purchase_column_ids)->update([
                        'product_qty_purchased' => 0,
                        'product_purchase_price' => $data['purchase_price'],
                        'amount_due' => 0,
                        'amount_paid' => 0,
                    ]);
                    //IncomingStock side
                    IncomingStock::whereIn('id', $incoming_stock_column_ids)->update([
                        'quantity_added' => 0,
                    ]);
                } else {

                    //purchase side
                    $result_except_last = array_slice($purchase_column_ids, 0, count($purchase_column_ids) - 1, true); //array except last-item
                    $result_only_last = collect(end($purchase_column_ids))[0]; //array only last-item

                    $purchases_result_except_last = Purchase::whereIn('id', $result_except_last);
                    $sum1 = $purchases_result_except_last->sum('product_qty_purchased');

                    $purchases_only_last = Purchase::where('id', $result_only_last)->first();

                    //some calcs
                    $diff1 = $quantity_removed - $sum1;
                    $quantity_remaining = $purchases_only_last->product_qty_purchased - $diff1;
                    Purchase::where('id', $result_only_last)->update([
                        'product_qty_purchased' => $quantity_remaining,
                        'product_purchase_price' => $data['purchase_price'],
                        'amount_due' => $quantity_remaining * $data['purchase_price'],
                        'amount_paid' => $quantity_remaining * $data['purchase_price'],
                    ]);

                    $purchases_result_except_last->update([
                        'product_qty_purchased' => 0,
                        'product_purchase_price' => $data['purchase_price'],
                        'amount_due' => 0,
                        'amount_paid' => 0,
                    ]);

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    //incomingStock side
                    $incomingStock_except_last = array_slice($incoming_stock_column_ids, 0, count($incoming_stock_column_ids) - 1, true); //array except last-item
                    $incomingStock_only_last = collect(end($incoming_stock_column_ids))[0]; //array only last-item

                    $incomingStocks_result_except_last = IncomingStock::whereIn('id', $incomingStock_except_last);


                    $incomingStocks_only_last = IncomingStock::where('id', $incomingStock_only_last)->first();

                    //some calcs
                    // $sum2 = $incomingStocks_result_except_last->sum('quantity_added');
                    // $diff2 = $quantity_removed - $sum2;

                    // $quantity_remaining = $incomingStocks_only_last->quantity_added - $diff2;
                    IncomingStock::where('id', $incomingStock_only_last)->update([
                        'quantity_added' => $quantity_remaining,
                    ]);
                    $incomingStocks_result_except_last->update([
                        'quantity_added' => 0,
                    ]);
                }

                $productWarehouse = ProductWarehouse::where('product_id', $product->id)->first();
                if (isset($productWarehouse)) {
                    $qty = $productWarehouse->product_qty - $quantity_removed;
                    $productWarehouse->update(['product_qty' => $qty]);
                }
            }
        }

        //warehouse
        if (!empty($data['warehouse'])) {
            //check if initial warehouse existed
            $warehouse = WareHouse::find($data['warehouse']);
            if (!in_array($product->id, $product->warehouses->pluck('id')->toArray())) {
                $product_warehouse = new ProductWarehouse();
                $product_warehouse->product_id = $product->id;
                $product_warehouse->product_qty = $product->stock_available();
                $product_warehouse->warehouse_id = $data['warehouse'];
                $product_warehouse->warehouse_type = $warehouse->type;
                $product_warehouse->save();
            }
        }

        return back()->with('success', 'Product Updated Successfully');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        //
    }

    public function deleteProduct($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $product = Product::where(['unique_key' => $unique_key]);
        if (!$product->exists()) {
            abort(404);
        }

        $product = $product->first();
        $oldImage = $product->image;
        if (Storage::disk('public')->exists('products/' . $oldImage)) {
            Storage::disk('public')->delete('products/' . $oldImage);
        }

        $product->purchases()->delete();
        $product->sales()->delete();
        $product->incomingStocks()->delete();
        $product->outgoingStocks()->delete();
        ProductWarehouse::where('product_id', $product->id)->delete();

        $product->delete();

        return back()->with('success', 'Product Removed Successfully');
    }
}
