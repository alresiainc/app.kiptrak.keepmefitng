<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

use App\Models\Permission;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        //----Task Manager-22----------------------
        $permission = new Permission();
        $permission->name = 'Task Manager Menu';
        $permission->slug = Str::slug('Task Manager Menu');
        $permission->created_by = 1;
        $permission->save();

        //----------Task Manager---Id-22--------------------
        $permission = new Permission();
        $permission->name = 'View Project List';
        $permission->slug = Str::slug('View Project List');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Project';
        $permission->slug = Str::slug('Create Project');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Project';
        $permission->slug = Str::slug('Edit Project');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Delete Project';
        $permission->slug = Str::slug('Delete Project');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'View Task List';
        $permission->slug = Str::slug('View Task List');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Create Task';
        $permission->slug = Str::slug('Create Task');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Edit Task';
        $permission->slug = Str::slug('Edit Task');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'Delete Task';
        $permission->slug = Str::slug('Delete Task');
        $permission->parent_id = 22;
        $permission->created_by = 1;
        $permission->save();
    }

    public function addOrderbumpToForm(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'orderbump_product' => 'required',
        ]);

        $data = $request->all();
        $formHolder = FormHolder::where('unique_key', $data['form_unique_key'])->first();
        if (!isset($formHolder)) {
            abort(404);
        }

        if (!empty($data['orderbump_subheading'])) {
            $orderbump_subheading = serialize(array_filter($data['orderbump_subheading'], fn($value) => !is_null($value) && $value !== ''));
        } else {
            $orderbump_subheading = serialize(['It\'s an Amazing Offer']);
        }

        $product = Product::find($data['orderbump_product']);
        
        //orderbump
        $orderbump = new OrderBump();
        $orderbump->orderbump_heading = !empty($data['orderbump_heading']) ? $data['orderbump_heading'] : 'Would You Like to Add this Package to your Order';
        $orderbump->orderbump_subheading = $orderbump_subheading;
        $orderbump->product_id = $data['orderbump_product'];
        $orderbump->order_id = isset($formHolder->order) ? $formHolder->order->id : null;
        $orderbump->product_expected_quantity_to_be_sold = 1;
        $orderbump->product_expected_amount = 0;
        $orderbump->product_actual_selling_price = $product->sale_price;
        $orderbump->product_assumed_selling_price = $product->sale_price + 500;
        // $outgoingStock->created_by = $authUser->id;
        $orderbump->status = 'true';
        $orderbump->save();

        $product = Product::where('id', $data['orderbump_product'])->first();
        
        //outgoing stock
        // $outgoingStock = new OutgoingStock();
        // $outgoingStock->product_id = $data['orderbump_product'];
        // $outgoingStock->order_id = isset($formHolder->order_id) ? $formHolder->order->id : null;
        // $outgoingStock->quantity_removed = 1;
        // $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
        // $outgoingStock->reason_removed = 'as_orderbump'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
        // $outgoingStock->quantity_returned = 0; //by default
        // $outgoingStock->created_by = $authUser->id;
        // $outgoingStock->status = 'true';
        // $outgoingStock->save();

        //$package_bundle = [];
        // Create a new package array for each product ID
        $package_bundles = [
            'product_id'=>$data['orderbump_product'],
            'quantity_removed'=>1,
            'amount_accrued'=>$product->sale_price,
            'customer_acceptance_status'=>null,
            'reason_removed'=>'as_orderbump',
            'quantity_returned'=>0,
            'reason_returned'=>null,
            'isCombo'=>isset($product->combo_product_ids) ? 'true' : null,
        ];
        // $package_bundle[] = $package_bundles;
        $orderPackageBundle = $formHolder->order->outgoingStock->package_bundle;
        array_push($orderPackageBundle,$package_bundles);

        //create new OutgoingStock
        $outgoingStock = new OutgoingStock();
        $outgoingStock->order_id = isset($formHolder->order_id) ? $formHolder->order->id : null;
        $outgoingStock->package_bundle = $orderPackageBundle;
        $outgoingStock->created_by = $authUser->id;
        $outgoingStock->status = 'true';
        $outgoingStock->save();

        //update formHolder
        $formHolder->update(['orderbump_id'=>$orderbump->id]);

        return back()->with('success', 'Order bump Added Successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCkeditor()
    {
        return view('ckeditor');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCkeditorPost(Request $request): JsonResponse
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('media'), $fileName);
            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateExistingArrayBasedOnValuesFromAnother($id)
    {
        $ids = ['1', '2', '31', '41', '15'];
        $package_bundle_1 = [];
        foreach ($ids as $id) {
            // Create a new package array for each product ID
            $package_bundles = [
                'product_id'=>$id,
                'quantity_removed'=>1,
                'amount_accrued'=>100,
                'customer_acceptance_status'=>'accepted',
                'reason_removed'=>'as_order_firstphase',
            ];
            $package_bundle_1[] = $package_bundles;
        }
        //return $package_bundle_1;
        //now update each row package_bundle
        $outgoingStockPackageBundle = OutgoingStock::where('id', '5')->first()->package_bundle;

        foreach ($outgoingStockPackageBundle as &$package_bundle) {
            // Find the corresponding package_bundle in $package_bundle_1 based on product_id
            $matching_package = collect($package_bundle_1)->firstWhere('product_id', $package_bundle['product_id']);
        
            // If a matching package is found, update the row in $outgoingStockPackageBundle
            if ($matching_package) {
                // Merge the matching keys and values from $matching_package into $package_bundle
                $package_bundle = array_merge($package_bundle, array_intersect_key($matching_package, $package_bundle));
            }
        }
        
        // Now $outgoingStockPackageBundle has the updated data
        return $outgoingStockPackageBundle;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
