<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Country;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function allSupplier()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $suppliers = Supplier::orderBy('id', 'DESC')->get();
        return view('pages.suppliers.allSupplier', compact('authUser', 'user_role', 'suppliers'));
    }

public function addSupplier()
{
    $authUser = auth()->user();
    $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    $countries = Country::all();
    return view('pages.suppliers.addSupplier', compact('authUser', 'user_role', 'countries'));
}

public function addSupplierPost(Request $request)
{
    $authUser = auth()->user();
    $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    $request->validate([
        'company_name' => 'required|string',
        'supplier_name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'nullable',
        'company_logo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    ]);

    $data = $request->all();

    $supplier = new Supplier();
    $supplier->company_name = $data['company_name'];
    $supplier->supplier_name = $data['supplier_name'];
    $supplier->email = $data['email'];
    $supplier->phone_number = $data['phone_number'];
    $supplier->created_by = $authUser->id;
    $supplier->status = 'true';
    
    if ($request->company_logo) {
        //image
        $imageName = time().'.'.$request->company_logo->extension();
        //store products in folder
        $request->company_logo->storeAs('supplier', $imageName, 'public');
        $supplier->company_logo = $imageName;
    }

    $supplier->save();
    
    return back()->with('success', 'Supplier Added Successfully');

    
}

public function editSupplier($unique_key)
{
    $authUser = auth()->user();
    $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    $supplier = Supplier::where('unique_key', $unique_key)->first();
    if(!isset($supplier)){
        abort(404);
    }

    $countries = Country::all();

    return view('pages.suppliers.editSupplier', compact('authUser', 'user_role', 'supplier', 'countries'));
}

public function editSupplierPost(Request $request, $unique_key)
{
    $authUser = auth()->user();
    $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    $supplier = Supplier::where('unique_key', $unique_key)->first();
    if(!isset($supplier)){
        abort(404);
    }
    $request->validate([
        'company_name' => 'required|string',
        'supplier_name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'nullable',
        'company_logo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
    ]);

    $data = $request->all();

    $supplier->company_name = $data['company_name'];
    $supplier->supplier_name = $data['supplier_name'];
    $supplier->email = $data['email'];
    $supplier->phone_number = $data['phone_number'];
    $supplier->status = 'true';

    //profile_picture
    if ($request->company_logo) {
        $oldImage = $supplier->company_logo; //1.jpg
        if(Storage::disk('public')->exists('supplier/'.$oldImage)){
            Storage::disk('public')->delete('supplier/'.$oldImage);
            /*
                Delete Multiple files this way
                Storage::delete(['upload/test.png', 'upload/test2.png']);
            */
        }
        $imageName = time().'.'.$request->company_logo->extension();
        //store products in folder
        $request->company_logo->storeAs('supplier', $imageName, 'public');
        $supplier->company_logo = $imageName;
    }

    $supplier->save();
    
    return back()->with('success', 'Supplier Updated Successfully');
}

public function singleSupplier($unique_key)
{
    $authUser = auth()->user();
    $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

    $supplier = Supplier::where('unique_key', $unique_key)->first();
    if(!isset($supplier)){
        abort(404);
    }
    
    return view('pages.suppliers.singleSupplier', compact('authUser', 'user_role', 'supplier'));
}


    
    public function addSupplierAjax(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $request->validate([
            'company_name' => 'required|string',
            'supplier_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable',
            'company_logo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
        ]);

        $data = $request->all();

        $supplier = new Supplier();
        $supplier->company_name = $data['company_name'];
        $supplier->supplier_name = $data['supplier_name'];
        $supplier->email = $data['email'];
        $supplier->phone_number = $data['phone_number'];
        $supplier->created_by = $authUser->id;
        $supplier->status = 'true';
        
        if ($request->company_logo) {
            //image
            $imageName = time().'.'.$request->company_logo->extension();
            //store products in folder
            $request->company_logo->storeAs('supplier', $imageName, 'public');
            $supplier->company_logo = $imageName;
        }

        $supplier->save();
        
        $data['supplier'] = $supplier;

        return response()->json([
            'status'=>true,
            'data'=>$data
        ]);
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
