<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Events\TestEvent;
use App\Helpers\Helper;
use App\Notifications\TestNofication;
use App\Notifications\sendUserMessageNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;
use App\Helpers\FieldMatcher;
use App\Helpers\Naija\Naija;
// use App\Http\Controllers\Admin\ScriptController;
use App\Models\MessageTemplate;
use App\Models\Notification as ModelsNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/wordpress/plugin-update/{plugin}/download.zip', [WordPressPluginController::class, 'serveUpdate'])->name('wordpress.plugin.download');
Route::get('/wordpress/plugin-update/{plugin}/check', [WordPressPluginController::class, 'check'])->name('wordpress.plugin.checkUpdate');


Route::get('/test-helper', function () {
    $states = Naija::states();


    dd($states);
});
Route::get('/test-fields', function () {


    return view('emails.order-email')->with([
        'subject' => "Heloo",
        'content' => 'njkmnknj'
    ]);
    // ModelsNotification::create([
    //     // 'user_id' => auth()->user()->id,
    //     'message' => 'This is a test',
    //     'types' => 'superadmin',
    //     // 'roles' => 'admin',
    // ]);

    // Output the matched fields
    dd(auth()->user()->notifications);
});
Route::get('migrate', function () {
    Artisan::call('migrate');
    return redirect('/login')->with('success', 'Migration completed successfully');
    return view('page')->with('success', 'Migration completed successfully');
});

Route::get('/update-db', function () {
    $orders_statuses = [
        'order_in_transit' => 'Order In Transit',
        'rescheduled_order' => 'Rescheduled Order'
    ];
    $channels = config('site.notification_channels');

    // Loop through each channel and status to create the templates for order status changes
    foreach ($channels as $channel) {

        $messageTemplate = new MessageTemplate();
        $messageTemplate->name = "Order Changed to Rescheduled Order";
        $messageTemplate->channel = $channel;
        $messageTemplate->type = "{$channel}_order_status_changed_to_rescheduled_order";
        $messageTemplate->subject = "Rescheduled Order";
        $messageTemplate->message = "Hi [customer_first_name] [customer_last_name], quick update - your order has been rescheduled based on your request to shift the date for your delivery. \n\n We will do our BEST to deliver on the rescheduled date. Remember, Delay is DANGEROUS \n\n Please make sure to be available on that day to receive it. \n\nIn the meantime, do you have any questions? Need help? \n\n Reply here, and we'll take care of it fast! \n\n[staff_name], Customer Service";
        $messageTemplate->save();

        $messageTemplate = new MessageTemplate();
        $messageTemplate->name = "Order Changed to Order In Transit";
        $messageTemplate->channel = $channel;
        $messageTemplate->type = "{$channel}_order_status_changed_to_order_in_transit";
        $messageTemplate->subject = "Order Sent out for Delivery:";
        $messageTemplate->message = "Hello [customer_first_name] [customer_last_name]! Exciting news - your order is on the way, and the rider will deliver it to you today! Kindly pay to the delivery agent and collect your order.  \n\n It's currently in transit, and we can't wait for you to start enjoying the result you've always wanted. \n\nPlease be with your phone so that when he calls, you will respond. The delivery person has other customers to deliver to, so please don't keep the delivery person waiting. \n\nIf you need any help or have any questions while you wait, just reply to this message. \n\n Get ready - it'll be at your doorstep soon! \n\nThanks for your patronage. \n\n[staff_name], Customer Service";
        $messageTemplate->save();
    }
});


Route::get('/update-order-messages', function () {

    MessageTemplate::whereNotNull('id')->delete();
    $orders_statuses = [
        'new_order_assigned' => "New Order Assigned Message",
        'new_order_message_with_staff' => "New Order Message with Staff",
        'new_order_message_with_no_staff' => "New Order Message without Staff",
        // 'new' => 'New',
        // 'new_from_alarm' => 'New From Alarm',
        'pending' => 'Pending',
        'cancelled' => 'Cancelled',
        'delivered_not_remitted' => 'Delivered Not Remitted',
        'delivered_and_remitted' => 'Delivered and Remitted',
        'rescheduled_order' => 'Rescheduled Order',
        // 'order_in_transit' => 'Order In Transit',
        'order_confirmed' => 'Order Confirmed',
        'order_sent_out' => 'Order Sent Out',
        'delivery_attempted_1' => 'Delivery Attempted 1',
        'delivery_attempted_2' => 'Delivery Attempted 2',
        'delivery_attempted_3' => 'Delivery Attempted 3',
        'cancelled_admin' => 'Cancelled by Admin',
        'customer_unreachable' => 'Customer Unreachable',
        'cancelled_customer' => 'Cancelled by Customer',
        'rejected_customer' => 'Rejected by Customer',
        'duplicate_order' => 'Duplicate Order',
        // 'total_follow_ups' => 'Total Follow Ups',
        // 'today_follow_ups' => 'Today Follow Ups',
        // 'tomorrow_follow_ups' => 'Tomorrow Follow Ups',
        // 'other_orders' => 'Other Orders',

    ];




    $channels = config('site.notification_channels');

    foreach ($orders_statuses as $status => $statusLabel) {
        foreach ($channels as $channel) {


            if ($status === 'new_order_assigned') {
                $name = "New Order Assigned Message";
                $type = "{$channel}_new_order_assigned";
            } elseif ($status === 'new_order_message_with_staff') {
                $name = "New Order Message with Staff";
                $type = "{$channel}_new_order_message_with_staff";
            } elseif ($status === 'new_order_message_with_no_staff') {
                $name = "New Order Message without Staff";
                $type = "{$channel}_new_order_message_with_no_staff";
            } else {
                $name = "Order Changed to {$statusLabel}";
                $type = "{$channel}_order_status_changed_to_{$status}";
            }


            // Check if a message template for this type already exists
            if (!MessageTemplate::where('type', $type)->exists()) {
                $messageTemplate = new MessageTemplate();
                // 'new_order_assigned' => "New Order Assigned Message",
                // 'new_order_message_with_staff' => "New Order Message with Staff",
                // 'new_order_message_with_no_staff' => "New Order Message without Staff",
                $messageTemplate->name = $name;
                $messageTemplate->channel = $channel;
                $messageTemplate->type = $type;
                $messageTemplate->subject = $statusLabel;
                $messageTemplate->is_active = false;

                // Custom message based on the status
                if ($status === 'rescheduled_order') {
                    $messageTemplate->message = "Hi [customer_first_name] [customer_last_name], quick update - your order has been rescheduled based on your request to shift the date for your delivery. \n\n We will do our BEST to deliver on the rescheduled date. Remember, Delay is DANGEROUS \n\n Please make sure to be available on that day to receive it. \n\nIn the meantime, do you have any questions? Need help? \n\n Reply here, and we'll take care of it fast! \n\n[staff_name], Customer Service";
                } elseif ($status === 'order_in_transit') {
                    $messageTemplate->message = "Hello [customer_first_name] [customer_last_name]! Exciting news - your order is on the way, and the rider will deliver it to you today! Kindly pay to the delivery agent and collect your order.  \n\n It's currently in transit, and we can't wait for you to start enjoying the result you've always wanted. \n\nPlease be with your phone so that when he calls, you will respond. The delivery person has other customers to deliver to, so please don't keep the delivery person waiting. \n\nIf you need any help or have any questions while you wait, just reply to this message. \n\n Get ready - it'll be at your doorstep soon! \n\nThanks for your patronage. \n\n[staff_name], Customer Service";
                } else {
                    $messageTemplate->message = "Hello [customer_first_name] [customer_last_name], your order status has been updated to '{$statusLabel}'. For any questions or concerns, please reply to this message.\n\nThank you for choosing us!\n\n[staff_name], Customer Service";
                }



                $messageTemplate->save();
            }
        }
    }

    return "Message templates updated successfully!";
});


