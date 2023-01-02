<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Events\TestEvent;
use App\Notifications\TestNofication;
use App\Notifications\sendUserMessageNotification;
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


//login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');

Route::group(['middleware' => 'auth'], function() {

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
Route::post('/assign-staff-to-order', [OrderController::class, 'assignStaffToOrder'])->name('assignStaffToOrder');

//register any user, customer or agent, staff, etc
//staff
Route::get('/employees', [EmployeeController::class, 'allStaff'])->name('allStaff');
Route::get('/create-employee', [EmployeeController::class, 'addStaff'])->name('addStaff');
Route::post('/create-employee', [EmployeeController::class, 'addStaffPost'])->name('addStaffPost');
Route::get('/view-employee/{unique_key}', [EmployeeController::class, 'singleStaff'])->name('singleStaff');
Route::get('/edit-employee/{unique_key}', [EmployeeController::class, 'editStaff'])->name('editStaff');
Route::post('/edit-employee/{unique_key}', [EmployeeController::class, 'editStaffPost'])->name('editStaffPost');

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
Route::get('/single-customer-sales/{unique_key}', [CustomerController::class, 'singleCustomerSales'])->name('singleCustomerSales');

//product category
Route::get('/categories', [CategoryController::class, 'allCategory'])->name('allCategory');
Route::get('/create-category', [CategoryController::class, 'addCategory'])->name('addCategory');
Route::post('/create-category', [CategoryController::class, 'addCategoryPost'])->name('addCategoryPost');
Route::get('/view-category/{unique_key}', [CategoryController::class, 'singleCategory'])->name('singleCategory');
Route::get('/edit-category/{unique_key}', [CategoryController::class, 'editCategory'])->name('editCategory');
Route::post('/edit-category/{unique_key}', [CategoryController::class, 'editCategoryPost'])->name('editCategoryPost');
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

//Warehouses
Route::get('/warehouses', [WareHouseController::class, 'allWarehouse'])->name('allWarehouse');
Route::get('/create-warehouse', [WareHouseController::class, 'addWarehouse'])->name('addWarehouse');
Route::post('/create-warehouse', [WareHouseController::class, 'addWarehousePost'])->name('addWarehousePost');
Route::get('/view-warehouse/{unique_key}', [WareHouseController::class, 'singleWarehouse'])->name('singleWarehouse');
Route::get('/edit-warehouse/{unique_key}', [WareHouseController::class, 'editWarehouse'])->name('editWarehouse');
Route::post('/edit-warehouse/{unique_key}', [WareHouseController::class, 'editWarehousePost'])->name('editWarehousePost');
Route::get('/ajax-create-warehouse', [WareHouseController::class, 'addWarehouseAjax'])->name('addWarehouseAjax'); //ajax

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

//inventory management
Route::get('/inventory-dashboard', [InventoryController::class, 'inventoryDashboard'])->name('inventoryDashboard'); //staffReport
Route::get('/in-stock-products-warehouse', [InventoryController::class, 'inStockProductsByWarehouse'])->name('inStockProductsByWarehouse'); //inStockProductsByWarehouse
Route::post('/in-stock-products-warehouse', [InventoryController::class, 'inStockProductsByWarehouseQuery'])->name('inStockProductsByWarehouseQuery'); //inStockProductsByWarehouseQuery
Route::get('/in-stock-products-other-agents', [InventoryController::class, 'inStockProductsByOtherAgents'])->name('inStockProductsByOtherAgents'); //inStockProductsByOtherAgents
Route::post('/in-stock-products-other-agents', [InventoryController::class, 'inStockProductsByOtherAgentsQuery'])->name('inStockProductsByOtherAgentsQuery'); //inStockProductsByOtherAgents
Route::get('/all-products-inventory', [InventoryController::class, 'allProductInventory'])->name('allProductInventory');
Route::get('/single-product-sales/{unique_key}', [InventoryController::class, 'singleProductSales'])->name('singleProductSales');
Route::get('/single-product-purchases/{unique_key}', [InventoryController::class, 'singleProductPurchases'])->name('singleProductPurchases');

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

//generalSetting
Route::get('/general-setting', [GeneralSettingController::class, 'generalSetting'])->name('generalSetting');
Route::post('/general-setting', [GeneralSettingController::class, 'generalSettingPost'])->name('generalSettingPost');

//generalSetting
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
Route::get('/sent-email-messages', [MessageController::class, 'sentEmailMessage'])->name('sentEmailMessage'); //list
Route::get('/mail-customers-by-category/{selectedCategory}/{recipients?}', [MessageController::class, 'mailCustomersByCategory'])->name('mailCustomersByCategory'); //mailCustomersByCategory
Route::post('/mail-customers-by-category/{selectedCategory}/{recipients?}', [MessageController::class, 'mailCustomersByCategoryPost'])->name('mailCustomersByCategoryPost'); //mailCustomersByCategoryPost

Route::get('/send-sms/{phone?}', [MessageController::class, 'sendVCode'])->name('sendVCode'); //list

//reports
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

//exports
Route::get('/users-export', [ExportController::class, 'usersExport'])->name('usersExport'); //usersExport //avoid on live
Route::get('/users-export-sample', [ExportController::class, 'sampleUsersExport'])->name('sampleUsersExport'); //sampleUsersExport
Route::get('/employees-export', [ExportController::class, 'employeesExport'])->name('employeesExport'); //employeesExport

//suppliers exports
Route::get('/suppliers-export-sample', [ExportController::class, 'suppliersSampleExport'])->name('suppliersSampleExport'); //suppliersSampleExport
Route::get('/suppliers-export', [ExportController::class, 'suppliersExport'])->name('suppliersExport'); //suppliersExport

});






//https://api.ebulksms.com:4433/sendsms?username=ralphsunny114@gmail.com&apikey=b7199affae645712ff475bf7cbb13f8a7b260de0&sender=ugo&messagetext=hey&flash=0&recipients=2348066216874




