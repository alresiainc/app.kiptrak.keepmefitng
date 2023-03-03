<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use App\Models\Category;
use App\Models\IncomingStock;
use App\Models\Purchase;

class ProductComboController extends Controller
{
    
    public function addCombo()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $products = Product::whereNull('combo_product_ids')->get();
        $categories = Category::all();

        return view('pages.products.addCombo', compact('authUser', 'user_role', 'products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addComboPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'name' => 'required|string',
            'short_description' => 'nullable|string',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'category' => 'required',
            'currency' => 'nullable',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'code' => 'nullable|string|unique:products',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp',
        ]);

        $data = $request->all();
        $product = new Product();
        $product->name = $data['name'];
        $product->combo_product_ids = serialize($data['idQty']); //["1-4","2-1"] productId & qty
        $product->short_description = $data['short_description'] ? $data['short_description'] : null;
        $product->discount_type = $data['discount_type'];
        $product->discount = $data['discount_value'];
        // $product->quantity = $data['quantity']; //handled by inComingStocks
        $product->category_id = $data['category'];
        $product->country_id = 1; //country_id
        $product->purchase_price = $data['total_purchase'];
        $product->sale_price = $data['total_after_discount'];
        $product->created_by = $authUser->id;
        $product->status = 'true';

        //image
        $imageName = time().'.'.$request->image->extension();
        //store products in folder
        $request->image->storeAs('products', $imageName, 'public');
        $product->image = $imageName;
        $product->save();

        //incomingstocks
        $incomingStock = new IncomingStock();
        $incomingStock->product_id = $product->id;
        $incomingStock->quantity_added = 1;
        $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
        $incomingStock->created_by = $authUser->id;
        $incomingStock->status = 'true';
        $incomingStock->save();

        //Purchase
        $purchase = new Purchase();
        $purchase_code = 'kpa-' . date("Ymd") . '-'. date("his");
        $purchase->purchase_code = $purchase_code;
        
        $purchase->product_id = $product->id;
        $purchase->product_qty_purchased = 1;
        $purchase->incoming_stock_id = $incomingStock->id;

        $purchase->product_purchase_price = $data['total_purchase']; //per unit
        $purchase->amount_due = $data['total_purchase'];
        $purchase->amount_paid = $data['total_purchase']; //u cant owe as d admin

        $purchase->payment_type = 'cash';
        $purchase->note = 'Product added from system as combo';

        $purchase->created_by = $authUser->id;
        $purchase->status = 'received';
        $purchase->save();

        $product->update(['purchase_id'=>$purchase->id]);

        return back()->with('success', 'Combo Product Created Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allCombo(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $products = Product::where('combo_product_ids', '!=', null)->orderBy('id','DESC')->get();
        
        return view('pages.products.allCombo', compact('authUser', 'user_role', 'products'));
    }

    //singleProduct
    public function singleCombo($unique_key)
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
        
        return view('pages.products.singleCombo', compact('authUser', 'user_role', 'product', 'currency_symbol', 'features', 'stock_available'));
    }

    public function editCombo($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
            abort(404);
        }

        $products = Product::all();
        $categories = Category::all();

        return view('pages.products.editCombo', compact('authUser', 'user_role', 'product', 'products', 'categories'));
    }

    public function editComboPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $product = Product::where('unique_key', $unique_key)->first();
        if(!isset($product)){
            abort(404);
        }
        
        $request->validate([
            'name' => 'required|string',
            'short_description' => 'nullable|string',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'category' => 'required',
            'currency' => 'nullable',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'code' => 'nullable|string|unique:products',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp',
        ]);

        $data = $request->all();

        $product->name = $data['name'];
        $product->combo_product_ids = serialize($data['idQty']); //["1-4","2-1"] productId & qty
        $product->short_description = $data['short_description'] ? $data['short_description'] : null;
        $product->discount_type = $data['discount_type'];
        $product->discount = $data['discount_value'];
        // $product->quantity = $data['quantity']; //handled by inComingStocks
        $product->category_id = $data['category'];
        $product->country_id = 1; //country_id
        $product->purchase_price = $data['total_purchase'];
        $product->sale_price = $data['total_after_discount'];
        $product->created_by = $authUser->id;
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

        $product->save();

        //incomingstocks
        $incomingStock = IncomingStock::where('product_id', $product->id)->first();

        //Purchase
        $purchase = Purchase::where('incoming_stock_id', $incomingStock->id)->first();
        
        $purchase->product_purchase_price = $data['total_purchase']; //per unit
        $purchase->amount_due = $data['total_purchase'];
        $purchase->amount_paid = $data['total_purchase']; //u cant owe as d admin

        $purchase->save();

        return back()->with('success', 'Combo Product Updated Successfully');
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
