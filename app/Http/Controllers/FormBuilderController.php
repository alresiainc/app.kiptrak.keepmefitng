<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Events\TestEvent;
use App\Notifications\TestNofication;
use App\Notifications\NewOrder;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Exception;

use App\Models\FormHolder;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OutgoingStock;
use App\Models\IncomingStock;
use App\Models\OrderBump;
use App\Models\UpSell;
use App\Models\Customer;
use App\Models\CartAbandon;
use App\Models\UpsellSetting;
use App\Models\GeneralSetting;
use App\Models\SoundNotification;


class FormBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formBuilder()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        return view('pages.formBuilder');
    }

    public function newFormBuilder()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $products = Product::all();
        $staffs = User::where('type','staff')->get();
        // $products_jqueryArray = \json_encode($products);

        $string = 'kpf-' . date("his");
        $randomStrings = FormHolder::where('slug', 'like', $string.'%')->pluck('slug');

        do {
            $randomString = $string.rand(100000, 999999);
        } while ($randomStrings->contains($randomString));
    
        $form_code = $randomString;
        
        $package_select = '<select class="form-control select-checkbox" name="packages[]" style="width:100%">
        <option value="">--Select Option--</option>';
        foreach($products as $product):
            $package_select .= '<option value="'. $product->id.'"> '.$product->name.'</option>' ;
        endforeach;
        $package_select .='</select>';

        return view('pages.newFormBuilder', compact('authUser', 'user_role', 'products', 'package_select', 'form_code'));
    }

    //saving built form
    public function newFormBuilderPost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'name' => 'required|string|unique:form_holders',
            'packages' => 'required',
            'form_names' => 'required',
            'form_labels' => 'required',
        ]);

        $data = $request->all();
        //neccessary fields contain at least one value
        if ((count(array_filter($data['packages'])) == 0) || (count(array_filter($data['form_names'])) == 0) || (count(array_filter($data['form_labels'])) == 0)) {
            return back()->with('field_error', 'Missing Fields');
        }

        $form_names_expected = array("First Name", "Last Name", "Phone Number", "Whatsapp Phone Number", "Active Email", "State", "City", "Address", "Product Package");
        //check form-names & labels
        $form_names = Arr::flatten($data['form_names']);
        foreach ($form_names_expected as $form_name) {
            if (!in_array($form_name, $form_names)) { return back()->with('field_error', $form_name.' is Missing'); }

            //duplicates error
            if (count(array_keys($form_names, $form_name)) > 1) { return back()->with('field_error', $form_name.' Occurred Morethan Once'); }
        }

        //duplicate products selected error
        $packages = Arr::flatten($data['packages']);
        $selected_products = Product::whereIn('id', $data['packages'])->get();
        foreach ($selected_products as $package) {
            if (count(array_keys($packages, $package->id)) > 1) { return back()->with('field_error', 'Product: '.$package->name.' Was Selected Morethan Once'); }
        }
    
        //save FormHolder
        $formHolder = new FormHolder();
        $formHolder->name = $data['name'];
        $formHolder->slug = $request->form_code; //like form_code
        $formHolder->form_data = \serialize($request->except(['products', 'q', 'required', 'form_name_selected', '_token']));
        
        $formHolder->created_by = $authUser->id;
        $formHolder->status = 'true';
        $formHolder->save();

        //save Order
        $order = new Order();
        $order->form_holder_id = $formHolder->id;
        $order->source_type = 'form_holder_module';
        $order->status = 'new';
        $order->save();

        //outgoingStock, in place of orderProduct
        $product_ids = [];
        foreach ($data['packages'] as $package) {
            if (!empty($package)) {
                
                $product = Product::where('id', $package)->first();
                $product_ids[] = $product->id;
                $outgoingStock = new OutgoingStock();
                $outgoingStock->product_id = $product->id;
                $outgoingStock->order_id = $order->id;
                $outgoingStock->quantity_removed = 1;
                $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
                $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                $outgoingStock->quantity_returned = 0; //by default
                $outgoingStock->created_by = $authUser->id;
                $outgoingStock->status = 'true';
                $outgoingStock->save();
                
            }
            
        }

        //update formHolder
        $formHolder->update(['order_id'=>$order->id]);
        $order->update(['products'=>serialize($product_ids)]);

        return back()->with('success', 'Form Created Successfully');
        
    }

    public function allNewFormBuilders()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolds = FormHolder::orderBy('id', 'DESC')->get();
        $formHolders = [];
        foreach ($formHolds as $key => $formHolder) {
            $formHolder['form_data'] = \unserialize($formHolder->form_data);
            $formHolders[] = $formHolder;
        }
        //return $formHolders;

        $products = Product::where('status', 'true')->get();
        $upsellTemplates = UpsellSetting::all();
        $staffs = User::where('type','staff')->get();
        return view('pages.allFormBuilders', compact('authUser', 'user_role', 'formHolders', 'products', 'upsellTemplates', 'staffs'));
    }

    public function assignStaffToForm(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $form_id = $data['form_id'];
        $staff_id = $data['staff_id'];

        $formHolder = FormHolder::where('id',$form_id)->first();

        //check if form has entries
        if (count($formHolder->customers) > 0) {
            $orders = Order::where('form_holder_id', $formHolder->id)->update(['staff_assigned_id'=>$staff_id]);
        }
        
        //upd form
        $formHolder->update(['staff_assigned_id'=>$staff_id]);
        
         
        return back()->with('success', 'Staff Assigned Successfully');
    }

    //duplicateForm
    public function duplicateForm ($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            abort(404);
        }

        //form code
        $string = 'kpf-' . date("his");
        $randomStrings = FormHolder::where('slug', 'like', $string.'%')->pluck('slug');

        do {
            $randomString = $string.rand(100000, 999999);
        } while ($randomStrings->contains($randomString));
    
        $form_code = $randomString;

        //data to show/edit
        $formData = \unserialize($formHolder->form_data);
        
        $formContactInfo = [];
        $formPackage = [];
        $form_names = $formData['form_names'];
        $form_labels = $formData['form_labels'];
        $form_types = $formData['form_types'];
        $packages = $formData['packages']; //["1", "2"]

        $products = Product::all();

        foreach($packages as $key=>$package):
        $package_select_edit[] =
        '<div class="row w-100">
            <div class="col-sm-1 rem-on-display" onclick="$(this).closest(\'.row\').remove()">
                <button class="btn btn-sm btn-default" type="button"><span class="bi bi-x-lg"></span></button>
            </div>
            <div class="col-sm-11 d-flex align-items-center">
                <div class="mb-3 q-fc w-100">';
                $package_select_edit[] .=
                '<select class="select2 form-control border select-checkbox" name="packages[]" style="width:100%">';
                   if($this->productById($package) !== ""): $package_select_edit[] .= '<option value="'.$package.'" selected> '.$this->productById($package)->name.' </option>'; endif;
                    foreach($products as $product):
                        $package_select_edit[] .= '<option value="'. $product->id.'"> '.$product->name.'</option>';
                    endforeach;
                $package_select_edit[] .=
                '</select>
                <input type="hidden" name="former_packages[]" value="'.$package.'">
                </div>
            </div>
        </div>';
        endforeach;

        //return $package_select_edit;

        //for cloning
        $package_select = '<select class="form-control select-checkbox" name="packages[]" style="width:100%">
        <option value=""> --Select Product-- </option>';
        foreach($products as $product):
            $package_select .= '<option value="'. $product->id.'"> '.$product->name.'</option>' ;
        endforeach;
        $package_select .='</select>';

        //cos form_names are not determind by staff in form-building
        foreach ( $form_names as $key => $form_name ) {
            //$formContact[Str::slug($form_name)] = [ Str::slug($form_name), $form_labels[$key], $form_types[$key] ];
            $formContactInfo['form_name'] = $form_name;
            $formContactInfo['form_label'] = $form_labels[$key];
            $formContactInfo['form_type'] = $form_types[$key];
            $formContact[] = $formContactInfo;
        }
        // return $formContact;

        //extract products
        foreach ($formContact as $key => $formProduct) {
            if ($formProduct['form_name'] == 'Product Package') {
                $package_form_name = $formProduct['form_name'];
                $package_form_label = $formProduct['form_label'];
                $package_form_type = $formProduct['form_type'];
            }
        }
        
        // return $products;

        return view('pages.duplicateForm', compact('authUser', 'user_role', 'formHolder', 'products', 'package_select', 'form_code', 'formContact', 'packages', 'package_select_edit'));

    }

    public function duplicateFormPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder_former = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder_former)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|unique:form_holders',
            'packages' => 'required',
            'form_names' => 'required',
            'form_labels' => 'required',
        ]);

        $data = $request->all();
        //neccessary fields contain at least one value
        if ((count(array_filter($data['packages'])) == 0) || (count(array_filter($data['form_names'])) == 0) || (count(array_filter($data['form_labels'])) == 0)) {
            return back()->with('field_error', 'Missing Fields');
        }

        $form_names_expected = array("First Name", "Last Name", "Phone Number", "Whatsapp Phone Number", "Active Email", "State", "City", "Address", "Product Package");
        //check form-names & labels
        $form_names = Arr::flatten($data['form_names']);
        foreach ($form_names_expected as $form_name) {
            if (!in_array($form_name, $form_names)) { return back()->with('field_error', $form_name.' is Missing'); }

            //duplicates error
            if (count(array_keys($form_names, $form_name)) > 1) { return back()->with('field_error', $form_name.' Occurred Morethan Once'); }
        }

        //duplicate products selected error
        $packages = Arr::flatten($data['packages']);
        $selected_products = Product::whereIn('id', $data['packages'])->get();
        foreach ($selected_products as $package) {
            if (count(array_keys($packages, $package->id)) > 1) { return back()->with('field_error', 'Product: '.$package->name.' Was Selected Morethan Once'); }
        }
        //validation ends

        $formHolder = new FormHolder();
        $formHolder->name = $data['name'];
        $formHolder->parent_id = $formHolder_former->id; //like form_code
        $formHolder->slug = $request->form_code; //like form_code
        $formHolder->form_data = \serialize($request->except(['products', 'q', 'required', 'form_name_selected', '_token']));
        
        $formHolder->created_by = $authUser->id;
        $formHolder->status = 'true';
        $formHolder->save();

        //save Order
        $order = new Order();
        $order->form_holder_id = $formHolder->id;
        $order->source_type = 'form_holder_module';
        $order->status = 'new';
        $order->save();

        //outgoingStock, in place of orderProduct
        $product_ids = [];
        foreach ($data['packages'] as $package) {
            if (!empty($package)) {
                
                $product = Product::where('id', $package)->first();
                $product_ids[] = $product->id;
                $outgoingStock = new OutgoingStock();
                $outgoingStock->product_id = $product->id;
                $outgoingStock->order_id = $order->id;
                $outgoingStock->quantity_removed = 1;
                $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
                $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                $outgoingStock->quantity_returned = 0; //by default
                $outgoingStock->created_by = $authUser->id;
                $outgoingStock->status = 'true';
                $outgoingStock->save();
                
            }  
        }

        //update formHolder
        $formHolder->update(['order_id'=>$order->id]);
        $order->update(['products'=>serialize($product_ids)]);

        if ( (isset($formHolder_former->orderbump_id)) && (isset($formHolder_former->orderbump->product->id)) ) {
            //orderbump
            $former_orderbump = $formHolder_former->orderbump;

            $orderbump = new OrderBump();
            $orderbump->orderbump_heading = $former_orderbump->orderbump_heading;
            $orderbump->orderbump_subheading = $former_orderbump->orderbump_subheading;
            $orderbump->product_id = $former_orderbump->product_id;
            $orderbump->order_id = $order->id;
            $orderbump->product_expected_quantity_to_be_sold = 1;
            $orderbump->product_expected_amount = 0;
            $orderbump->status = 'true';
            $orderbump->save();

            $product = $former_orderbump->product;

            //outgoing stock for orderbump
            $outgoingStock = new OutgoingStock();
            $outgoingStock->product_id = $product->id;
            $outgoingStock->order_id = $order->id;
            $outgoingStock->quantity_removed = 1;
            $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
            $outgoingStock->reason_removed = 'as_orderbump'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
            $outgoingStock->quantity_returned = 0; //by default
            $outgoingStock->created_by = $authUser->id;
            $outgoingStock->status = 'true';
            $outgoingStock->save();

            //update formHolder
            $formHolder->update(['orderbump_id'=>$orderbump->id]);
        }

        if ( (isset($formHolder_former->upsell_id)) && (isset($formHolder_former->upsell->product->id)) ) {
            //orderbump
            $former_upsell = $formHolder_former->upsell;

            $upsell = new UpSell();
            $upsell->upsell_heading = $former_upsell->upsell_heading;
            $upsell->upsell_subheading = $former_upsell->upsell_subheading;
            $upsell->upsell_setting_id = $former_upsell->upsell_setting_id;
            $upsell->product_id = $former_upsell->product_id;
            $upsell->order_id = $order->id;
            $upsell->product_expected_quantity_to_be_sold = 1;
            $upsell->product_expected_amount = 0;
            $upsell->status = 'true';
            $upsell->save();

            $product = $former_upsell->product;

            //outgoing stock for upsell
            $outgoingStock = new OutgoingStock();
            $outgoingStock->product_id = $product->id;
            $outgoingStock->order_id = $order->id;
            $outgoingStock->quantity_removed = 1;
            $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
            $outgoingStock->reason_removed = 'as_upsell'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
            $outgoingStock->quantity_returned = 0; //by default
            $outgoingStock->created_by = $authUser->id;
            $outgoingStock->status = 'true';
            $outgoingStock->save();

            //update formHolder
            $formHolder->update(['upsell_id'=>$upsell->id]);
        }

        return back()->with('success', 'Duplicate Form Created Successfully');
        
    }

    public function editNewFormBuilder ($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            abort(404);
        }
        $form_code = $formHolder->slug;

        //data to show/edit
        $formData = \unserialize($formHolder->form_data);
        
        $formContactInfo = [];
        $formPackage = [];
        $form_names = $formData['form_names'];
        $form_labels = $formData['form_labels'];
        $form_types = $formData['form_types'];
        $packages = $formData['packages']; //["1", "2"]

        $products = Product::all();

        foreach($packages as $key=>$package):
            $package_select_edit[] =
            '<div class="row w-100">
                <div class="col-sm-1 rem-on-display" onclick="$(this).closest(\'.row\').remove()">
                    <button class="btn btn-sm btn-default" type="button"><span class="bi bi-x-lg"></span></button>
                </div>
                <div class="col-sm-11 d-flex align-items-center">
                    <div class="mb-3 q-fc w-100">';
                    $package_select_edit[] .=
                    '<select class="select2 form-control border select-checkbox" name="packages[]" style="width:100%">';
                       if($this->productById($package) !== ""): $package_select_edit[] .= '<option value="'.$package.'" selected> '.$this->productById($package)->name.' </option>'; endif;
                        foreach($products as $product):
                            $package_select_edit[] .= '<option value="'. $product->id.'"> '.$product->name.'</option>';
                        endforeach;
                    $package_select_edit[] .=
                    '</select>
                    <input type="hidden" name="former_packages[]" value="'.$package.'">
                    </div>
                </div>
            </div>';
        endforeach;

        //return $package_select_edit;
        
        //for cloning
        $package_select = '<select class="form-control select-checkbox" name="packages[]" style="width:100%">
        <option value=""> --Select Product-- </option>';
        foreach($products as $product):
            $package_select .= '<option value="'. $product->id.'"> '.$product->name.'</option>' ;
        endforeach;
        $package_select .='</select>';

        //cos form_names are not determind by staff in form-building
        foreach ( $form_names as $key => $form_name ) {
            //$formContact[Str::slug($form_name)] = [ Str::slug($form_name), $form_labels[$key], $form_types[$key] ];
            $formContactInfo['form_name'] = $form_name;
            $formContactInfo['form_label'] = $form_labels[$key];
            $formContactInfo['form_type'] = $form_types[$key];
            $formContact[] = $formContactInfo;
        }
        // return $formContact;

        //extract products
        foreach ($formContact as $key => $formProduct) {
            if ($formProduct['form_name'] == 'Product Package') {
                $package_form_name = $formProduct['form_name'];
                $package_form_label = $formProduct['form_label'];
                $package_form_type = $formProduct['form_type'];
            }
        }
        
        // return $products;

        return view('pages.editNewFormBuilder', compact('authUser', 'user_role', 'formHolder', 'products', 'package_select', 'form_code', 'formContact', 'packages', 'package_select_edit'));

    }

    public function editNewFormBuilderPost (Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string',
            'packages' => 'required',
            'form_names' => 'required',
            'form_labels' => 'required',
        ]);

        $data = $request->all();
        //neccessary fields contain at least one value
        if ((count(array_filter($data['packages'])) == 0) || (count(array_filter($data['form_names'])) == 0) || (count(array_filter($data['form_labels'])) == 0)) {
            return back()->with('field_error', 'Missing Fields');
        }

        $form_names_expected = array("First Name", "Last Name", "Phone Number", "Whatsapp Phone Number", "Active Email", "State", "City", "Address", "Product Package");
        //check form-names & labels
        $form_names = Arr::flatten($data['form_names']);
        foreach ($form_names_expected as $form_name) {
            if (!in_array($form_name, $form_names)) { return back()->with('field_error', $form_name.' is Missing'); }

            //duplicates error
            if (count(array_keys($form_names, $form_name)) > 1) { return back()->with('field_error', $form_name.' Occurred Morethan Once'); }
        }

        //duplicate products selected error
        $packages = Arr::flatten($data['packages']);
        $selected_products = Product::whereIn('id', $data['packages'])->get();
        foreach ($selected_products as $package) {
            if (count(array_keys($packages, $package->id)) > 1) { return back()->with('field_error', 'Product: '.$package->name.' Was Selected Morethan Once'); }
        }

        $order = $formHolder->order;
        $form_code = $formHolder->slug;
        
        $formHolder->form_data = \serialize($request->except(['products', 'q', 'required', 'form_name_selected', '_token', 'former_packages']));
        
        //$formHolder->created_by = $authUser->id;
        $formHolder->status = 'true';
        $formHolder->save();

        //outgoingStock, in place of orderProduct
        //remove n replace outgoing stock
        $former_packages = $data['former_packages']; //["1","2"]
        foreach ($former_packages as $key=>$former_package) {
            $outgoingStock = OutgoingStock::where(['order_id'=>$order->id, 'product_id'=>$former_package])->delete();
        }

        $product_ids = [];
        foreach ($data['packages'] as $package) {
            if (!empty($package)) {
                
                $product = Product::where('id', $package)->first();
                $product_ids[] = $product->id;

                //add unexisting selected package
                $outgoingStock = new OutgoingStock();
                $outgoingStock->product_id = $product->id;
                $outgoingStock->order_id = $order->id;
                $outgoingStock->quantity_removed = 1;
                $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
                $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                $outgoingStock->quantity_returned = 0; //by default
                $outgoingStock->created_by = $authUser->id;
                $outgoingStock->status = 'true';
                $outgoingStock->save();
                
            }  
        }

        //update formHolder, no need
        //$formHolder->update(['order_id'=>$order->id]);
        $order->update(['products'=>serialize($product_ids)]);

        return back()->with('success', 'Form Updated Successfully');
    }

    //not used. ajax save form first time
    public function formBuilderSave(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $result = $data['result']; //object
        $form_name = $data['name'];

        if ($form_name == "" || $form_name == null) {
            $rand = \mt_rand(0, 999999);
            $form_name = 'KpForm'.$rand;
        }
        $slug = Str::slug($form_name);
        
        if (FormHolder::where(['slug' => $slug])->get()->count() > 0) {
            return response()->json([
                'data'=>'error',
            ]);
        } else {

            foreach ($result as $key => $val) {
                // $className[] = $val['type'];
                if ($val['name'] == 'package') {
                    $packages[] = $val;
                }
                if ($val['name'] !== 'package') {
                    $contacts[] = $val;
                }
            }
    
            $formHolder = new FormHolder();
            $formHolder->name = $form_name;
            $formHolder->contact = serialize($contacts);
            $formHolder->package = serialize($packages);
            $formHolder->created_by = $authUser->id;
            $formHolder->status = 'true';
            $formHolder->save();
    
            //save Order
            $order = new Order();
            $order->form_holder_id = $formHolder->id;
            $order->source_type = 'form_holder_module';
            $order->status = 'new';
            $order->save();
    
            //outgoingStock, in place of orderProduct
            $product_ids = [];
            foreach ($packages as $package) {
                foreach ($package['values'] as $key => $option) {
                    $product = Product::where('code', $option['value'])->first();
                    $product_ids[] = $product->id;
                    $outgoingStock = new OutgoingStock();
                    $outgoingStock->product_id = $product->id;
                    $outgoingStock->order_id = $order->id;
                    $outgoingStock->quantity_removed = 1;
                    $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
                    $outgoingStock->reason_removed = 'as_order_firstphase'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
                    $outgoingStock->quantity_returned = 0; //by default
                    $outgoingStock->created_by = $authUser->id;
                    $outgoingStock->status = 'true';
                    $outgoingStock->save();
                }
                
            }
    
            //update formHolder
            $formHolder->update(['order_id'=>$order->id]);
            $order->update(['products'=>serialize($product_ids)]);
            
            $data['package'] = $package;
            $data['contacts'] = $contacts;
            $data['form_name'] = $form_name;
    
            return response()->json([
                // 'unique_key'=>$unique_key,
                // 'grand_total'=>$grand_total,
                'data'=>$data,
            ]);

        }

    }
    
    public function allFormBuilders()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolds = FormHolder::orderBy('id', 'DESC')->get();
        $formHolders = [];
        foreach ($formHolds as $key => $formHolder) {
            $formHolder['contact'] = \unserialize($formHolder->contact);
            $formHolder['package'] = \unserialize($formHolder->package);
            $formHolders[] = $formHolder;
        }
        // return $formHolders;

        $products = Product::where('status', 'true')->get();
        return view('pages.allFormBuilders', compact('authUser', 'user_role', 'formHolders', 'products'));
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
        
        //orderbump
        $orderbump = new OrderBump();
        $orderbump->orderbump_heading = !empty($data['orderbump_heading']) ? $data['orderbump_heading'] : 'Would You Like to Add this Package to your Order';
        $orderbump->orderbump_subheading = !empty($data['orderbump_subheading']) ? $data['orderbump_subheading'] : 'It\'s an Amazing Offer';
        $orderbump->product_id = $data['orderbump_product'];
        $orderbump->order_id = $formHolder->order->id;
        $orderbump->product_expected_quantity_to_be_sold = 1;
        $orderbump->product_expected_amount = 0;
        // $outgoingStock->created_by = $authUser->id;
        $orderbump->status = 'true';
        $orderbump->save();

        $product = Product::where('id', $data['orderbump_product'])->first();
        
        //outgoing stock
        $outgoingStock = new OutgoingStock();
        $outgoingStock->product_id = $data['orderbump_product'];
        $outgoingStock->order_id = $formHolder->order->id;
        $outgoingStock->quantity_removed = 1;
        $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
        $outgoingStock->reason_removed = 'as_orderbump'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
        $outgoingStock->quantity_returned = 0; //by default
        $outgoingStock->created_by = $authUser->id;
        $outgoingStock->status = 'true';
        $outgoingStock->save();

        //update formHolder
        $formHolder->update(['orderbump_id'=>$orderbump->id]);

        return back()->with('success', 'Order bump Added Successfully');
    }

    public function editOrderbumpToForm(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'orderbump_product' => 'required',
        ]);
        $data = $request->all();
        $formHolder = FormHolder::where('unique_key', $data['editOrderbump_form_unique_key'])->first();
        if (!isset($formHolder)) {
            abort(404);
        }
        

        //orderbump
        $orderbump = OrderBump::where('id', $formHolder->orderbump->id)->first();
        $orderbump->orderbump_heading = !empty($data['orderbump_heading']) ? $data['orderbump_heading'] : 'Would You Like to Add this Package to your Order';
        $orderbump->orderbump_subheading = !empty($data['orderbump_subheading']) ? $data['orderbump_subheading'] : 'It\'s an Amazing Offer';
        $orderbump->product_id = $data['orderbump_product'];
        $orderbump->order_id = $formHolder->order->id;
        $orderbump->product_expected_quantity_to_be_sold = 1;
        $orderbump->product_expected_amount = 0;
        // $outgoingStock->created_by = $authUser->id;
        $orderbump->status = 'true';
        $orderbump->save();

        $product = Product::where('id', $data['orderbump_product'])->first();
        
        //outgoing stock
        $outgoingStock = OutgoingStock::where('order_id', $formHolder->order->id)->where('reason_removed', 'as_orderbump')->first();
        $outgoingStock->product_id = $data['orderbump_product'];
        $outgoingStock->order_id = $formHolder->order->id;
        $outgoingStock->quantity_removed = 1;
        $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
        $outgoingStock->reason_removed = 'as_orderbump'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
        $outgoingStock->quantity_returned = 0; //by default
        $outgoingStock->created_by = $authUser->id;
        $outgoingStock->status = 'true';
        $outgoingStock->save();

        //update formHolder
        $formHolder->update(['orderbump_id'=>$orderbump->id]);

        return back()->with('success', 'OrderBump Updated Added Successfully');
    }

    public function addUpsellToForm(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'upsell_product' => 'required',
        ]);

        $data = $request->all();
        $formHolder = FormHolder::where('unique_key', $data['addUpsell_form_unique_key'])->first();
        if (!isset($formHolder)) {
            abort(404);
        }
        
        //upsell
        $upsell = new UpSell();
        $upsell->upsell_heading = !empty($data['upsell_heading']) ? $data['upsell_heading'] : 'Wait, One More Chance';
        $upsell->upsell_subheading = !empty($data['upsell_subheading']) ? $data['upsell_subheading'] : 'We\'re giving this at a giveaway price';
        $upsell->upsell_setting_id = $data['upsell_setting_id'];
        $upsell->product_id = $data['upsell_product'];
        $upsell->order_id = $formHolder->order->id;
        $upsell->product_expected_quantity_to_be_sold = 1;
        $upsell->product_expected_amount = 0;
        // $upsell->created_by = $authUser->id;
        $upsell->status = 'true';
        $upsell->save();

        $product = Product::where('id', $data['upsell_product'])->first();
        
        //outgoing stock
        $outgoingStock = new OutgoingStock();
        $outgoingStock->product_id = $data['upsell_product'];
        $outgoingStock->order_id = $formHolder->order->id;
        $outgoingStock->quantity_removed = 1;
        $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
        $outgoingStock->reason_removed = 'as_upsell'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
        $outgoingStock->quantity_returned = 0; //by default
        $outgoingStock->created_by = $authUser->id;
        $outgoingStock->status = 'true';
        $outgoingStock->save();

        //update formHolder
        $formHolder->update(['upsell_id'=>$upsell->id]);

        return back()->with('success', 'UpSell Added Successfully');
    }

    public function editUpsellToForm(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'upsell_product' => 'required',
        ]);
        $data = $request->all();
        $formHolder = FormHolder::where('unique_key', $data['editUpsell_form_unique_key'])->first();
        if (!isset($formHolder)) {
            abort(404);
        }
        $data = $request->all();

        //upsell
        $upsell = UpSell::where('id', $formHolder->upsell->id)->first();
        $upsell->upsell_heading = !empty($data['upsell_heading']) ? $data['upsell_heading'] : 'Wait, One More Chance';
        $upsell->upsell_subheading = !empty($data['upsell_subheading']) ? $data['upsell_subheading'] : 'We\'re giving this at a giveaway price';
        $upsell->upsell_setting_id = $data['upsell_setting_id'];
        $upsell->product_id = $data['upsell_product'];
        $upsell->order_id = $formHolder->order->id;
        $upsell->product_expected_quantity_to_be_sold = 1;
        $upsell->product_expected_amount = 0;
        // $upsell->created_by = $authUser->id;
        $upsell->status = 'true';
        $upsell->save();

        $product = Product::where('id', $data['upsell_product'])->first();
        
        //outgoing stock
        $outgoingStock = OutgoingStock::where('order_id', $formHolder->order->id)->where('reason_removed', 'as_upsell')->first();
        $outgoingStock->product_id = $data['upsell_product'];
        $outgoingStock->order_id = $formHolder->order->id;
        $outgoingStock->quantity_removed = 1;
        $outgoingStock->amount_accrued = $product->sale_price; //since qty is always one
        $outgoingStock->reason_removed = 'as_upsell'; //as_order_firstphase, as_orderbump, as_upsell as_expired, as_damaged,
        $outgoingStock->quantity_returned = 0; //by default
        $outgoingStock->created_by = $authUser->id;
        $outgoingStock->status = 'true';
        $outgoingStock->save();

        //update formHolder
        $formHolder->update(['upsell_id'=>$upsell->id]);

        return back()->with('success', 'UpSell Updated Successfully');
    }

    //for external webpages
    // public function formEmbedded($unique_key)
    // {
    //     $authUser = auth()->user();
    //     $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
    //     $formHolder = FormHolder::where('unique_key', $unique_key)->first();
    //     if (!isset($formHolder)) {
    //         \abort(404);
    //     }
    //     $formName = $formHolder->name;
    //     $formContact = \unserialize($formHolder->contact);
    //     $formPackage = \unserialize($formHolder->package);
    //     foreach ($formPackage as $package) {
    //         foreach ($package['values'] as $key => $option) {
    //             $option['product_price'] = Product::where('code', $option['value'])->first()->sale_price;
    //             $option['type'] = $package['type'] == 'radio-group' ? 'radio' : 'checkbox';
    //             $products[] = $option;
    //         }
            
    //     }
        
    //     // return $products;
    //     // [
    //     //     {
    //     //         "type":"radio-group","required":"false","label":"Select A Package From Below:","inline":"false","name":"package","access":"false","other":"false",
    //     //         "values":[
    //     //             {"label":"1 Bottle of Instant FLusher Pill + 1 Pack of Instant Flusher Tea","value":null,"selected":"false"},
    //     //             {"label":"2 Bottles of Instant FLusher Pill + 2 Packs of Instant Flusher Tea","value":null,"selected":"false"}
    //     //             ]
    //     //     }
    //     // ]
        
    //     return view('pages.formEmbedded', compact('authUser', 'user_role', 'unique_key', 'formHolder', 'formName', 'formContact', 'formPackage', 'products'));
    // }

    public function formEmbedded($unique_key, $current_order_id="", $stage="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            \abort(404);
        }
        if ($current_order_id !== "") {
            $order = Order::where('id', $current_order_id)->first();
            if (!isset($order)) {
                \abort(404);
            }
            $order->outgoingStocks()->where(['customer_acceptance_status'=>NULL])
            ->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>1, 'reason_returned'=>'declined']);
        } else {
            $order = $formHolder->order;
        }
        
        $stage="";

        $authUser = User::find(1);

        $formName = $formHolder->name;
        $formData = \unserialize($formHolder->form_data);
        
        $formContactInfo = [];
        $formPackage = [];
        $form_names = $formData['form_names'];
        $form_labels = $formData['form_labels'];
        $form_types = $formData['form_types'];
        $packages = $formData['packages'];

        //cos form_names are not determind by staff in form-building
        foreach ( $form_names as $key => $form_name ) {
            //$formContact[Str::slug($form_name)] = [ Str::slug($form_name), $form_labels[$key], $form_types[$key] ];
            $formContactInfo['form_name'] = Str::slug($form_name);
            $formContactInfo['form_label'] = $form_labels[$key];
            $formContactInfo['form_type'] = $form_types[$key];
            $formContact[] = $formContactInfo;
        }

        //extract products
        foreach ($formContact as $key => $formProduct) {
            if ($formProduct['form_name'] == Str::slug('Product Package')) {
                $package_form_name = $formProduct['form_name'];
                $package_form_label = $formProduct['form_label'];
                $package_form_type = $formProduct['form_type'];
            }
        }
        
        //products package
        //products package
        foreach ($packages as $key => $package) {
            $product = Product::where('id', $package)->first();
            $formPackage['id'] = $package; //product_id
            $formPackage['name'] = $product->name;
            $formPackage['price'] = $product->sale_price;
            $formPackage['stock_available'] = $product->stock_available();
            $formPackage['form_name'] = Str::slug($package_form_name);
            $formPackage['form_label'] = $package_form_label;
            $formPackage['form_type'] = $package_form_type;
            $products[] = $formPackage;
        }
        //name, labels, type, in dat order
      
        //for thankyou part
        $order = $formHolder->order;
        $mainProduct_revenue = 0;  //price * qty
        $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
        'customer_acceptance_status'=>'accepted'])->get();

        if ( count($mainProducts_outgoingStocks) > 0 ) {
            foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
            }
        }

        //orderbump
        $orderbumpProduct_revenue = 0; //price * qty
        $orderbump_outgoingStock = '';
        if (isset($formHolder->orderbump_id)) {
            $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
            if ($orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
                $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
            }
        }

        //upsell
        $upsellProduct_revenue = 0; //price * qty
        $upsell_outgoingStock = '';
        if (isset($formHolder->upsell_id)) {
            $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
            if ($upsell_outgoingStock->customer_acceptance_status == 'accepted') {
                $upsellProduct_revenue += $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
            }
        }
        
        //order total amt
        $order_total_amount = $mainProduct_revenue + $orderbumpProduct_revenue + $upsellProduct_revenue;
        $grand_total = $order_total_amount; //might include discount later
        
        $orderId = ''; //used in thankYou section
        if ($order->id < 10){
            $orderId = '0000'.$order->id;
        }
        // <!-- > 10 < 100 -->
        if (($order->id > 10) && ($order->id < 100)) {
            $orderId = '000'.$order->id;
        }
        // <!-- > 100 < 1000 -->
        if (($order->id) > 100 && ($order->id < 1000)) {
            $orderId = '00'.$order->id;
        }
        // <!-- > 1000 < 10000++ -->
        if (($order->id) > 1000 && ($order->id < 1000)) {
            $orderId = '0'.$order->id;
        }

        //package or product qty. sum = 0, if it doesnt exist
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
        $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
        $qty_total = $qty_main_product + $qty_orderbump + $qty_upsell;

        //end thankyou part

        $customer = ''; $invoiceData = [];
        if (isset($order->customer)) {
            //customer
            $customer =  $order->customer;

            $receipients = Arr::collapse([[$authUser->email],[$customer->email]]);

            // event(new TestEvent($invoiceData));
        }
        
        return view('pages.formEmbedded', compact('authUser', 'user_role', 'unique_key', 'formHolder', 'formName', 'formContact', 'formPackage', 'products',
        'mainProducts_outgoingStocks', 'order', 'orderId', 'mainProduct_revenue', 'orderbump_outgoingStock', 'orderbumpProduct_revenue', 'upsell_outgoingStock',
        'upsellProduct_revenue', 'customer', 'qty_total', 'order_total_amount', 'grand_total', 'stage'));
    }

    //like single newFormBuilder
    public function newFormLink($unique_key, $current_order_id="", $stage="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            \abort(404);
        }

        if ($current_order_id !== "") {
            $order = Order::where('id', $current_order_id)->first();
            if (!isset($order)) {
                \abort(404);
            }
            $order->outgoingStocks()->where(['customer_acceptance_status'=>NULL])
            ->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>1, 'reason_returned'=>'declined']);
        } else {
            $order = $formHolder->order;
        }

        $authUser = User::find(1);

        $customer_ip_address = \Request::ip();
        $existingCart = CartAbandon::where(['order_id'=>$order->id, 'form_holder_id'=>$formHolder->id, 'customer_ip_address'=>$customer_ip_address])->first();
        if (isset($existingCart)) {
            $cartAbandoned_id = $existingCart->id;
        } else {
            //create new cart-abandoned, for this process
            $cartAbandoned = new CartAbandon();
            $cartAbandoned->order_id = $order->id;
            $cartAbandoned->form_holder_id = $formHolder->id;
            $cartAbandoned->customer_delivery_duration = 1;
            $cartAbandoned->customer_ip_address = $customer_ip_address;
            $cartAbandoned->save();
            $cartAbandoned_id = $cartAbandoned->id;
        }

        $formName = $formHolder->name;
        $formData = \unserialize($formHolder->form_data);
        
        $formContactInfo = [];
        $formPackage = [];
        $form_names = $formData['form_names'];
        $form_labels = $formData['form_labels'];
        $form_types = $formData['form_types'];
        $packages = $formData['packages'];

        //cos form_names are not determind by staff in form-building
        foreach ( $form_names as $key => $form_name ) {
            //$formContact[Str::slug($form_name)] = [ Str::slug($form_name), $form_labels[$key], $form_types[$key] ];
            $formContactInfo['form_name'] = Str::slug($form_name);
            $formContactInfo['form_label'] = $form_labels[$key];
            $formContactInfo['form_type'] = $form_types[$key];
            $formContact[] = $formContactInfo;
        }

        //extract products
        foreach ($formContact as $key => $formProduct) {
            if ($formProduct['form_name'] == Str::slug('Product Package')) {
                $package_form_name = $formProduct['form_name'];
                $package_form_label = $formProduct['form_label'];
                $package_form_type = $formProduct['form_type'];
            }
        }
        
        //products package
        foreach ($packages as $key => $package) {
            $product = Product::where('id', $package)->first();
            $formPackage['id'] = $package; //product_id
            $formPackage['name'] = $product->name;
            $formPackage['price'] = $product->sale_price;
            $formPackage['stock_available'] = $product->stock_available();
            $formPackage['form_name'] = Str::slug($package_form_name);
            $formPackage['form_label'] = $package_form_label;
            $formPackage['form_type'] = $package_form_type;
            $products[] = $formPackage;
        }
        //name, labels, type, in dat order
      
        //for thankyou part
        // $order = $formHolder->order;
        $mainProduct_revenue = 0;  //price * qty
        $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
        'customer_acceptance_status'=>'accepted'])->get();

        if ( count($mainProducts_outgoingStocks) > 0 ) {
            foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
            }
        }

        //orderbump
        $orderbumpProduct_revenue = 0; //price * qty
        $orderbump_outgoingStock = '';
        if (isset($formHolder->orderbump_id)) {
            $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
            if ($orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
                $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
            }
        }

        //upsell
        $upsellProduct_revenue = 0; //price * qty
        $upsell_outgoingStock = '';
        if (isset($formHolder->upsell_id)) {
            $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
            if ($upsell_outgoingStock->customer_acceptance_status == 'accepted') {
                $upsellProduct_revenue += $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
            }
        }
        
        //order total amt
        $order_total_amount = $mainProduct_revenue + $orderbumpProduct_revenue + $upsellProduct_revenue;
        $grand_total = $order_total_amount; //might include discount later
        
        $orderId = ''; //used in thankYou section
        if ($order->id < 10){
            $orderId = '0000'.$order->id;
        }
        // <!-- > 10 < 100 -->
        if (($order->id > 10) && ($order->id < 100)) {
            $orderId = '000'.$order->id;
        }
        // <!-- > 100 < 1000 -->
        if (($order->id) > 100 && ($order->id < 1000)) {
            $orderId = '00'.$order->id;
        }
        // <!-- > 1000 < 10000++ -->
        if (($order->id) > 1000 && ($order->id < 1000)) {
            $orderId = '0'.$order->id;
        }

        //package or product qty. sum = 0, if it doesnt exist
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
        $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
        $qty_total = $qty_main_product + $qty_orderbump + $qty_upsell;

        //end thankyou part

        $customer = ''; $invoiceData = [];
        if (isset($order->customer)) {
            //customer
            $customer =  $order->customer;

            $receipients = Arr::collapse([[$authUser->email],[$customer->email]]);

            // event(new TestEvent($invoiceData));
        }
        
        return view('pages.newFormLink', compact('authUser', 'user_role', 'unique_key', 'formHolder', 'formName', 'formContact', 'formPackage', 'products',
        'mainProducts_outgoingStocks', 'order', 'orderId', 'mainProduct_revenue', 'orderbump_outgoingStock', 'orderbumpProduct_revenue', 'upsell_outgoingStock',
        'upsellProduct_revenue', 'customer', 'qty_total', 'order_total_amount', 'grand_total', 'stage', 'cartAbandoned_id'));
    }

    //newFormLinkPost//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function newFormLinkPost(Request $request, $unique_key, $current_order_id="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'phone-number' => 'required',
            'whatsapp-phone-number' => 'required',
            'active-email' => 'required|email',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'delivery_duration' => 'required',
            'product_packages' => 'required',
        ]);

        if (!empty($request->orderbump_check)) {
            $request->validate([
                'product' => 'required',
            ]);
        } 
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            \abort(404);
        }

        $data = $request->all();
        $order = $formHolder->order;
        
        $existing_customer = Customer::where('order_id', $order->id)->count();
        //order already exist, and customer tries to submit
        if ($existing_customer > 0) {
            if ($request->upsell_available != '') {
                Session::put('upsell_stage', 'true');
                return back();
            } else {
                Session::put('thankyou_stage', 'true');
                return back();
            }
            // return back()->with('info', 'Order Already Taken. We will get back to you shortly');
        }

        //update package in OutgoingStock
        foreach ($data['package'] as $key => $code) {
            if(!empty($code)){
                //accepted updated
                $product_id = Product::where('code', $code)->first()->id;
                OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])
                ->update(['customer_acceptance_status'=>'accepted']);
                
                //rejected or declined updated
                $rejected_products = OutgoingStock::where('product_id', '!=', $product_id)->where('order_id', $order->id)
                ->where('reason_removed','as_order_firstphase')->get();
                foreach ($rejected_products as $key => $rejected) {
                    $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                }
                
            } 
        }

        //accepted orderbump
        if (!empty($request->orderbump_check)) {
            //accepted updated
            $product_id = Product::where('id', $data['product'])->first()->id;
            OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])
            ->update(['customer_acceptance_status'=>'accepted']);
        }

        $customer = new Customer();
        $customer->order_id = $order->id;
        $customer->form_holder_id = $formHolder->id;
        $customer->firstname = $data['first-name'];
        $customer->lastname = $data['last-name'];
        $customer->phone_number = $data['phone-number'];
        $customer->whatsapp_phone_number = $data['whatsapp-phone-number'];
        $customer->email = $data['email'];
        $customer->delivery_address = $data['delivery-address'];
        $customer->created_by = $authUser->id;
        $customer->status = 'true';
        $customer->save();

        //update order status
        $order->update(['customer_id'=>$customer->id, 'delivery_duration'=>$data['delivery_duration'], 'status'=>'new']);

        //to activate psell & thankyou part
        if ($request->upsell_available != '') {
            Session::put('upsell_stage', 'true');
        } else {
            Session::put('thankyou_stage', 'true');
        }
        
        //return back()->with('order-success', 'Saved Successfully');
        return back();

    }

    //after clicking first main btn, ajax
    public function saveNewFormFromCustomer(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        //delete cartabandoned

        $cartAbandon = CartAbandon::where('id', $data['cartAbandoned_id']);
        if($cartAbandon->exists()) {
            $cartAbandon->delete();
        }

        $formHolder = FormHolder::where('unique_key', $data['unique_key'])->first();
        $order = $formHolder->order;

        //cos order was created initially @ newFormBuilderPost
        if (isset($order->customer_id)) {
            
            //save Order
            $newOrder = new Order();
            $newOrder->form_holder_id = $formHolder->id;
            $newOrder->source_type = 'form_holder_module';
            $newOrder->status = 'new';
            $newOrder->save();

            //making a copy from the former outgoingStocks
            $outgoingStocks = $order->outgoingStocks;
            foreach($outgoingStocks as $i => $outgoingStock)
            {
                //make copy of rows, and create new records
                $outgoingStocks[$i]->order_id = $newOrder->id;
                $outgoingStocks[$i]->quantity_returned = 0;
                $outgoingStocks[$i]->quantity_removed = 1;
                $outgoingStocks[$i]->amount_accrued = $outgoingStock->product->sale_price;
                $x[$i] = (new OutgoingStock())->create($outgoingStock->only(['product_id', 'order_id', 'quantity_removed', 'amount_accrued',
                'reason_removed', 'quantity_returned', 'created_by', 'status']));
            }
            // return $x;

            // $order = $order;

            foreach ($data['product_packages'] as $key => $product_id) {
                $data['product_id'] = $product_id;
                if (!empty($product_id)) {

                    $idPriceQty = explode('-', $product_id);
                    $productId = $idPriceQty[0];
                    $saleUnitPrice = $idPriceQty[1];
                    $qtyRemoved = $idPriceQty[2];

                    //accepted updated
                    $amount_accrued = $qtyRemoved * $saleUnitPrice;
                    OutgoingStock::where(['product_id'=>$productId, 'order_id'=>$newOrder->id, 'reason_removed'=>'as_order_firstphase'])
                    ->update(['quantity_removed'=>$qtyRemoved, 'amount_accrued'=>$amount_accrued, 'customer_acceptance_status'=>'accepted']);
                    
                    //rejected or declined updated
                    // $rejected_products = OutgoingStock::where('product_id', '!=', $productId)->where('order_id', $newOrder->id)
                    // ->where('reason_removed','as_order_firstphase')->get();
                    // foreach ($rejected_products as $key => $rejected) {
                    //     $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                    // }
                    
                } 
            }
                    
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
            $customer->created_by = $authUser->id;
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
                    OutgoingStock::where(['product_id'=>$productId, 'order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])
                    ->update(['quantity_removed'=>$qtyRemoved, 'amount_accrued'=>$amount_accrued, 'customer_acceptance_status'=>'accepted']);
                    
                    //rejected or declined updated
                    // $rejected_products = OutgoingStock::where('product_id', '!=', $productId)->where('order_id', $order->id)
                    // ->where('reason_removed','as_order_firstphase')->get();
                    // foreach ($rejected_products as $key => $rejected) {
                    //     $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                    // }
                    
                } 
            }
        
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
            $customer->created_by = $authUser->id;
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

    //saveNewFormOrderBumpFromCustomer
    public function saveNewFormOrderBumpFromCustomer(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $formHolder = FormHolder::where('unique_key', $data['unique_key'])->first();
        // $order = $formHolder->order;
        $order = Order::where('id', $data['current_order_id'])->first();

        //accepted orderbump
        if (!empty($data['orderbump_product_checkbox'])) {
            //accepted updated
            $product_id = Product::where('id', $data['orderbump_product_checkbox'])->first()->id;
            OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])
            ->update(['customer_acceptance_status'=>'accepted']);
        }

        //update order with same orderbump as formholder
        // $order->update(['orderbump_id'=>$formHolder->orderbump_id]);
        $order->orderbump_id = $formHolder->orderbump_id;
        $order->save();

        $has_upsell = isset($formHolder->upsell_id) ? true : false;
        $data['has_upsell'] = $has_upsell;

        $customer =  $order->customer;

        //call notify fxn
        if ($has_upsell==false) {
            $this->invoiceData($formHolder, $customer, $order);

        }

        return response()->json([
            'status'=>true,
            'data'=>$data,
        ]);
    }

    //saveNewFormOrderBumpFromCustomer
    public function saveNewFormUpSellFromCustomer(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $formHolder = FormHolder::where('unique_key', $data['unique_key'])->first();
        // $order = $formHolder->order;
        $order = Order::where('id', $data['current_order_id'])->first();

        //accepted orderbump
        if (!empty($data['upsell_product_checkbox'])) {
            //accepted updated
            $product_id = Product::where('id', $data['upsell_product_checkbox'])->first()->id;
            OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_upsell'])
            ->update(['customer_acceptance_status'=>'accepted']);
        }

        //update order with same orderbump as formholder
        $order->update(['upsell_id'=>$formHolder->upsell_id]);

        //////////////////////////////////////////////////////////////////////////////////////
        $customer =  $order->customer;
        $this->invoiceData($formHolder, $customer, $order);
    
        //////////////////////////////////////////////////////////////////////////////
        
        return response()->json([
            'status'=>true,
            'data'=>$data,
        ]);
    }

    //declined orderbump
    public function saveNewFormOrderBumpRefusalFromCustomer(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $formHolder = FormHolder::where('unique_key', $data['unique_key'])->first();
        // $order = $formHolder->order;
        $order = Order::where('id', $data['current_order_id'])->first();

        //accepted orderbump
        if (!empty($data['orderbump_product_checkbox'])) {
            //accepted updated
            $product_id = Product::where('id', $data['orderbump_product_checkbox'])->first()->id;
            OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])
            ->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>1, 'reason_returned'=>'declined']);
        }

        //update order with same orderbump as formholder
        $order->update(['orderbump_id'=>null]);

        $has_upsell = isset($formHolder->upsell_id) ? true : false;
        $data['has_upsell'] = $has_upsell;

        return response()->json([
            'status'=>true,
            'data'=>$data,
        ]);
    }

    //declined upsell
    public function saveNewFormUpSellRefusalFromCustomer(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        $formHolder = FormHolder::where('unique_key', $data['unique_key'])->first();
        // $order = $formHolder->order;
        $order = Order::where('id', $data['current_order_id'])->first();

        //declined upsell
        if (!empty($data['upsell_product_checkbox'])) {
            //accepted updated
            $product_id = Product::where('id', $data['upsell_product_checkbox'])->first()->id;
            OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_upsell'])
            ->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>1, 'reason_returned'=>'declined']);
        }

        //update order with same upsell as formholder
        $order->update(['upsell_id'=>null]);

        $has_upsell = isset($formHolder->upsell_id) ? true : false;
        $data['has_upsell'] = $has_upsell;

        return response()->json([
            'status'=>true,
            'data'=>$data,
        ]);
    }

    //cart abandon. gets cleared once submit btn is clicked
    public function cartAbandonContact(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $contact_data = $request->except(['unique_key']);
        $cartAbandoned_id = $data['cartAbandoned_id'];

        $contactFieldArr = explode('|', $data['inputVal']); //Ugo|first-name
        $contact = $contactFieldArr[0]; //Ugo
        $field = $contactFieldArr[1]; //first-name

        $cartAbandon = CartAbandon::where('id',$cartAbandoned_id)->first();
        if ($field=='first-name') { $cartAbandon->customer_firstname = $contact; }
        if ($field=='last-name') { $cartAbandon->customer_lastname = $contact; }
        if ($field=='phone-number') { $cartAbandon->customer_phone_number = $contact; }
        if ($field=='whatsapp-phone-number') { $cartAbandon->customer_whatsapp_phone_number = $contact; }
        if ($field=='active-email') { $cartAbandon->customer_email = $contact; }
        if ($field=='state') { $cartAbandon->customer_state = $contact; }
        if ($field=='city') { $cartAbandon->customer_city = $contact; }
        if ($field=='address') { $cartAbandon->customer_delivery_address = $contact; }
        
        $cartAbandon->save();
    
        return response()->json([
            'status'=>true,
            'data'=>$data,
        ]);
    }

    public function cartAbandonDeliveryDuration(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $contact_data = $request->except(['unique_key']);
        $cartAbandoned_id = $data['cartAbandoned_id'];

        $contactFieldArr = explode('|', $data['inputVal']); //Ugo|first-name
        $contact = $contactFieldArr[0]; //Ugo
        $field = $contactFieldArr[1]; //first-name

        $cartAbandon = CartAbandon::where('id',$cartAbandoned_id)->first();
        if ($field=='first-name') { $cartAbandon->customer_firstname = $contact; }
        if ($field=='last-name') { $cartAbandon->customer_lastname = $contact; }
        if ($field=='phone-number') { $cartAbandon->customer_phone_number = $contact; }
        if ($field=='whatsapp-phone-number') { $cartAbandon->customer_whatsapp_phone_number = $contact; }
        if ($field=='active-email') { $cartAbandon->customer_email = $contact; }
        if ($field=='state') { $cartAbandon->customer_state = $contact; }
        if ($field=='city') { $cartAbandon->customer_city = $contact; }
        if ($field=='address') { $cartAbandon->customer_delivery_address = $contact; }
        
        $cartAbandon->customer_delivery_duration = $data['delivery_duration'];

        $cartAbandon->save();

        return response()->json([
            'status'=>true,
            'data'=>$data,
        ]);
    }

    public function cartAbandonPackage(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();
        $package_data = $request->except(['unique_key']);

        $cartAbandoned_id = $data['cartAbandoned_id'];

        $contactFieldArr = explode('|', $data['inputVal']); //Ugo|first-name
        $contact = $contactFieldArr[0]; //Ugo
        $field = $contactFieldArr[1]; //first-name

        $cartAbandon = CartAbandon::where('id',$cartAbandoned_id)->first();
        if ($field=='first-name') { $cartAbandon->customer_firstname = $contact; }
        if ($field=='last-name') { $cartAbandon->customer_lastname = $contact; }
        if ($field=='phone-number') { $cartAbandon->customer_phone_number = $contact; }
        if ($field=='whatsapp-phone-number') { $cartAbandon->customer_whatsapp_phone_number = $contact; }
        if ($field=='active-email') { $cartAbandon->customer_email = $contact; }
        if ($field=='state') { $cartAbandon->customer_state = $contact; }
        if ($field=='city') { $cartAbandon->customer_city = $contact; }
        if ($field=='address') { $cartAbandon->customer_delivery_address = $contact; }

        $cartAbandon->package_info = serialize($data['product_package']);
        $cartAbandon->save();

        return response()->json([
            'status'=>true,
            'data'=>$data,
        ]);
    }

    //invoiceData(), callback for notifying admin abt new order
    public function invoiceData($formHolder, $customer, $order)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        //create soundNotification
        $soundNotification = new SoundNotification();
        $soundNotification->type = 'Order';
        $soundNotification->topic = 'New Order';
        $soundNotification->content = 'Customer placed an order';
        $soundNotification->link = 'order-form/'.$order->unique_key;
        $soundNotification->order_id = $order->id;
        $soundNotification->status = 'new';
        $soundNotification->save();
        
        //mainProduct_revenue
        $mainProduct_revenue = 0;  //price * qty
        $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase', 'customer_acceptance_status'=>'accepted'])->get();

        if ( count($mainProducts_outgoingStocks) > 0 ) {
            foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
            }
        }

        //orderbump
        $orderbumpProduct_revenue = 0; //price * qty
        $orderbump_outgoingStock = '';
        if (isset($formHolder->orderbump_id)) {
            $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
            if ($orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
                $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
            }
        }
        
        //upsell
        $upsellProduct_revenue = 0; //price * qty
        $upsell_outgoingStock = '';
        if (isset($formHolder->upsell_id)) {
            $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
            if ($upsell_outgoingStock->customer_acceptance_status == 'accepted') {
                $upsellProduct_revenue += $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
            }
        }
        
        //order total amt

        $orderId = ''; //used in thankYou section
        if ($order->id < 10){
            $orderId = '0000'.$order->id;
        }
        // <!-- > 10 < 100 -->
        if (($order->id > 10) && ($order->id < 100)) {
            $orderId = '000'.$order->id;
        }
        // <!-- > 100 < 1000 -->
        if (($order->id) > 100 && ($order->id < 1000)) {
            $orderId = '00'.$order->id;
        }
        // <!-- > 1000 < 10000++ -->
        if (($order->id) > 1000 && ($order->id < 1000)) {
            $orderId = '0'.$order->id;
        }

        //package or product qty. sum = 0, if it doesnt exist
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
        $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
        $qty_total = $qty_main_product + $qty_orderbump + $qty_upsell;

        $order_total_amount = $mainProduct_revenue + $orderbumpProduct_revenue + $upsellProduct_revenue;
        $grand_total = $order_total_amount; //might include discount later

        $admin = GeneralSetting::first();

        // $whatsapp_phone_number = '';
        // if (substr($customer->whatsapp_phone_number, 0, 1) === '0') {
        //     $whatsapp_phone_number = '234' . substr($customer->whatsapp_phone_number, 1);
        // }

        //$whatsapp_msg = "Hi ".$customer->firstname." ".$customer->lastname.", you just placed order with Invoice-id: kp-".$orderId.". We will get back to you soon";
        $whatsapp_msg = "Hello ".$customer->firstname." ".$customer->lastname.". My name is".$authUser->name.", I am contacting you from KeepMeFit and I am the Customer Service Representative incharge of the order you placed for ";
        $whatsapp_msg .= "";
        foreach($mainProducts_outgoingStocks as $main_outgoingStock):
            $whatsapp_msg .= " [Product: ".$main_outgoingStock->product->name.". Price: ".$mainProduct_revenue.". Qty: ".$main_outgoingStock->quantity_removed."], ";
        endforeach;

        if($orderbump_outgoingStock != ''):
            $whatsapp_msg .= "[Product: ".$orderbump_outgoingStock->product->name.". Price: ".$orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed.". Qty: ".$orderbump_outgoingStock->quantity_removed."], ";
        endif;

        if($upsell_outgoingStock != ''):
            $whatsapp_msg .= "[Product: ".$upsell_outgoingStock->product->name.". Price: ".$upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed.". Qty: ".$upsell_outgoingStock->quantity_removed."]. ";
        endif;

        $whatsapp_msg .= "I am reaching out to you to confirm your order and to let you know the delivery person will call you to deliver your order. Kindly confirm if the details you sent are correct ";

        $whatsapp_msg .= "[Phone Number: ".$customer->phone_number.". Whatsapp Phone Number: ".$customer->whatsapp_phone_number.". Delivery Address: ".$customer->delivery_address."]. ";

        $whatsapp_msg .= "Please kindly let me know when we can deliver your order. Thank you!";

        // //mail user about their new order
        $invoiceData = [
            'order' => $order,
            'orderId' => $orderId,
            'customer' => $customer,
            'mainProducts_outgoingStocks' => $mainProducts_outgoingStocks,
            'mainProduct_revenue' => $mainProduct_revenue,
            'orderbump_outgoingStock' => $orderbump_outgoingStock,
            'orderbumpProduct_revenue' => $orderbumpProduct_revenue,
            'whatsapp_msg' => $whatsapp_msg,
            'upsell_outgoingStock' => $upsell_outgoingStock,
            'upsellProduct_revenue' => $upsellProduct_revenue,
            'qty_total' => $qty_total,
            'order_total_amount' => $order_total_amount,
            'grand_total' => $grand_total,
        ];

        try {
            Notification::route('mail', [$admin->official_notification_email])->notify(new NewOrder($invoiceData));
        } catch (Exception $exception) {
            // return back()->withError($exception->getMessage())->withInput();
            return back();
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //not used
    public function formLink($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            \abort(404);
        }
        $formName = $formHolder->name;
        $formContact = \unserialize($formHolder->contact);
        $formPackage = \unserialize($formHolder->package);
        foreach ($formPackage as $package) {
            foreach ($package['values'] as $key => $option) {
                $option['product_price'] = Product::where('code', $option['value'])->first()->sale_price;
                $option['type'] = $package['type'] == 'radio-group' ? 'radio' : 'checkbox';
                $option['name'] = isset($package['name']) ? $package['name'] : 'package';
                $products[] = $option;
            }
            
        }

        // return $products;
        // [
        //     {
        //         "type":"radio-group","required":"false","label":"Select A Package From Below:","inline":"false","name":"package","access":"false","other":"false",
        //         "values":[
        //             {"label":"1 Bottle of Instant FLusher Pill + 1 Pack of Instant Flusher Tea","value":null,"selected":"false"},
        //             {"label":"2 Bottles of Instant FLusher Pill + 2 Packs of Instant Flusher Tea","value":null,"selected":"false"}
        //             ]
        //     }
        // ]

        //for thankyou part
        $order = $formHolder->order;
        $mainProduct_revenue = 0;  //price * qty
        $mainProducts_outgoingStocks = $order->outgoingStocks()->where(['reason_removed'=>'as_order_firstphase',
        'customer_acceptance_status'=>'accepted'])->get();

        if ( count($mainProducts_outgoingStocks) > 0 ) {
            foreach ($mainProducts_outgoingStocks as $key => $main_outgoingStock) {
                $mainProduct_revenue = $mainProduct_revenue + ($main_outgoingStock->product->sale_price * $main_outgoingStock->quantity_removed);
            }
        }

        //orderbump
        $orderbumpProduct_revenue = 0; //price * qty
        $orderbump_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_orderbump')->first();
        if ($orderbump_outgoingStock->customer_acceptance_status == 'accepted') {
            $orderbumpProduct_revenue = $orderbumpProduct_revenue + ($orderbump_outgoingStock->product->sale_price * $orderbump_outgoingStock->quantity_removed);
        }
        
        //upsell
        $upsellProduct_revenue = 0; //price * qty
        $upsell_outgoingStock = $order->outgoingStocks()->where('reason_removed', 'as_upsell')->first();
        if ($upsell_outgoingStock->customer_acceptance_status == 'accepted') {
            $upsellProduct_revenue = $upsellProduct_revenue + ($upsell_outgoingStock->product->sale_price * $upsell_outgoingStock->quantity_removed);
        }

        //order total amt
        $order_total_amount = $mainProduct_revenue + $orderbumpProduct_revenue + $upsellProduct_revenue;
        $grand_total = $order_total_amount; //might include discount later
        
        $orderId = ''; //used in thankYou section
        if ($order->id < 10){
            $orderId = '0000'.$order->id;
        }
        // <!-- > 10 < 100 -->
        if (($order->id > 10) && ($order->id < 100)) {
            $orderId = '000'.$order->id;
        }
        // <!-- > 100 < 1000 -->
        if (($order->id) > 100 && ($order->id < 100)) {
            $orderId = '00'.$order->id;
        }
        // <!-- > 1000 < 10000++ -->
        if (($order->id) > 100 && ($order->id < 100)) {
            $orderId = '0'.$order->id;
        }

        //customer
        $customer = isset($order->customer) ? $order->customer : '';

        //package or product qty. sum = 0, if it doesnt exist
        $qty_main_product = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_order_firstphase'])->sum('quantity_removed');
        $qty_orderbump = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_orderbump'])->sum('quantity_removed');
        $qty_upsell = OutgoingStock::where(['order_id'=>$order->id, 'customer_acceptance_status'=>'accepted', 'reason_removed'=>'as_upsell'])->sum('quantity_removed');
        $qty_total = $qty_main_product + $qty_orderbump + $qty_upsell;

        //end thankyou part
        
        return view('pages.formLink', compact('authUser', 'user_role', 'unique_key', 'formHolder', 'formName', 'formContact', 'formPackage', 'products',
        'mainProducts_outgoingStocks', 'order', 'orderId', 'mainProduct_revenue', 'orderbump_outgoingStock', 'upsell_outgoingStock',
        'customer', 'qty_total', 'order_total_amount', 'grand_total'));
    }

    //not used formLinkPost
    public function formLinkPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'first-name' => 'required',
            'last-name' => 'required',
            'phone-number' => 'required',
            'whatsapp-phone-number' => 'required',
            'email' => 'required|email',
            'delivery-address' => 'required',
            'package' => 'required'
        ]);

        if (!empty($request->orderbump_check)) {
            $request->validate([
                'product' => 'required',
            ]);
        } 
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            \abort(404);
        }

        $data = $request->all();
        $order = $formHolder->order;
        
        $existing_customer = Customer::where('order_id', $order->id)->count();
        //order already exist, and customer tries to submit
        if ($existing_customer > 0) {
            if ($request->upsell_available != '') {
                Session::put('upsell_stage', 'true');
                return back();
            } else {
                Session::put('thankyou_stage', 'true');
                return back();
            }
            // return back()->with('info', 'Order Already Taken. We will get back to you shortly');
        }

        //update package in OutgoingStock
        foreach ($data['package'] as $key => $code) {
            if(!empty($code)){
                //accepted updated
                $product_id = Product::where('code', $code)->first()->id;
                OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_order_firstphase'])
                ->update(['customer_acceptance_status'=>'accepted']);
                
                //rejected or declined updated
                $rejected_products = OutgoingStock::where('product_id', '!=', $product_id)->where('order_id', $order->id)
                ->where('reason_removed','as_order_firstphase')->get();
                foreach ($rejected_products as $key => $rejected) {
                    $rejected->update(['customer_acceptance_status'=>'rejected', 'quantity_returned'=>$rejected->quantity_removed]);
                }
                
            }
            
        }

        //accepted orderbump
        if (!empty($request->orderbump_check)) {
            //accepted updated
            $product_id = Product::where('id', $data['product'])->first()->id;
            OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_orderbump'])
            ->update(['customer_acceptance_status'=>'accepted']);
        }

        $customer = new Customer();
        $customer->order_id = $order->id;
        $customer->form_holder_id = $formHolder->id;
        $customer->firstname = $data['first-name'];
        $customer->lastname = $data['last-name'];
        $customer->phone_number = $data['phone-number'];
        $customer->whatsapp_phone_number = $data['whatsapp-phone-number'];
        $customer->email = $data['email'];
        $customer->delivery_address = $data['delivery-address'];
        $customer->created_by = $authUser->id;
        $customer->status = 'true';
        $customer->save();

        //update order status
        $order->update(['customer_id'=>$customer->id, 'status'=>'new']);

        //to activate psell & thankyou part
        if ($request->upsell_available != '') {
            Session::put('upsell_stage', 'true');
        } else {
            Session::put('thankyou_stage', 'true');
        }
        
        //return back()->with('order-success', 'Saved Successfully');
        return back();

    }

    //not used
    public function formLinkUpsellPost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formHolder = FormHolder::where('unique_key', $unique_key)->first();
        if (!isset($formHolder)) {
            \abort(404);
        }

        $data = $request->all();
        $order = $formHolder->order;

        if (!empty($request->upsell_product)) {
            //accepted updated
            $product_id = Product::where('id', $data['upsell_product'])->first()->id;
            OutgoingStock::where(['product_id'=>$product_id, 'order_id'=>$order->id, 'reason_removed'=>'as_upsell'])
            ->update(['customer_acceptance_status'=>'accepted']);
        }
        if (!empty(Session::get('upsell_stage'))) {
            Session::forget('upsell_stage');
        }
        Session::put('thankyou_stage', 'true');
        return back();
    }

    //not used
    public function editFormHolder($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $formLabel = OrderLabel::where('unique_key', $unique_key)->first();
        $order_id = $formLabel->order_id;

        $orderProducts = OrderProduct::where('order_id', $order_id)->get(['product_id']); //by column
        $orderBump_product = OrderBump::where('order_id', $order_id)->first();
        $upSell_product = OrderBump::where('order_id', $order_id)->first();

        return view('pages.forms.editForm', compact('authUser', 'user_role', 'formLabel', 'orderProducts', 'orderBump_product', 'upSell_product'));
    }

    public function productById($id){
        $product = Product::where('id',$id)->first();
        if(isset($product)){
            return $product;
        } else {
            return "";
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        return view('pages.test');
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

    //not used
    public function search(Request $request)
    {
        try {
            $user = User::findOrFail($request->input('user_id'));
        } catch (ModelNotFoundException $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return view('users.search', compact('user'));
    }


}