Route::get('/do-clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return redirect('/login')->with('success', 'cache, config, view, route cleared successfully');
});

Route::get('/reset-sitessss', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    //remember to change env to local, to avoid 'STDIN' constant error

    // Artisan::call('migrate:refresh', [
    //     '--seed' => true
    // ]);

    return redirect('/login')->with('success', 'Site reset successfully');

    // return view('page')->with('message', 'Site reset successfully');
})->name('reset-site');

// Route::get('/', function () {
//     return view('welcome');
// });
//symlink for sites that allow
// Route::get('/storage-link', function () {
//     $targetFolder = storage_path('app/pulbic');
//     $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
//     symlink($targetFolder, $linkFolder);
// });

Route::get('/storage-link', function () {

    Artisan::call('storage:link');
    // $targetFolder = storage_path('app/public'); // Corrected the typo here
    // $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';

    // // Check if the symbolic link already exists
    // if (!file_exists($linkFolder)) {
    //     symlink($targetFolder, $linkFolder); // Create the symlink
    //     return 'Symlink created successfully!';
    // } else {
    //     return 'Symlink already exists!';
    // }
});


Route::get('/notify', function () {

    //https://api.ebulksms.com:8080/sendsms?username=ralphsunny114@gmail.com&apikey=e1f6f5adc595fed1a13ec3593b2516a4ea8eb61d&sender=Ugo&dndsender=1&messagetext=helloralph&flash=0/1&recipients=2348066216874
    // $receiver = '+2349020127061';
    //$receiver = '+2348066216874';
    //$text = 'welcome from the platform';

    //http://api.textmebot.com/send.php?recipient=+2348066216874&apikey=9PsD5ecU3KL8&text=This%20is%20a%20test

    // $response = Http::get('http://api.textmebot.com/send.php?recipient='.$receiver.'&apikey=9PsD5ecU3KL8&text='.$text);

    //$response = Http::get('http://api.textmebot.com/send.php?recipient=+2348066216874&apikey=9PsD5ecU3KL8&text=This%20is%20a%20test&json=yes');

    //dd($response);
    $message = \App\Models\Message::first();
    $recipients = User::whereIn('id', ['1', '2'])->get();
    Notification::send($recipients, new sendUserMessageNotification($message));

    return 'ok';

    //return view('test');
    //Mail::to('test@email.com')->send(new TestMail());
    $user = User::find(1);
    $receivers = User::where('type', 'staff')->get();
    //$to = User::find(5);
    //$when = Carbon::now()->addMinutes(10);
    //$when = Carbon::now()->addSeconds(10);
    //  foreach ($receivers as $key => $value) {
    //     $value->notify((new TestNofication($user))->delay($when));
    //  }

    //Notification::send($user, new TestNofication($user)); //notify to multiple receivers like admins

    $invoiceData = [
        'user' => $user,
        'users' => User::all(),
        'first' => 'akon',
        'last' => 'ugo',
        'email' => 'akon@gmail.com',
    ];

    event(new TestEvent($invoiceData)); //sending mail to new user using TestMail in event

    return 'Ok';
});

Route::get('/test', [TestController::class, 'createCkeditor'])->name('test');
Route::post('/test', [TestController::class, 'createCkeditorPost'])->name('createCkeditorPost');



//login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
Route::get('/form-embedded/{unique_key}/{current_order_id?}/{stage?}', [FormBuilderController::class, 'formEmbedded'])->name('formEmbedded');

Route::get('/states/get_lgas_by_state', [State::class, 'get_lgas_by_state'])->name('state.lgas');
Route::get('/form-short-code/{id}/{current_order_id?}/{stage?}', [FormBuilderController::class, 'viewForm'])->name('newFormLink');

Route::get('/get-form/{unique_key}/{current_order_id?}/{stage?}', [FormBuilderController::class, 'viewForm'])->name('newFormLink');
// Route::get('/new-form-link/{unique_key}/{current_order_id?}/{stage?}', [FormBuilderController::class, 'newFormLink'])->name('newFormLink'); //like singleform for newFormBuilder 
Route::post('/new-form-link/{unique_key}/{current_order_id?}/{stage?}', [FormBuilderController::class, 'newFormLinkPost'])->name('newFormLinkPost'); //the post
Route::get('/ajax-save-new-form-link', [FormBuilderController::class, 'saveNewFormFromCustomer'])->name('saveNewFormFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-orderbump', [FormBuilderController::class, 'saveNewFormOrderBumpFromCustomer'])->name('saveNewFormOrderBumpFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-upsell', [FormBuilderController::class, 'saveNewFormUpSellFromCustomer'])->name('saveNewFormUpSellFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-downsell', [FormBuilderController::class, 'saveNewFormDownsellFromCustomer'])->name('saveNewFormDownsellFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-orderbump-refusal', [FormBuilderController::class, 'saveNewFormOrderBumpRefusalFromCustomer'])->name('saveNewFormOrderBumpRefusalFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-upsell-refusal', [FormBuilderController::class, 'saveNewFormUpSellRefusalFromCustomer'])->name('saveNewFormUpSellRefusalFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-downsell-refusal', [FormBuilderController::class, 'saveNewFormDownsellRefusalFromCustomer'])->name('saveNewFormDownsellRefusalFromCustomer'); //ajax
Route::get('/delete-form/{unique_key}', [FormBuilderController::class, 'deleteForm'])->name('deleteForm'); //deleteForm

//cart abandoned
Route::get('/cart-abandon-contact', [FormBuilderController::class, 'cartAbandonContact'])->name('cartAbandonContact'); //ajax
Route::get('/cart-abandon-delivery-duration', [FormBuilderController::class, 'cartAbandonDeliveryDuration'])->name('cartAbandonDeliveryDuration'); //ajax
Route::get('/cart-abandon-package', [FormBuilderController::class, 'cartAbandonPackage'])->name('cartAbandonPackage'); //ajax

//thankyou
Route::get('/view-thankyou-templates/{unique_key}/{current_order_id?}', [ThankYouSettingController::class, 'singleThankYouTemplate'])->name('singleThankYouTemplate');
Route::get('/show-thankyou-templates/{unique_key}/{current_order_id?}', [ThankYouSettingController::class, 'showThankYouTemplate'])->name('showThankYouTemplate');
Route::get('/thankYou-embedded/{unique_key}/{current_order_id?}', [ThankYouSettingController::class, 'thankYouEmbedded'])->name('thankYouEmbedded');

