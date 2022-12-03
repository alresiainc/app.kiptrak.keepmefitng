<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Events\TestEvent;
use App\Notifications\TestNofication;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

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

// Route::get('/', function () {
//     return view('welcome');
// });
//symlink for sites that allow
// Route::get('/storage-link', function(){
//     $tagartFolder = storage_path('app/pulbic');
//     $linkFolder = $_SERVER['DOCUMENT_ROOT'] .'/storage';
//     symlink($tagartFolder,$linkFolder);
// });

// Route::get('/email', function () {
//     Mail::to('test@email.com')->send(new TestMail());
// }); 

Route::get('/notify', function () {

    // $receiver = '+2349020127061';
    $receiver = '+2348066216874';
    $text = 'welcome from the platform';

    //http://api.textmebot.com/send.php?recipient=+2348066216874&apikey=9PsD5ecU3KL8&text=This%20is%20a%20test

    // $response = Http::get('http://api.textmebot.com/send.php?recipient='.$receiver.'&apikey=9PsD5ecU3KL8&text='.$text);

    $response = Http::get('http://api.textmebot.com/send.php?recipient=+2348066216874&apikey=9PsD5ecU3KL8&text=This%20is%20a%20test&json=yes');

    dd($response);
    
    //return view('test');
    //Mail::to('test@email.com')->send(new TestMail());
     $user = User::find(1);
     $receivers = User::where('type','staff')->get();
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

Route::get('/test', [FormBuilderController::class, 'test'])->name('test');
Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

//Forms
Route::get('/forms', [FormController::class, 'allForms'])->name('allForms');
Route::get('/create-form', [FormController::class, 'addForm'])->name('addForm');
Route::post('/create-form', [FormController::class, 'addFormPost'])->name('addFormPost');
Route::get('/edit-form/{unique_id}', [FormController::class, 'editForm'])->name('editForm');
Route::get('/edit-form/{unique_id}', [FormController::class, 'editForm'])->name('editForm');
Route::post('/edit-form/{unique_id}', [FormController::class, 'editFormPost'])->name('editFormPost');

Route::get('/form/{unique_id}', [FormController::class, 'singleForm'])->name('singleForm'); //viewed by admin
Route::get('/order-form/{unique_id}', [FormController::class, 'customerOrderForm'])->name('customerOrderForm'); //sent to customer
Route::get('/complete-customer-order', [FormController::class, 'completeCustomerOrder'])->name('completeCustomerOrder'); //ajax
Route::get('/product-orderbump-customer-order', [FormController::class, 'productOrderbumpCustomerOrder'])->name('productOrderbumpCustomerOrder'); //ajax
Route::get('/product-upsell-customer-order', [FormController::class, 'productUpsellCustomerOrder'])->name('productUpsellCustomerOrder'); //ajax
Route::get('/product-only-customer-order', [FormController::class, 'productOnlyCustomerOrder'])->name('productOnlyCustomerOrder'); //ajax

//formbuilder drag n drop
Route::get('/form-builder', [FormBuilderController::class, 'formBuilder'])->name('formBuilder');
Route::get('/form-builder-save', [FormBuilderController::class, 'formBuilderSave'])->name('formBuilderSave'); //ajax

Route::get('/new-form-builder', [FormBuilderController::class, 'newFormBuilder'])->name('newFormBuilder');
Route::post('/new-form-builder', [FormBuilderController::class, 'newFormBuilderPost'])->name('newFormBuilderPost');
Route::get('/all-new-form-builder', [FormBuilderController::class, 'allNewFormBuilders'])->name('allNewFormBuilders');
Route::get('/edit-new-form-builder/{unique_key}', [FormBuilderController::class, 'editNewFormBuilder'])->name('editNewFormBuilder'); //edit by admin
Route::post('/edit-new-form-builder/{unique_key}', [FormBuilderController::class, 'editNewFormBuilderPost'])->name('editNewFormBuilderPost'); //edit by admin

Route::get('/form-embedded/{unique_key}', [FormBuilderController::class, 'formEmbedded'])->name('formEmbedded');
Route::get('/form-link/{unique_key}', [FormBuilderController::class, 'formLink'])->name('formLink'); //like singleform
Route::post('/form-link/{unique_key}/{stage?}', [FormBuilderController::class, 'formLinkPost'])->name('formLinkPost');
Route::post('/form-link-upsell/{unique_key}', [FormBuilderController::class, 'formLinkUpsellPost'])->name('formLinkUpsellPost');

Route::get('/new-form-link/{unique_key}/{stage?}', [FormBuilderController::class, 'newFormLink'])->name('newFormLink'); //like singleform for newFormBuilder 
Route::post('/new-form-link/{unique_key}/{stage?}', [FormBuilderController::class, 'newFormLinkPost'])->name('newFormLinkPost'); //the post
Route::get('/ajax-save-new-form-link', [FormBuilderController::class, 'saveNewFormFromCustomer'])->name('saveNewFormFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-orderbump', [FormBuilderController::class, 'saveNewFormOrderBumpFromCustomer'])->name('saveNewFormOrderBumpFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-upsell', [FormBuilderController::class, 'saveNewFormUpSellFromCustomer'])->name('saveNewFormUpSellFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-orderbump-refusal', [FormBuilderController::class, 'saveNewFormOrderBumpRefusalFromCustomer'])->name('saveNewFormOrderBumpRefusalFromCustomer'); //ajax
Route::get('/ajax-save-new-form-link-upsell-refusal', [FormBuilderController::class, 'saveNewFormUpSellRefusalFromCustomer'])->name('saveNewFormUpSellRefusalFromCustomer'); //ajax

Route::get('/forms-list', [FormBuilderController::class, 'allFormBuilders'])->name('allFormBuilders');
Route::post('/add-orderbump/{form_unique_key}', [FormBuilderController::class, 'addOrderbumpToForm'])->name('addOrderbumpToForm');
Route::post('/edit-orderbump/{form_unique_key}', [FormBuilderController::class, 'editOrderbumpToForm'])->name('editOrderbumpToForm');
Route::post('/add-upsell/{form_unique_key}', [FormBuilderController::class, 'addUpsellToForm'])->name('addUpsellToForm');
Route::post('/edit-upsell/{form_unique_key}', [FormBuilderController::class, 'editUpsellToForm'])->name('editUpsellToForm');

//cart abandoned
Route::get('/carts', [OrderController::class, 'cartAbandon'])->name('cartAbandon');
Route::get('/carts/{unique_key}', [OrderController::class, 'singleCartAbandon'])->name('singleCartAbandon');
Route::get('/cart-abandon-contact', [FormBuilderController::class, 'cartAbandonContact'])->name('cartAbandonContact'); //ajax
Route::get('/cart-abandon-package', [FormBuilderController::class, 'cartAbandonPackage'])->name('cartAbandonPackage'); //ajax


//Orders
Route::get('/orders', [OrderController::class, 'allOrders'])->name('allOrders');
Route::get('/create-order', [OrderController::class, 'addOrder'])->name('addOrder');
Route::post('/create-order', [OrderController::class, 'addOrderPost'])->name('addOrderPost');
Route::get('/view-order/{unique_key}', [OrderController::class, 'singleOrder'])->name('singleOrder'); //viewed by admin
Route::post('/assign-agent-to-order', [OrderController::class, 'assignAgentToOrder'])->name('assignAgentToOrder');

//register any user, customer or agent, staff, etc
//staff
Route::get('/staff', [AuthController::class, 'allStaff'])->name('allStaff');
Route::get('/create-staff', [AuthController::class, 'addStaff'])->name('addStaff');
Route::post('/create-staff', [AuthController::class, 'addStaffPost'])->name('addStaffPost');
Route::get('/view-staff/{unique_key}', [AuthController::class, 'singleStaff'])->name('singleStaff');
Route::get('/edit-staff/{unique_key}', [AuthController::class, 'editStaff'])->name('editStaff');
Route::post('/edit-staff/{unique_key}', [AuthController::class, 'editStaffPost'])->name('editStaffPost');

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

//Products
Route::get('/products', [ProductController::class, 'allProducts'])->name('allProducts');
Route::get('/create-product', [ProductController::class, 'addProduct'])->name('addProduct');
Route::post('/create-product', [ProductController::class, 'addProductPost'])->name('addProductPost');
Route::get('/view-product/{unique_key}', [ProductController::class, 'singleProduct'])->name('singleProduct');
Route::get('/edit-product/{unique_key}', [ProductController::class, 'editProduct'])->name('editProduct');
Route::post('/edit-product/{unique_key}', [ProductController::class, 'editProductPost'])->name('editProductPost');

//Warehouses
Route::get('/warehouses', [WareHouseController::class, 'allWarehouse'])->name('allWarehouse');
Route::get('/create-warehouse', [WareHouseController::class, 'addWarehouse'])->name('addWarehouse');
Route::post('/create-warehouse', [WareHouseController::class, 'addWarehousePost'])->name('addWarehousePost');
Route::get('/view-warehouse/{unique_key}', [WareHouseController::class, 'singleWarehouse'])->name('singleWarehouse');
Route::get('/edit-warehouse/{unique_key}', [WareHouseController::class, 'editWarehouse'])->name('editWarehouse');
Route::post('/edit-warehouse/{unique_key}', [WareHouseController::class, 'editWarehousePost'])->name('editWarehousePost');

//supplier
Route::get('/suppliers', [SupplierController::class, 'allSupplier'])->name('allSupplier');
Route::get('/create-supplier', [SupplierController::class, 'addSupplier'])->name('addSupplier');
Route::post('/create-supplier', [SupplierController::class, 'addSupplierPost'])->name('addSupplierPost');
Route::get('/view-supplier/{unique_key}', [SupplierController::class, 'singleSupplier'])->name('singleSupplier');
Route::get('/edit-supplier/{unique_key}', [SupplierController::class, 'editSupplier'])->name('editSupplier');
Route::post('/edit-supplier/{unique_key}', [SupplierController::class, 'editSupplierPost'])->name('editSupplierPost');

//purchase
Route::get('/purchases', [PurchaseController::class, 'allPurchase'])->name('allPurchase');
Route::get('/create-purchase', [PurchaseController::class, 'addPurchase'])->name('addPurchase');
Route::post('/create-purchase', [PurchaseController::class, 'addPurchasePost'])->name('addPurchasePost');
Route::get('/view-purchase/{unique_key}', [PurchaseController::class, 'singlePurchase'])->name('singlePurchase');
Route::get('/edit-purchase/{unique_key}', [PurchaseController::class, 'editPurchase'])->name('editPurchase');
Route::post('/edit-purchase/{unique_key}', [PurchaseController::class, 'editPurchasePost'])->name('editPurchasePost');

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



