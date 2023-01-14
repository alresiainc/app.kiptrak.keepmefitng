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
            'sale_price' => 'required|numeric',
            'code' => 'nullable|string|unique:products',
            // 'features' => 'nullable|array',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
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
        $product->created_by = 1;
        $product->status = 'true';
        
        //image
        $imageName = time().'.'.$request->image->extension();
        //store products in folder
        $request->image->storeAs('products', $imageName, 'public');
        $product->image = $imageName;
        $product->save();

        //warehouse
        if (!empty($data['warehouse_id'])) {
            $product_warehouse = new ProductWarehouse();
            $warehouse = WareHouse::find($data['warehouse_id']);
            $product_warehouse->product_id = $product->id;
            $product_warehouse->warehouse_id = $data['warehouse_id'];
            $product_warehouse->warehouse_type = $warehouse->type;
            $product_warehouse->save();
        }
        
        //incomingstocks
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = $product->id;
        $incomingStock->quantity_added = $data['quantity'];
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = '1';
        $incomingStock->status = 'true';
        $incomingStock->save();
        
        //Purchase
        $purchase = new Purchase();
        $purchase_code = 'kpa-' . date("Ymd") . '-'. date("his");
        $purchase->purchase_code = $purchase_code;
        
        $purchase->product_id = $product->id;
        $purchase->product_qty_purchased = $data['quantity'];
        $purchase->incoming_stock_id = $incomingStock->id;

        $purchase->product_purchase_price = $data['purchase_price']; //per unit
        $purchase->amount_due = $data['quantity'] * $data['purchase_price'];
        $purchase->amount_paid = $data['quantity'] * $data['purchase_price']; //u cant owe as d admin

        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system';

        $purchase->created_by = 1;
        $purchase->status = 'received';
        $purchase->save();

        $product->update(['purchase_id'=>$purchase->id]);

        return back()->with('success', 'Product Created Successfully');

    }

    //allProducts
    public function allProducts()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::orderBy('id','DESC')->get();
        return view('pages.products.allProducts', compact('authUser', 'user_role', 'products'));
    }

    //singleProduct
    public function singleProduct($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
            abort(404);
        }
        
        $currency_symbol = substr($product->country_id, strrpos($product->country_id, '-') + 1);
        $features = unserialize($product->features) == [null] ? '' : unserialize($product->features);

        //stock_available
        $stock_available = $product->stock_available();
        
        return view('pages.products.singleProduct', compact('authUser', 'user_role', 'product', 'currency_symbol', 'features', 'stock_available'));
    }

    //editProduct
    public function editProduct($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
            abort(404);
        }

        $currency_nationality = $product->country->name;
        $currency_symbol = $product->country->symbol;

        $countries = Country::all();
        $features = unserialize($product->features) == [null] ? '' : unserialize($product->features);

        //stock_available
        $stock_available = $product->stock_available();

        $categories = Category::all();
        $warehouses = Warehouse::all();

        $agents = User::where('type', 'agent')->get();

        return view('pages.products.editProduct', compact('authUser', 'user_role', 'product', 'currency_symbol', 'features',
        'countries', 'currency_nationality', 'stock_available', 'categories', 'warehouses', 'agents'));
    }

    public function editProductPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
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
            'sale_price' => 'required|numeric',
            'code' => 'nullable|string',
            // 'features' => 'nullable|array',
            //'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
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
        $product->created_by = '1';
        $product->status = 'true';
        
        //image
        if ($request->image) {
            $oldImage = $product->image; //1.jpg
            if(Storage::disk('public')->exists('products/'.$oldImage)){
                Storage::disk('public')->delete('products/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->image->extension();
            //store products in folder
            $request->image->storeAs('products', $imageName, 'public');
            $product->image = $imageName;
        }
        
        //return $oldImage;
        //Storage::disk('public')->delete($oldImagePath);
        $product->save();

        //warehouse
        if (!empty($data['warehouse_id'])) {
            $product_warehouse = new ProductWarehouse();
            $warehouse = WareHouse::find($data['warehouse_id']);
            $product_warehouse->product_id = $product->id;
            $product_warehouse->warehouse_id = $data['warehouse_id'];
            $product_warehouse->warehouse_type = $warehouse->type;
            $product_warehouse->save();
        }

        if(!empty($data['quantity']) && $data['quantity'] !== 0)
        {
            //incomingStock
            if ($data['quantity'] > 0) {
                //incomingstocks
                $incomingStock = new IncomingStock();
                $incomingStock->product_id = $product->id;
                $incomingStock->quantity_added = $data['quantity'];
                $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
                $incomingStock->created_by = '1';
                $incomingStock->status = 'true';
                $incomingStock->save();
            }

            //outgoingStock
            if ($data['quantity'] < 0) {
                $outgoingStock = new OutgoingStock();
                $outgoingStock->product_id = $product->id;
                $outgoingStock->quantity_removed = abs($data['quantity']); //stay +ve
                $outgoingStock->reason_removed = 'as_administrative'; //as_order, as_expired, as_damaged, as_administrative
                $outgoingStock->quantity_returned = 0; //by default
                $outgoingStock->created_by = '1';
                $outgoingStock->status = 'true';
                $outgoingStock->save();
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
}