//auth routes
Route::group(['middleware' => 'auth'], function () {



    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/today', [DashboardController::class, 'todayRecord'])->name('todayRecord');
    Route::get('/weekly', [DashboardController::class, 'weeklyRecord'])->name('weeklyRecord');
    Route::get('/monthly', [DashboardController::class, 'monthlyRecord'])->name('monthlyRecord');
    Route::get('/yearly', [DashboardController::class, 'yearlyRecord'])->name('yearlyRecord');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/account-profile', [AuthController::class, 'accountProfile'])->name('accountProfile'); //logged in user profile
    Route::get('/account-setting', [AuthController::class, 'accountSetting'])->name('accountSetting');
    Route::post('/edit-account-profile', [AuthController::class, 'editProfilePost'])->name('editProfilePost');
    Route::post('/edit-account-password', [AuthController::class, 'editPasswordPost'])->name('editPasswordPost');

    //Forms
    Route::get('/forms', [FormController::class, 'allForms'])->name('allForms');
    Route::get('/create-form', [FormController::class, 'addForm'])->name('addForm');
    Route::post('/create-form', [FormController::class, 'addFormPost'])->name('addFormPost');
    Route::get('/edit-form/{unique_id}', [FormController::class, 'editForm'])->name('editForm');
    Route::post('/edit-form/{unique_id}', [FormController::class, 'editFormPost'])->name('editFormPost');


    Route::get('/form/{unique_id}', [FormController::class, 'singleForm'])->name('singleForm'); //viewed by admin
    Route::get('/order-form/{unique_id}', [FormController::class, 'customerOrderForm'])->name('customerOrderForm'); //sent to customer
    Route::get('/complete-customer-order', [FormController::class, 'completeCustomerOrder'])->name('completeCustomerOrder'); //ajax
    Route::get('/product-orderbump-customer-order', [FormController::class, 'productOrderbumpCustomerOrder'])->name('productOrderbumpCustomerOrder'); //ajax
    Route::get('/product-upsell-customer-order', [FormController::class, 'productUpsellCustomerOrder'])->name('productUpsellCustomerOrder'); //ajax
    Route::get('/product-downsell-customer-order', [FormController::class, 'productDownsellCustomerOrder'])->name('productDownsellCustomerOrder'); //ajax
    Route::get('/product-only-customer-order', [FormController::class, 'productOnlyCustomerOrder'])->name('productOnlyCustomerOrder'); //ajax

    //formbuilder drag n drop
    Route::get('/form-builder', [FormBuilderController::class, 'formBuilder'])->name('formBuilder');
    Route::get('/form-builder-save', [FormBuilderController::class, 'formBuilderSave'])->name('formBuilderSave'); //ajax

    Route::get('/new-form-builder', [FormBuilderController::class, 'newFormBuilder'])->name('newFormBuilder');
    Route::post('/new-form-builder', [FormBuilderController::class, 'newFormBuilderPost'])->name('newFormBuilderPost');
    Route::get('/all-new-form-builder', [FormBuilderController::class, 'allNewFormBuilders'])->name('allNewFormBuilders');
    Route::post('/assign-staff-to-form', [FormBuilderController::class, 'assignStaffToForm'])->name('assignStaffToForm'); //ajax
    // Route::get('/edit-new-form-builder/{unique_key}', [FormBuilderController::class, 'editNewFormBuilder'])->name('editNewFormBuilder'); //edit by admin
    Route::get('/edit-new-form-builder/{unique_key}', [FormBuilderController::class, 'viewFormBuilder'])->name('editNewFormBuilder'); //edit by admin

    Route::post('/edit-new-form-builder/{unique_key}', [FormBuilderController::class, 'updateFormBuilderPost'])->name('editNewFormBuilderPost'); //edit by admin

    //duplicateForm, unique_id is id of form copied
    Route::get('/duplicate-form/{unique_id}', [FormBuilderController::class, 'duplicateForm'])->name('duplicateForm');
    Route::post('/duplicate-form/{unique_id}', [FormBuilderController::class, 'duplicateFormPost'])->name('duplicateFormPost');

    // Route::get('/form-embedded/{unique_key}/{current_order_id?}/{stage?}', [FormBuilderController::class, 'formEmbedded'])->name('formEmbedded');
    Route::get('/form-link/{unique_key}', [FormBuilderController::class, 'formLink'])->name('formLink'); //like singleform
    Route::post('/form-link/{unique_key}/{stage?}', [FormBuilderController::class, 'formLinkPost'])->name('formLinkPost');
    Route::post('/form-link-upsell/{unique_key}', [FormBuilderController::class, 'formLinkUpsellPost'])->name('formLinkUpsellPost');
    Route::post('/form-link-downsell/{unique_key}', [FormBuilderController::class, 'formLinkDownsellPost'])->name('formLinkDownsellPost');

    Route::get('/forms-list', [FormBuilderController::class, 'allFormBuilders'])->name('allFormBuilders');
    Route::post('/add-orderbump', [FormBuilderController::class, 'addOrderbumpToForm'])->name('addOrderbumpToForm');
    Route::post('/edit-orderbump', [FormBuilderController::class, 'editOrderbumpToForm'])->name('editOrderbumpToForm');
    Route::post('/add-upsell', [FormBuilderController::class, 'addUpsellToForm'])->name('addUpsellToForm');
    Route::post('/edit-upsell', [FormBuilderController::class, 'editUpsellToForm'])->name('editUpsellToForm');
    Route::post('/add-downsell', [FormBuilderController::class, 'addDownsellToForm'])->name('addDownsellToForm');
    Route::post('/edit-downsell', [FormBuilderController::class, 'editDownsellToForm'])->name('editDownsellToForm');
    Route::post('/add-thankyou', [FormBuilderController::class, 'addThankYouTemplateToForm'])->name('addThankYouTemplateToForm');
    Route::post('/edit-thankyou', [FormBuilderController::class, 'editThankYouTemplateToForm'])->name('editThankYouTemplateToForm');

    //cart abandoned
    Route::get('/carts', [OrderController::class, 'cartAbandon'])->name('cartAbandon');
    Route::get('/carts/{unique_key}', [OrderController::class, 'singleCartAbandon'])->name('singleCartAbandon');
    Route::get('/delete-carts/{unique_key}', [OrderController::class, 'deleteCartAbandon'])->name('deleteCartAbandon');
    // Route::get('/cart-abandon-contact', [FormBuilderController::class, 'cartAbandonContact'])->name('cartAbandonContact'); //ajax
    // Route::get('/cart-abandon-delivery-duration', [FormBuilderController::class, 'cartAbandonDeliveryDuration'])->name('cartAbandonDeliveryDuration'); //ajax
    // Route::get('/cart-abandon-package', [FormBuilderController::class, 'cartAbandonPackage'])->name('cartAbandonPackage'); //ajax

    //Orders
    Route::get('/orders/{status?}', [OrderController::class, 'allOrders'])->name('allOrders');
    Route::get('/update-order-status/{unique_key}/{status}', [OrderController::class, 'updateOrderStatus'])->name('updateOrderStatus');
    Route::get('/create-order', [OrderController::class, 'addOrder'])->name('addOrder');
    Route::post('/create-order', [OrderController::class, 'addOrderPost'])->name('addOrderPost');
    Route::get('/view-order/{unique_key}', [OrderController::class, 'singleOrder'])->name('singleOrder'); //viewed by admin
    Route::get('/edit-order/{unique_key}', [OrderController::class, 'editOrder'])->name('editOrder');
    Route::post('/edit-order/{unique_key}', [OrderController::class, 'editOrderPost'])->name('editOrderPost');
    Route::post('/assign-agent-to-order', [OrderController::class, 'assignAgentToOrder'])->name('assignAgentToOrder');
    Route::post('/assign-staff-to-order', [OrderController::class, 'assignStaffToOrder'])->name('assignStaffToOrder');
    Route::post('/update-order-date-status', [OrderController::class, 'updateOrderDateStatus'])->name('updateOrderDateStatus');
    Route::get('/delete-order/{unique_key}', [OrderController::class, 'deleteOrder'])->name('deleteOrder');
    Route::get('/delete-all-orders', [OrderController::class, 'deleteAllOrders'])->name('deleteAllOrders');
    Route::post('/add-extra-cost-to-order', [OrderController::class, 'extraCostToOrder'])->name('extraCostToOrder');

    //register any user, customer or agent, staff, etc
    //staff
    Route::get('/employees', [EmployeeController::class, 'allStaff'])->name('allStaff');
    Route::get('/create-employee', [EmployeeController::class, 'addStaff'])->name('addStaff');
    Route::post('/create-employee', [EmployeeController::class, 'addStaffPost'])->name('addStaffPost');
    Route::get('/view-employee/{unique_key}', [EmployeeController::class, 'singleStaff'])->name('singleStaff');
    Route::get('/edit-employee/{unique_key}', [EmployeeController::class, 'editStaff'])->name('editStaff');
    Route::post('/edit-employee/{unique_key}', [EmployeeController::class, 'editStaffPost'])->name('editStaffPost');
    Route::get('/delete-employee/{unique_key}', [EmployeeController::class, 'deleteStaff'])->name('deleteStaff');

    //agent
    Route::get('/agents', [AuthController::class, 'allAgent'])->name('allAgent');
    Route::get('/create-agent', [AuthController::class, 'addAgent'])->name('addAgent');
    Route::post('/create-agent', [AuthController::class, 'addAgentPost'])->name('addAgentPost');
    Route::get('/view-agent/{unique_key}', [AuthController::class, 'singleAgent'])->name('singleAgent');
    Route::get('/edit-agent/{unique_key}', [AuthController::class, 'editAgent'])->name('editAgent');
    Route::post('/edit-agent/{unique_key}', [AuthController::class, 'editAgentPost'])->name('editAgentPost');

    //customers
    Route::get('/customers', [CustomerController::class, 'allCustomer'])->name('allCustomer');
    Route::get('/create-customer', [CustomerController::class, 'addCustomer'])->name('addCustomer');
    Route::post('/create-customer', [CustomerController::class, 'addCustomerPost'])->name('addCustomerPost');
    Route::get('/view-customer/{unique_key}', [CustomerController::class, 'singleCustomer'])->name('singleCustomer');
    Route::get('/edit-customer/{unique_key}', [CustomerController::class, 'editCustomer'])->name('editCustomer');
    Route::post('/edit-customer/{unique_key}', [CustomerController::class, 'editCustomerPost'])->name('editCustomerPost');
    Route::get('/delete-customer/{unique_key}', [CustomerController::class, 'deleteCustomer'])->name('deleteCustomer');
    Route::get('/single-customer-sales/{unique_key}', [CustomerController::class, 'singleCustomerSales'])->name('singleCustomerSales');
    Route::get('/delete-all-customers', [CustomerController::class, 'deleteAllCustomers'])->name('deleteAllCustomers'); //ajax

    Route::get('/ajax-create-customer', [CustomerController::class, 'addCustomerAjax'])->name('addCustomerAjax');

    //product category
    Route::get('/categories', [CategoryController::class, 'allCategory'])->name('allCategory');
    Route::get('/create-category', [CategoryController::class, 'addCategory'])->name('addCategory');
    Route::post('/create-category', [CategoryController::class, 'addCategoryPost'])->name('addCategoryPost');
    Route::get('/view-category/{unique_key}', [CategoryController::class, 'singleCategory'])->name('singleCategory');
    Route::get('/edit-category/{unique_key}', [CategoryController::class, 'editCategory'])->name('editCategory');
    Route::post('/edit-category/{unique_key?}', [CategoryController::class, 'editCategoryPost'])->name('editCategoryPost');
    Route::get('/delete-category/{unique_key}', [CategoryController::class, 'deleteCategory'])->name('deleteCategory');
    Route::get('/category-products/{unique_key}', [CategoryController::class, 'productsByCategory'])->name('productsByCategory');
    Route::get('/category-sales/{unique_key}', [CategoryController::class, 'salesByCategory'])->name('salesByCategory');
    Route::get('/category-purchases/{unique_key}', [CategoryController::class, 'purchasesByCategory'])->name('purchasesByCategory');
    Route::get('/category-customers/{unique_key}', [CategoryController::class, 'customersByCategory'])->name('customersByCategory'); //customersByCategory

    Route::get('/ajax-send-customer-mail', [CategoryController::class, 'ajaxSendCustomerMail'])->name('ajaxSendCustomerMail'); //ajaxSendCustomerMail
    Route::get('/ajax-create-product-category', [CategoryController::class, 'createProductCategoryAjax'])->name('createProductCategoryAjax'); //ajax

    //Products
    Route::get('/products', [ProductController::class, 'allProducts'])->name('allProducts');
    Route::get('/create-product', [ProductController::class, 'addProduct'])->name('addProduct');
    Route::post('/create-product', [ProductController::class, 'addProductPost'])->name('addProductPost');
    Route::get('/view-product/{unique_key}', [ProductController::class, 'singleProduct'])->name('singleProduct');
    Route::get('/edit-product/{unique_key}', [ProductController::class, 'editProduct'])->name('editProduct');
    Route::post('/edit-product/{unique_key}', [ProductController::class, 'editProductPost'])->name('editProductPost');
    Route::get('/delete-product/{unique_key}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');

    //combo product
    Route::get('/products-combo', [ProductComboController::class, 'allCombo'])->name('allCombo');
    Route::get('/create-product-combo', [ProductComboController::class, 'addCombo'])->name('addCombo');
    Route::post('/create-product-combo', [ProductComboController::class, 'addComboPost'])->name('addComboPost');
    Route::get('/view-product-combo/{unique_key}', [ProductComboController::class, 'singleCombo'])->name('singleCombo');
    Route::get('/edit-product-combo/{unique_key}', [ProductComboController::class, 'editCombo'])->name('editCombo');
    Route::post('/edit-product-combo/{unique_key}', [ProductComboController::class, 'editComboPost'])->name('editComboPost');

    //Warehouses
    Route::get('/warehouses', [WareHouseController::class, 'allWarehouse'])->name('allWarehouse');
    Route::get('/create-warehouse', [WareHouseController::class, 'addWarehouse'])->name('addWarehouse');
    Route::post('/create-warehouse', [WareHouseController::class, 'addWarehousePost'])->name('addWarehousePost');
    Route::get('/view-warehouse/{unique_key}', [WareHouseController::class, 'singleWarehouse'])->name('singleWarehouse');
    Route::get('/edit-warehouse/{unique_key}', [WareHouseController::class, 'editWarehouse'])->name('editWarehouse');
    Route::post('/edit-warehouse/{unique_key}', [WareHouseController::class, 'editWarehousePost'])->name('editWarehousePost');
    Route::get('/ajax-create-warehouse', [WareHouseController::class, 'addWarehouseAjax'])->name('addWarehouseAjax'); //ajax

    //transfers
    Route::post('/product-transfer-setup', [ProductTransferController::class, 'productTransferSetupPost'])->name('productTransferSetupPost');
    Route::get('/product-transfer-setup/{from_warehouse_unique_key}/{to_warehouse_unique_key}', [ProductTransferController::class, 'productTransferSetup'])->name('productTransferSetup');
    Route::post('/product-transfer/{from_warehouse_unique_key}/{to_warehouse_unique_key}', [ProductTransferController::class, 'productTransferPost'])->name('productTransferPost');
    Route::get('/all-product-transfers', [ProductTransferController::class, 'allProductTransfers'])->name('allProductTransfers');

    //supplier
    Route::get('/suppliers', [SupplierController::class, 'allSupplier'])->name('allSupplier');
    Route::get('/create-supplier', [SupplierController::class, 'addSupplier'])->name('addSupplier');
    Route::post('/create-supplier', [SupplierController::class, 'addSupplierPost'])->name('addSupplierPost');
    Route::get('/view-supplier/{unique_key}', [SupplierController::class, 'singleSupplier'])->name('singleSupplier');
    Route::get('/edit-supplier/{unique_key}', [SupplierController::class, 'editSupplier'])->name('editSupplier');
    Route::post('/edit-supplier/{unique_key}', [SupplierController::class, 'editSupplierPost'])->name('editSupplierPost');

    Route::get('/ajax-create-supplier', [SupplierController::class, 'addSupplierAjax'])->name('addSupplierAjax'); //ajax

    //purchase
    Route::get('/purchases', [PurchaseController::class, 'allPurchase'])->name('allPurchase');
    Route::get('/create-purchase', [PurchaseController::class, 'addPurchase'])->name('addPurchase');
    Route::post('/create-purchase', [PurchaseController::class, 'addPurchasePost'])->name('addPurchasePost');
    Route::get('/view-purchase/{unique_key}', [PurchaseController::class, 'singlePurchase'])->name('singlePurchase');
    Route::get('/edit-purchase/{unique_key}', [PurchaseController::class, 'editPurchase'])->name('editPurchase');
    Route::post('/edit-purchase/{unique_key}', [PurchaseController::class, 'editPurchasePost'])->name('editPurchasePost');
    Route::get('/delete-purchase/{unique_key}', [PurchaseController::class, 'deletePurchase'])->name('deletePurchase');

    //inventory management
    Route::get('/inventory-dashboard/{warehouse_unique_key?}', [InventoryController::class, 'inventoryDashboard'])->name('inventoryDashboard'); //inventoryDashboard
    Route::get('/inventory-dashboard-today/{warehouse_unique_key?}', [InventoryController::class, 'inventoryDashboardToday'])->name('inventoryDashboardToday'); //inventoryDashboardToday
    Route::get('/inventory-dashboard-weekly/{warehouse_unique_key?}', [InventoryController::class, 'inventoryDashboardWeekly'])->name('inventoryDashboardWeekly'); //inventoryDashboardWeekly
    Route::get('/inventory-dashboard-monthly/{warehouse_unique_key?}', [InventoryController::class, 'inventoryDashboardMonthly'])->name('inventoryDashboardMonthly'); //inventoryDashboardMonthly
    Route::get('/inventory-dashboard-yearly/{warehouse_unique_key?}', [InventoryController::class, 'inventoryDashboardYearly'])->name('inventoryDashboardYearly'); //inventoryDashboardYearly
    Route::get('/in-stock-products-warehouse', [InventoryController::class, 'inStockProductsByWarehouse'])->name('inStockProductsByWarehouse'); //inStockProductsByWarehouse
    Route::post('/in-stock-products-warehouse', [InventoryController::class, 'inStockProductsByWarehouseQuery'])->name('inStockProductsByWarehouseQuery'); //inStockProductsByWarehouseQuery
    Route::get('/in-stock-products-other-agents', [InventoryController::class, 'inStockProductsByOtherAgents'])->name('inStockProductsByOtherAgents'); //inStockProductsByOtherAgents
    Route::post('/in-stock-products-other-agents', [InventoryController::class, 'inStockProductsByOtherAgentsQuery'])->name('inStockProductsByOtherAgentsQuery'); //inStockProductsByOtherAgents
    Route::get('/all-products-inventory/{stock?}', [InventoryController::class, 'allProductInventory'])->name('allProductInventory');
    Route::get('/single-product-sales/{unique_key}', [InventoryController::class, 'singleProductSales'])->name('singleProductSales');
    Route::get('/single-product-purchases/{unique_key}', [InventoryController::class, 'singleProductPurchases'])->name('singleProductPurchases'); //singleProductPurchases

    //sale
    Route::get('/sales', [SaleController::class, 'allSale'])->name('allSale');
    Route::get('/create-sale', [SaleController::class, 'addSale'])->name('addSale');
    Route::post('/create-sale', [SaleController::class, 'addSalePost'])->name('addSalePost');
    Route::get('/view-sale/{unique_key}', [SaleController::class, 'singleSale'])->name('singleSale');
    Route::get('/edit-sale/{unique_key}', [SaleController::class, 'editSale'])->name('editSale');
    Route::post('/edit-sale/{unique_key}', [SaleController::class, 'editSalePost'])->name('editSalePost');

    //expense
    Route::get('/expenses', [ExpenseController::class, 'allExpense'])->name('allExpense');
    Route::get('/create-expense', [ExpenseController::class, 'addExpense'])->name('addExpense');
    Route::post('/create-expense', [ExpenseController::class, 'addExpensePost'])->name('addExpensePost');
    Route::get('/view-expense/{unique_key}', [ExpenseController::class, 'singleExpense'])->name('singleExpense');
    Route::get('/edit-expense/{unique_key}', [ExpenseController::class, 'editExpense'])->name('editExpense');
    Route::post('/edit-expense/{unique_key}', [ExpenseController::class, 'editExpensePost'])->name('editExpensePost');
    Route::get('/delete-expense/{unique_key}', [ExpenseController::class, 'deleteExpense'])->name('deleteExpense');
    Route::get('/delete-all-expenses', [ExpenseController::class, 'deleteAllExpenses'])->name('deleteAllExpenses'); //ajax

    //expense category
    Route::get('/expense-categories', [ExpenseController::class, 'allExpenseCategory'])->name('allExpenseCategory');
    Route::get('/create-expense-category', [ExpenseController::class, 'addExpenseCategory'])->name('addExpenseCategory');
    Route::post('/create-expense-category', [ExpenseController::class, 'addExpenseCategoryPost'])->name('addExpenseCategoryPost');
    Route::get('/ajax-create-expense-category', [ExpenseController::class, 'addExpenseCategoryAjaxPost'])->name('addExpenseCategoryAjaxPost'); //ajax, seen in addPurchase
    Route::get('/view-expense-category/{unique_key}', [ExpenseController::class, 'singleExpenseCategory'])->name('singleExpenseCategory');
    Route::get('/edit-expense-category/{unique_key}', [ExpenseController::class, 'editExpenseCategory'])->name('editExpenseCategory');
    Route::post('/edit-expense-category/{unique_key}', [ExpenseController::class, 'editExpenseCategoryPost'])->name('editExpenseCategoryPost');

    //account
    Route::get('/accounts', [AccountController::class, 'allAccount'])->name('allAccount');
    Route::get('/create-account', [AccountController::class, 'addAccount'])->name('addAccount');
    Route::post('/create-account', [AccountController::class, 'addAccountPost'])->name('addAccountPost');
    Route::get('/ajax-create-account', [AccountController::class, 'addAccountAjaxPost'])->name('addAccountAjaxPost'); //ajax post, seen in addPurchase
    Route::get('/view-account/{unique_key}', [AccountController::class, 'singleAccount'])->name('singleAccount');
    Route::get('/edit-account/{unique_key}', [AccountController::class, 'editAccount'])->name('editAccount');
    Route::post('/edit-account/{unique_key}', [AccountController::class, 'editAccountPost'])->name('editAccountPost');

    //moneyTransfer
    Route::get('/money-transfers', [AccountController::class, 'allMoneyTransfer'])->name('allMoneyTransfer');
    Route::post('/add-money-transfers', [AccountController::class, 'addMoneyTransferPost'])->name('addMoneyTransferPost');

    //balanceSheet
    Route::get('/balance-sheet', [AccountController::class, 'balanceSheet'])->name('balanceSheet');

    //allUpsellTemplates
    Route::get('/all-upsell-templates', [UpsellSettingController::class, 'allUpsellTemplates'])->name('allUpsellTemplates');
    Route::get('/view-upsell-template/{unique_key}', [UpsellSettingController::class, 'singleUpsellTemplate'])->name('singleUpsellTemplate');
    Route::get('/add-upsell-templates', [UpsellSettingController::class, 'addUpsellTemplate'])->name('addUpsellTemplate');
    Route::post('/add-upsell-templates', [UpsellSettingController::class, 'addUpsellTemplatePost'])->name('addUpsellTemplatePost');
    Route::get('/edit-upsell-templates/{unique_key}', [UpsellSettingController::class, 'editUpsellTemplate'])->name('editUpsellTemplate');
    Route::post('/edit-upsell-templates/{unique_key}', [UpsellSettingController::class, 'editUpsellTemplatePost'])->name('editUpsellTemplatePost');
    Route::get('/duplicate-upsell-templates/{unique_key}', [UpsellSettingController::class, 'duplicateUpsellTemplate'])->name('duplicateUpsellTemplate');

    //allDownsellTemplates 
    Route::get('/all-downsell-templates', [UpsellSettingController::class, 'allDownsellTemplates'])->name('allDownsellTemplates');
    Route::get('/view-downsell-template/{unique_key}', [UpsellSettingController::class, 'singleDownsellTemplate'])->name('singleDownsellTemplate');
    Route::get('/add-downsell-templates', [UpsellSettingController::class, 'addDownsellTemplate'])->name('addDownsellTemplate');
    Route::post('/add-downsell-templates', [UpsellSettingController::class, 'addDownsellTemplatePost'])->name('addDownsellTemplatePost');
    Route::get('/edit-downsell-templates/{unique_key}', [UpsellSettingController::class, 'editDownsellTemplate'])->name('editDownsellTemplate');
    Route::post('/edit-downsell-templates/{unique_key}', [UpsellSettingController::class, 'editDownsellTemplatePost'])->name('editDownsellTemplatePost');

    //thankYouTemplates
    Route::get('/all-thankyou-templates', [ThankYouSettingController::class, 'thankYouTemplates'])->name('thankYouTemplates');
    Route::get('/add-thankyou-templates', [ThankYouSettingController::class, 'addThankYouTemplate'])->name('addThankYouTemplate');
    Route::post('/add-thankyou-templates', [ThankYouSettingController::class, 'addThankYouTemplatePost'])->name('addThankYouTemplatePost');
    // Route::get('/view-thankyou-templates/{unique_key}/{current_order_id?}', [ThankYouSettingController::class, 'singleThankYouTemplate'])->name('singleThankYouTemplate');
    Route::get('/edit-thankyou-templates/{unique_key}', [ThankYouSettingController::class, 'editThankYouTemplate'])->name('editThankYouTemplate');
    Route::post('/edit-thankyou-templates/{unique_key}', [ThankYouSettingController::class, 'editThankYouTemplatePost'])->name('editThankYouTemplatePost');
    // Route::get('/thankYou-embedded/{unique_key}/{current_order_id?}', [ThankYouSettingController::class, 'thankYouEmbedded'])->name('thankYouEmbedded');

    //generalSetting
    Route::get('/general-setting', [GeneralSettingController::class, 'generalSetting'])->name('generalSetting');
    Route::post('/general-setting', [GeneralSettingController::class, 'generalSettingPost'])->name('generalSettingPost');

    //serlzo
    Route::get('/serlzo-setting', [SerlzoWhatsAppAccountController::class, 'index'])->name('serlzo.index');
    Route::post('/serlzo-setting/initialize', [SerlzoWhatsAppAccountController::class, 'initialize'])->name('serlzo.initialize');
    Route::post('/serlzo-setting/register', [SerlzoWhatsAppAccountController::class, 'register'])->name('serlzo.register');
    Route::post('/serlzo-setting/login', [SerlzoWhatsAppAccountController::class, 'login'])->name('serlzo.login');
    Route::get('/serlzo-setting/generate-qr-code/{token}', [SerlzoWhatsAppAccountController::class, 'generateQrCode'])->name('serlzo.qr');
    Route::get('/serlzo-setting/status/{token}', [SerlzoWhatsAppAccountController::class, 'checkStatus'])->name('serlzo.status');
    Route::delete('/serlzo-setting/{token}', [SerlzoWhatsAppAccountController::class, 'deleteDevice'])->name('serlzo.delete');

    //companyStructure
    Route::get('/company-structure', [GeneralSettingController::class, 'companyStructure'])->name('companyStructure');

    //faqs
    Route::get('/faqs', [GeneralSettingController::class, 'faq'])->name('faq');
    Route::post('/faqs', [GeneralSettingController::class, 'faqPost'])->name('faqPost');
    Route::get('/delete-faq/{unique_key}', [GeneralSettingController::class, 'deleteFaq'])->name('deleteFaq');

    //docs
    Route::get('docs-dashboard', [GeneralSettingController::class, 'dashboardDocs'])->name('dashboardDocs');

    //incomeStatement
    Route::get('/income-statement', [FinanceController::class, 'incomeStatement'])->name('incomeStatement');
    Route::post('/income-statement', [FinanceController::class, 'incomeStatementQuery'])->name('incomeStatementQuery');
    Route::get('/purchase-revenue', [FinanceController::class, 'purchaseRevenue'])->name('purchaseRevenue');
    Route::get('/sales-revenue', [FinanceController::class, 'saleRevenue'])->name('saleRevenue');

    //allRole
    Route::get('/all-roles', [RoleController::class, 'allRole'])->name('allRole');
    Route::get('/add-role', [RoleController::class, 'addRole'])->name('addRole');
    Route::post('/add-role', [RoleController::class, 'addRolePost'])->name('addRolePost');
    Route::get('/view-role/{unique_key}', [RoleController::class, 'singleRole'])->name('singleRole');
    Route::get('/edit-role/{unique_key}', [RoleController::class, 'editRole'])->name('editRole');
    Route::post('/edit-role/{unique_key}', [RoleController::class, 'editRolePost'])->name('editRolePost');
    Route::post('/assign-role-to-user', [RoleController::class, 'assignRoleToUserPost'])->name('assignRoleToUserPost');
    Route::get('/delete-role/{unique_key}', [RoleController::class, 'deleteRole'])->name('deleteRole');

    Route::get('/add-permission', [RoleController::class, 'addPermission'])->name('addPermission');
    Route::post('/add-permission', [RoleController::class, 'addPermissionPost'])->name('addPermissionPost');
    Route::get('/ajax-create-permission-main-menu', [RoleController::class, 'ajaxCreatePermissionMainMenu'])->name('ajaxCreatePermissionMainMenu');

    //allAttendance
    Route::get('/all-attendances', [AttendanceController::class, 'allAttendance'])->name('allAttendance');
    Route::get('/add-attendance', [AttendanceController::class, 'addAttendance'])->name('addAttendance');
    Route::post('/add-attendance', [AttendanceController::class, 'addAttendancePost'])->name('addAttendancePost');
    Route::get('/view-attendance/{unique_key}', [AttendanceController::class, 'singleAttendance'])->name('singleAttendance');
    Route::get('/edit-attendance/{unique_key}', [AttendanceController::class, 'editAttendance'])->name('editAttendance');
    Route::post('/edit-attendance/{unique_key}', [AttendanceController::class, 'editAttendancePost'])->name('editAttendancePost');

    //allPayroll
    Route::get('/all-payrolls', [PayrollController::class, 'allPayroll'])->name('allPayroll');
    Route::get('/add-payroll', [PayrollController::class, 'addPayroll'])->name('addPayroll');
    Route::post('/add-payroll', [PayrollController::class, 'addPayrollPost'])->name('addPayrollPost');
    Route::get('/view-payroll/{unique_key}', [PayrollController::class, 'singlePayroll'])->name('singlePayroll');
    Route::get('/edit-payroll/{unique_key}', [PayrollController::class, 'editPayroll'])->name('editPayroll');
    Route::post('/edit-payroll/{unique_key}', [PayrollController::class, 'editPayrollPost'])->name('editPayrollPost');
    Route::get('/delete-payroll/{unique_key}', [PayrollController::class, 'deletePayroll'])->name('deletePayroll');

    //sms messages
    Route::get('/compose-sms-message', [MessageController::class, 'composeSmsMessage'])->name('composeSmsMessage');
    Route::post('/compose-sms-message', [MessageController::class, 'composeSmsMessagePost'])->name('composeSmsMessagePost');
    Route::get('/sent-sms-messages', [MessageController::class, 'sentSmsMessage'])->name('sentSmsMessage'); //list

    //email messages
    Route::get('/compose-email-message', [MessageController::class, 'composeEmailMessage'])->name('composeEmailMessage');
    Route::post('/compose-email-message', [MessageController::class, 'composeEmailMessagePost'])->name('composeEmailMessagePost');
    Route::post('/send-customer-mail', [MessageController::class, 'sendCustomerMail'])->name('sendCustomerMail'); //sendCustomerMail
    Route::post('/send-employee-mail', [MessageController::class, 'sendEmployeeMail'])->name('sendEmployeeMail'); //sendEmployeeMail
    Route::post('/send-agent-mail', [MessageController::class, 'sendAgentMail'])->name('sendAgentMail'); //sendAgentMail
    Route::get('/sent-whatsapp-messages/{source?}', [MessageController::class, 'sentWhatsappMessage'])->name('sentWhatsappMessage'); //sentWhatsappMessage
    Route::get('/sent-email-messages', [MessageController::class, 'sentEmailMessage'])->name('sentEmailMessage'); //sentEmailMessage
    Route::post('/sent-email-messages', [MessageController::class, 'sentEmailMessageUpdate'])->name('sentEmailMessageUpdate'); //sentEmailMessageUpdate
    Route::post('/sent-sms-messages', [MessageController::class, 'sentSmsMessageUpdate'])->name('sentSmsMessageUpdate'); //sentSmsMessageUpdate
    Route::post('/sent-whatsapp-messages', [MessageController::class, 'sentWhatsappMessageUpdate'])->name('sentWhatsappMessageUpdate'); //sentEmailMessageUpdate

    Route::get('/view-message-templates/{channel}', [MessageController::class, 'viewTemplates'])->name('viewMessageTemplates'); //viewTemplate
    Route::post('/update-message-template/{template}', [MessageController::class, 'updateTemplate'])->name('updateMessageTemplate'); //updateTemplate
    Route::get('/update-template-status/{template}/{status}', [MessageController::class, 'updateTemplateStatus'])->name('updateTemplateStatus'); //updateTemplate

    //whatsapp
    Route::post('/send-agent-whatsapp', [MessageController::class, 'sendAgentWhatsapp'])->name('sendAgentWhatsapp'); //sendAgentWhatsapp
    Route::post('/send-employee-whatsapp', [MessageController::class, 'sendEmployeeWhatsapp'])->name('sendEmployeeWhatsapp'); //sendEmployeeWhatsapp
    Route::post('/send-customer-whatsapp', [MessageController::class, 'sendCustomerWhatsapp'])->name('sendCustomerWhatsapp'); //sendCustomerWhatsapp

    Route::get('/mail-customers-by-category/{selectedCategory}/{recipients?}', [MessageController::class, 'mailCustomersByCategory'])->name('mailCustomersByCategory'); //mailCustomersByCategory
    Route::post('/mail-customers-by-category/{selectedCategory}/{recipients?}', [MessageController::class, 'mailCustomersByCategoryPost'])->name('mailCustomersByCategoryPost'); //mailCustomersByCategoryPost

    Route::get('/send-sms/{phone?}', [MessageController::class, 'sendVCode'])->name('sendVCode'); //list

    //reports
    Route::get('/reports-profit-and-loss/{start_date?}/{end_date?}/{location?}', [ReportController::class, 'profitLossReport'])->name('profitLossReport'); //profitLossReport
    Route::get('/reports-profit-and-loss-ajax', [ReportController::class, 'profitLossReportAjax'])->name('profitLossReportAjax'); //profitLossReportAjax
    Route::get('/reports-sales-rep/{staff_unique_key?}/{start_date?}/{end_date?}/{location?}', [ReportController::class, 'salesRepReport'])->name('salesRepReport'); //salesRepReport
    Route::get('/reports-sales-rep-ajax', [ReportController::class, 'salesRepReportAjax'])->name('salesRepReportAjax'); //salesRepReportAjax

    Route::get('/reports-activity-logs', [ReportController::class, 'activityLogReport'])->name('activityLogReport'); //activityLogReport

    Route::get('/reports-product', [ReportController::class, 'productReport'])->name('productReport'); //productReport
    Route::post('/reports-product', [ReportController::class, 'productReportQuery'])->name('productReportQuery'); //productReportQuery

    Route::get('/reports-sale', [ReportController::class, 'saleReport'])->name('saleReport'); //saleReport
    Route::post('/reports-sale', [ReportController::class, 'saleReportQuery'])->name('saleReportQuery'); //saleReportQuery

    //purchaseReport
    Route::get('/reports-purchase', [ReportController::class, 'purchaseReport'])->name('purchaseReport'); //purchaseReport
    Route::post('/reports-purchase', [ReportController::class, 'purchaseReportQuery'])->name('purchaseReportQuery'); //purchaseReportQuery

    //customerReport
    Route::get('/reports-customer/{type?}', [ReportController::class, 'customerReport'])->name('customerReport'); //customerReport
    Route::post('/reports-customer/{type?}', [ReportController::class, 'customerReportQuery'])->name('customerReportQuery'); //customerReportQuery

    //supplierReport
    Route::get('/reports-supplier/{type?}', [ReportController::class, 'supplierReport'])->name('supplierReport'); //supplierReport
    Route::post('/reports-supplier/{type?}', [ReportController::class, 'supplierReportQuery'])->name('supplierReportQuery'); //supplierReportQuery

    //staffReport
    Route::get('/reports-staff/{type?}', [ReportController::class, 'staffReport'])->name('staffReport'); //staffReport
    Route::post('/reports-staff/{type?}', [ReportController::class, 'staffReportQuery'])->name('staffReportQuery'); //staffReportQuery

    //imports
    Route::post('/persons-import', [ImportController::class, 'personsImport'])->name('personsImport'); //personsImport //avoid on live
    Route::post('/users-import', [ImportController::class, 'usersImport'])->name('usersImport'); //personsImport //avoid on live
    Route::post('/employees-import', [ImportController::class, 'employeesImport'])->name('employeesImport'); //employeesImport
    Route::post('/suppliers-import', [ImportController::class, 'suppliersImport'])->name('suppliersImport'); //employeesImport
    Route::post('/products-import', [ImportController::class, 'productsImport'])->name('productsImport'); //productsImport
    Route::post('/warehouses-import', [ImportController::class, 'warehousesImport'])->name('warehousesImport'); //warehousesImport
    Route::post('/agents-import', [ImportController::class, 'agentsImport'])->name('agentsImport'); //agentsImport
    Route::post('/customers-import', [ImportController::class, 'customersImport'])->name('customersImport'); //customersImport

    //exports
    Route::get('/users-export', [ExportController::class, 'usersExport'])->name('usersExport'); //usersExport //avoid on live
    Route::get('/users-export-sample', [ExportController::class, 'sampleUsersExport'])->name('sampleUsersExport'); //sampleUsersExport
    Route::get('/employees-export', [ExportController::class, 'employeesExport'])->name('employeesExport'); //employeesExport

    //suppliers exports
    Route::get('/suppliers-export-sample', [ExportController::class, 'suppliersSampleExport'])->name('suppliersSampleExport'); //suppliersSampleExport
    Route::get('/suppliers-export', [ExportController::class, 'suppliersExport'])->name('suppliersExport'); //suppliersExport

    //wareHouses export
    Route::get('/warehouses-export-sample', [ExportController::class, 'warehousesSampleExport'])->name('warehousesSampleExport'); //warehousesSampleExport
    Route::get('/warehouses-export', [ExportController::class, 'warehousesExport'])->name('warehousesExport'); //warehousesExport

    //products export
    Route::get('/products-export-sample', [ExportController::class, 'productsSampleExport'])->name('productsSampleExport'); //ProductsSampleExport
    Route::get('/products-export', [ExportController::class, 'productsExport'])->name('productsExport'); //productsExport

    //purchase export
    Route::get('/purchases-export', [ExportController::class, 'purchasesExport'])->name('purchasesExport'); //purchasesExport

    //sale export
    Route::get('/sales-export', [ExportController::class, 'salesExport'])->name('salesExport'); //salesExport

    //agents export
    Route::get('/agents-export-sample', [ExportController::class, 'agentsSampleExport'])->name('agentsSampleExport'); //agentsSampleExport
    Route::get('/agents-export', [ExportController::class, 'agentsExport'])->name('agentsExport'); //agentsExport

    //customers export
    Route::get('/customers-export-sample', [ExportController::class, 'customersSampleExport'])->name('customersSampleExport'); //customersSampleExport
    Route::get('/customers-export', [ExportController::class, 'customersExport'])->name('customersExport'); //customersExport

    //soundNotification
    Route::get('/sound-notification', [SoundNotificationController::class, 'soundNotification'])->name('soundNotification'); //soundNotification

    //task mgt
    //project
    Route::get('/taskmgr-overview', [ProjectController::class, 'overview'])->name('overview'); //project
    Route::get('/add-project', [ProjectController::class, 'addProject'])->name('addProject'); //addProject
    Route::post('/add-project', [ProjectController::class, 'addProjectPost'])->name('addProjectPost'); //addProjectPost
    Route::get('/all-projects', [ProjectController::class, 'allProject'])->name('allProject'); //allProject
    Route::get('/single-project/{unique_key}', [ProjectController::class, 'singleProject'])->name('singleProject'); //singleProject
    Route::post('/single-project/{unique_key}', [ProjectController::class, 'updateProjectPerformance'])->name('updateProjectPerformance'); //updateProjectPerformance
    Route::get('/edit-project/{unique_key}', [ProjectController::class, 'editProject'])->name('editProject'); //editProject
    Route::post('/edit-project/{unique_key}', [ProjectController::class, 'editProjectPost'])->name('editProjectPost'); //editProjectPost
    Route::get('/delete-project/{unique_key}', [ProjectController::class, 'deleteProject'])->name('deleteProject'); //deleteProject

    //task
    Route::get('/add-task', [TaskController::class, 'addTask'])->name('addTask'); //addTask
    Route::post('/add-task', [TaskController::class, 'addTaskPost'])->name('addTaskPost'); //addTaskPost
    Route::get('/all-tasks/{project_unique_key?}', [TaskController::class, 'allTask'])->name('allTask'); //allTask
    Route::get('/single-task/{unique_key}', [TaskController::class, 'singleTask'])->name('singleTask'); //singleTask
    Route::get('/edit-task/{unique_key}', [TaskController::class, 'editTask'])->name('editTask'); //editTask
    Route::post('/edit-task/{unique_key}', [TaskController::class, 'editTaskPost'])->name('editTaskPost'); //editTaskPost
    Route::post('/task-remark/{unique_key}', [TaskController::class, 'taskRemarkPost'])->name('taskRemarkPost'); //taskRemarkPost
    Route::get('/delete-task/{unique_key}', [TaskController::class, 'deleteTask'])->name('deleteTask'); //deleteTask

    Route::get('/update-task-status/{unique_key}/{status}', [TaskController::class, 'updateTaskStatus'])->name('updateTaskStatus'); //updateTaskStatus
    Route::get('/update-task-priority/{unique_key}/{priority}', [TaskController::class, 'updateTaskPriority'])->name('updateTaskPriority'); //updateTaskPriority

    //ajax-create-task-category
    Route::get('/ajax-create-task-category', [TaskController::class, 'ajaxCreateTaskCategory'])->name('ajaxCreateTaskCategory'); //ajaxCreateTaskCategory
    Route::get('/all-task-category', [TaskController::class, 'allTaskCategory'])->name('allTaskCategory'); //allTaskCategory
    Route::post('/add-task-category', [TaskController::class, 'addTaskCategoryPost'])->name('addTaskCategoryPost'); //addTaskCategoryPost
    Route::post('/edit-task-category', [TaskController::class, 'editTaskCategoryPost'])->name('editTaskCategoryPost'); //editTaskCategoryPost
    Route::get('/delete-task-category/{unique_key}', [TaskController::class, 'deleteTaskCategory'])->name('deleteTaskCategory'); //deleteTaskCategory

    //staff-dashboarf
    Route::get('/staff-dashboard', [StaffDashboardController::class, 'staffDashboard'])->name('staffDashboard'); //staffDashboard
    Route::get('/staff-dashboard/{start_date?}/{end_date?}/{duration?}', [StaffDashboardController::class, 'staffDashboardDateFilter'])->name('staffDashboardDateFilter'); //staffDashboardFilter
    Route::post('/staff-dashboard/{start_date?}/{end_date?}/{duration?}', [StaffDashboardController::class, 'staffDashboardFilterPost'])->name('staffDashboardFilterPost'); //staffDashboardFilterPost

    Route::get('/staff-dashboard-today', [StaffDashboardController::class, 'staffTodayRecord'])->name('staffTodayRecord'); //staffTodayRecord
    Route::get('/staff-dashboard-yesterday', [StaffDashboardController::class, 'staffYesterdayRecord'])->name('staffYesterdayRecord'); //staffYesterdayRecord
    Route::get('/staff-dashboard-last7days', [StaffDashboardController::class, 'staffLast7DaysRecord'])->name('staffLast7DaysRecord'); //staffLast7DaysRecord
    Route::get('/staff-dashboard-last14days', [StaffDashboardController::class, 'staffLast14DaysRecord'])->name('staffLast14DaysRecord'); //staffLast14DaysRecord
    Route::get('/staff-dashboard-last30days', [StaffDashboardController::class, 'staffLast30DaysRecord'])->name('staffLast30DaysRecord'); //staffLast30DaysRecord
    Route::get('/staff-dashboard-weekly', [StaffDashboardController::class, 'staffWeeklyRecord'])->name('staffWeeklyRecord'); //staffWeeklyRecord
    Route::get('/staff-dashboard-lastweek', [StaffDashboardController::class, 'staffLastWeekRecord'])->name('staffLastWeekRecord'); //staffLastWeekRecord
    Route::get('/staff-dashboard-monthly', [StaffDashboardController::class, 'staffMonthlyRecord'])->name('staffMonthlyRecord'); //staffMonthlyRecord
    Route::get('/staff-dashboard-lastmonth', [StaffDashboardController::class, 'staffLastMonthRecord'])->name('staffLastMonthRecord'); //staffLastMonthRecord

    //Route::get('/nLargest', [TaskController::class, 'nLargest'])->name('nLargest'); //kLargest

    Route::get('/integration', [\App\Http\Controllers\SettingsController::class, 'integration'])->name('settings.integration');

    Route::get('/scripts', [ScriptController::class, 'scripts'])->name('scripts');
    Route::post('/scripts', [ScriptController::class, 'update'])->name('update.scripts');
});


Route::get('/link/form/get-view', [FormBuilderController::class, 'viewPublicForm'])->name('form.get');

Route::get('/link/order/{key}/invoice', [FormBuilderController::class, 'invoice'])->name('order.invoice');




//https://api.ebulksms.com:4433/sendsms?username=ralphsunny114@gmail.com&apikey=b7199affae645712ff475bf7cbb13f8a7b260de0&sender=ugo&messagetext=hey&flash=0&recipients=2348066216874