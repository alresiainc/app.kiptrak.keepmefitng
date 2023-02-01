<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;

class CategoryController extends Controller
{
    public function allCategory()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $categories = Category::orderBy('id', 'DESC')->get();
        return view('pages.category.allCategory', compact('authUser', 'user_role', 'categories'));
    }

    public function addCategoryPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'category' => 'required',
        ]);
        
        $authUser = auth()->user();
        $data = $request->all();

        $category = new Category();
        $category->name = $data['category'];
        $category->created_by = $authUser->id;
        $category->status = 'true';
        $category->save();

        return back()->with('success', 'Category Added Successfully');
    }

    public function singleCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        

    }

    public function editCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
    }

    public function editCategoryPost(Request $request, $unique_key="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'category_name' => 'required',
        ]);
        
        $data = $request->all();

        $category = Category::where('id', $data['category_id'])->first();
        $category->name = $data['category_name'];
        $category->created_by = $authUser->id;
        $category->status = 'true';
        $category->save();

        return back()->with('success', 'Category Updated Successfully');
    }

    public function deleteCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $category = Category::where(['unique_key'=>$unique_key]);
        if(!$category->exists()){
            abort(404);
        }
        $category = $category->first();
        $category->delete();
    
        return back()->with('success', 'Category Renoved Successfully');
    }

    public function productsByCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $category = Category::where('unique_key', $unique_key)->first();
        if(!isset($category)){
            abort(404);
        }
        $products = $category->products;
        return view('pages.category.productsByCategory', compact('authUser', 'user_role', 'category', 'products'));
    }

    public function salesByCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $category = Category::where('unique_key', $unique_key)->first();
        if(!isset($category)){
            abort(404);
        }
        $products = $category->products->pluck('id'); //[1,3,4]
        $sales = Sale::whereIn('sales.product_id', $products)->get();
        return view('pages.category.salesByCategory', compact('authUser', 'user_role', 'category', 'sales'));
    }

    public function purchasesByCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $category = Category::where('unique_key', $unique_key)->first();
        if(!isset($category)){
            abort(404);
        }
        $products = $category->products->pluck('id'); //[1,3,4]
        $purchases = Purchase::whereIn('purchases.product_id', $products)->get();
        return view('pages.category.purchasesByCategory', compact('authUser', 'user_role', 'category', 'purchases'));
    }

    //customers by category of products bought
    public function customersByCategory($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $category = Category::where('unique_key', $unique_key)->first();
        if(!isset($category)){
            abort(404);
        }
        $products = $category->products->pluck('id'); //[1,3,4]
        $sale_customers = Sale::whereIn('sales.product_id', $products)->select('customer_id')->groupBy('customer_id')->get();
        $persons = []; $customers = [];
        foreach ($sale_customers as $key => $val) {
            $customer = DB::table('customers')->find($val->customer_id);
            $customers[] = $customer;
        }
        //return $customers;
        return view('pages.category.customersByCategory', compact('authUser', 'user_role', 'category', 'customers'));
    }

    public function ajaxSendCustomerMail(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
    }

    public function createProductCategoryAjax(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $authUser = auth()->user();
        $data = $request->all();
        
        $category = new Category();
        $category->name = $data['category_name'];
       
        $category->created_by = $authUser->id;
        $category->status = 'true';
        $category->save();
        
        //store in array
        $data['category'] = $category;

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        //
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        //
    }
}
