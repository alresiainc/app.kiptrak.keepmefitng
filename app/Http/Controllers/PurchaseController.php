<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Models\Country;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\IncomingStock;
use App\Models\Expense;

class PurchaseController extends Controller
{
    
    public function allPurchase()
    {
        $purchases = Purchase::where('parent_id', null)->get();
        
        return view('pages.purchases.allPurchase', compact('purchases'));
    }

    
    public function addPurchase()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        $purchase_code = time();
        $purchase_last = DB::table('purchases')->latest('id')->first(); //will later be auth->id
        if (!isset($purchase_last)) {
            $purchase_code = $purchase_code;
        } else {
            $purchase_code = $purchase_code.''.$purchase_last->id+1;
        }
        
        return view('pages.purchases.addPurchase', compact('products', 'suppliers', 'purchase_code'));
    }

    public function addPurchasePost(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required|string',
            'supplier' => 'required|string',
            // 'purchase_date' => 'required|string',
            'product' => 'required|string',
            'payment_type' => 'required|string',
            'purchase_status' => 'required|string',
            'note' => 'nullable|string',
            'attached_document' => 'nullable|mimes:jpg, jpeg, png, pdf, csv, docx, xlsx, txt, gif, svg, webp|max:2048',
        ]);

        $data = $request->all();

        //duplicate. break fxn here
        $dup = [];
        foreach($data['product_id'] as $key => $id){
            if(!empty($id)){
                
                if(!in_array($id, $dup)){
                    $dup[] = $id;
                
                } else {
                    return back()->with('duplicate_error', 'Duplicate Product Detected. You can increase quantity accordingly');
                }
            }
            
        }

        $imageName = '';
        if ($request->attached_document) {
            //image
            $imageName = time().'.'.$request->attached_document->extension();
            //store products in folder
            $request->attached_document->storeAs('purchase', $imageName, 'public');
        }

        //normal looping
        foreach ($data['product_id'] as $key => $id) {
            if(!empty($id)){

                $parent_purchase = Purchase::where('purchase_code', $data['purchase_code']); //for grouping purchases

                //update product purchase price currently
                Product::where(['id'=>$id])->update(['purchase_price'=>$data['unit_price'][$key]]);

                //update product stock
                $incomingStock = new IncomingStock();
                $incomingStock->product_id = $id;
                $incomingStock->quantity_added = $data['product_qty'][$key];
                $incomingStock->reason_added = 'as_purchase'; //as_new_product, as_returned_product, as_purchase
                $incomingStock->created_by = 1;
                $incomingStock->status = 'true';
                $incomingStock->save();

                //expense
                $data = $request->all();

                // $expense = new Expense();
                // $expense->expense_code = 'kpe-' . date("Ymd") . '-'. date("his");
                // $expense->amount = $data['product_qty'][$key] * $data['unit_price'][$key];
                // $expense->product_id = $id;
                // $expense->note = !empty($data['note']) ? $data['note'] : null;
                // $expense->created_by = 1;
                // $expense->status = 'true';
                // $expense->save();
                
                $purchase = new Purchase();
                $purchase->purchase_code = $data['purchase_code'];
                $purchase->parent_id = $parent_purchase->exists() ? $parent_purchase->first()->id : null;
                $purchase->supplier_id = $data['supplier'];
                // $purchase->purchase_date = $data['purchase_date'];

                $purchase->product_id = $id;
                $purchase->product_qty_purchased = $data['product_qty'][$key];
                $purchase->incoming_stock_id = $incomingStock->id;

                $purchase->product_purchase_price = $data['unit_price'][$key];
                $purchase->amount_due = $data['product_qty'][$key] * $data['unit_price'][$key];
                $purchase->amount_paid = $data['product_qty'][$key] * $data['unit_price'][$key]; //u cant owe as d admin

                $purchase->payment_type = $data['payment_type'];
                $purchase->note = !empty($data['note']) ? $data['note'] : null;

                $purchase->attached_document = $imageName == '' ? null : $imageName;

                $purchase->created_by = 1;
                $purchase->status = $data['purchase_status'];

                $purchase->save();

                
            }
        }

        return back()->with('success', 'Purchase Saved Successfully');

        
    }
    
    public function singlePurchase($unique_key)
    {
        return '123';
    }

    public function editPurchase($unique_key)
    {
        $purchase = Purchase::where('unique_key', $unique_key);
        $purchase_code = $purchase->first()->purchase_code;
        if(!$purchase->exists()){
            abort(404);
        }
        $products = Product::all();
        $suppliers = Supplier::all();
        $purchases = Purchase::where('purchase_code', $purchase_code)->get();
        $purchase = $purchase->first();
        
        return view('pages.purchases.editPurchase', compact('products', 'suppliers', 'purchase', 'purchases'));
    }

    public function editPurchasePost(Request $request, $unique_key)
    {
        $purchase = Purchase::where('unique_key', $unique_key);
        if(!$purchase->exists()){
            abort(404);
        }
        $purchase = $purchase->first();
        $request->validate([
            'purchase_code' => 'required|string',
            'supplier' => 'required|string',
            // 'purchase_date' => 'required|string',
            'product' => 'nullable|string',
            'payment_type' => 'required|string',
            'purchase_status' => 'required|string',
            'note' => 'nullable|string',
            'attached_document' => 'nullable|mimes:jpg, jpeg, png, pdf, csv, docx, xlsx, txt, gif, svg, webp|max:2048',
        ]);

        $data = $request->all();

        //file
        $imageName = '';
        if ($request->attached_document) {
            $oldImage = $purchase->attached_document; //1.jpg
            if(Storage::disk('public')->exists('purchase/'.$oldImage)){
                Storage::disk('public')->delete('purchase/'.$oldImage);
                /*
                    Delete Multiple files this way
                    Storage::delete(['upload/test.png', 'upload/test2.png']);
                */
            }
            $imageName = time().'.'.$request->attached_document->extension();
            //store products in folder
            $request->attached_document->storeAs('purchase', $imageName, 'public');
            //$purchase->attached_document = $imageName;
        }
        //

        foreach ($data['product_id'] as $key => $id) {
            if(!empty($id)){
                
                $parent_purchase = Purchase::where('purchase_code', $data['purchase_code']);
                Product::where(['id'=>$id])->update(['price'=>$data['unit_price'][$key]]);

                $existing_purchase = $parent_purchase->where('product_id', $id);
                if ($existing_purchase->exists()) {
                    $existing_purchase->update([
                        'purchase_code' => $data['purchase_code'],
                        'supplier_id' => $data['supplier'],
                        // 'purchase_date' => $data['purchase_date'],
                        'product_id' => $id,
                        'product_qty_purchased' => $data['product_qty'][$key],
                        'amount_due' => $data['product_qty'][$key] * $data['unit_price'][$key],
                        'amount_paid' => 0,
                        'payment_type' => $data['payment_type'],
                        'note' => !empty($data['note']) ? $data['note'] : null,
                        'attached_document' => $imageName == '' ? null : $imageName,
                        'created_by' => 1,
                        'status' => $data['purchase_status'],
                    ]);
                    IncomingStock::where(['id'=>$data['incoming_stock_id'][$key]])->update([
                     'product_id' => $id,
                     'quantity_added' => $data['product_qty'][$key],
                     'reason_added' => 'as_new_product',
                     'created_by' => 1,
                     'status' => 'true'
                    ]);
                } else {

                    $imageName = '';
                    if ($request->attached_document) {
                        //image
                        $imageName = time().'.'.$request->attached_document->extension();
                        //store products in folder
                        $request->attached_document->storeAs('purchase', $imageName, 'public');
                    }

                    //update product stock
                    $incomingStock = new IncomingStock();
                    $incomingStock->product_id = $id;
                    $incomingStock->quantity_added = $data['product_qty'][$key];
                    $incomingStock->reason_added = 'as_new_product'; //as_new_product, as_returned_product, as_administrative
                    $incomingStock->created_by = 1;
                    $incomingStock->status = 'true';
                    $incomingStock->save();
                    
                    //incase of new added
                    $purchase = new Purchase();
                    $purchase->purchase_code = $data['purchase_code'];
                    $purchase->parent_id = $parent_purchase->exists() ? $parent_purchase->first()->id : null;
                    $purchase->supplier_id = $data['supplier'];
                    // $purchase->purchase_date = $data['purchase_date'];

                    $purchase->product_id = $id;
                    $purchase->product_qty_purchased = $data['product_qty'][$key];
                    $purchase->incoming_stock_id = $incomingStock->id;
                    $purchase->amount_due = $data['product_qty'][$key] * $data['unit_price'][$key];
                    $purchase->amount_paid = 0;

                    $purchase->payment_type = $data['payment_type'];
                    $purchase->note = !empty($data['note']) ? $data['note'] : null;

                    $purchase->attached_document = $imageName == '' ? null : $imageName;

                    $purchase->created_by = 1;
                    $purchase->status = $data['purchase_status'];

                    $purchase->save();

                    
                }
                

                
            }
        }

        return back()->with('success', 'Purchase Updated Successfully');
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
