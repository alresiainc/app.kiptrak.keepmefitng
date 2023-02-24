<?php
//$url = url()->current(); 
$routeName = \Route::currentRouteName();
//dd($routeName);
?>
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">
      
    <!---dashboard--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'dashboard-manager' || $user_role->permissions->contains('slug', 'view-dashboard')) ))
    <li class="nav-item">
      <a class="nav-link" data-bs-target="#dashboard-nav" href="/"
      @if($routeName=='dashboard')
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif><i class="bi bi-grid"></i><span>Dashboard</span></a>
      <ul id="dashboard-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav"></ul>
    </li>
    @endif

    <!---products--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'product-manager' || $user_role->permissions->contains('slug', 'view-product-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#products-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allProducts') || ($routeName=='addProduct') || ($routeName=='singleProduct') || ($routeName=='editProduct') || ($routeName=='allCategory'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-box"></i>
        <span>Products</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="products-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-product')) ))
        <li>
          <a href="{{ route('addProduct') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Product</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-product-list')) ))
        <li>
          <a href="{{ route('allProducts') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Products</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-product-category-list')) ))
        <li>
          <a href="{{ route('allCategory') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Categories</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---form-builder--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'form-builder-manager' || $user_role->permissions->contains('slug', 'view-form-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='formBuilder') || ($routeName=='newFormBuilder') || ($routeName=='allNewFormBuilders') || ($routeName=='editNewFormBuilder'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-textarea-resize"></i>
        <span>Form Builder</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-form')) ))
        <li>
          <a href="{{ route('newFormBuilder') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Build Form</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-form')) ))
        <li>
          <a href="{{ route('allNewFormBuilders') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Forms</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---orders--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'order-manager' || $user_role->permissions->contains('slug', 'view-order-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allOrders') || ($routeName=='addOrder') || ($routeName=='singleOrder'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-cart3"></i>
        <span>Orders</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="orders-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-order-list')) ))
        <li>
          <a href="{{ route('allOrders', 'new') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>New Orders</span></a>
        </li>
        <li>
          <a href="{{ route('allOrders', 'pending') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Pending Orders</span></a>
        </li>
        <li>
          <a href="{{ route('allOrders', 'delivered_not_remitted') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Delivered Not Remitted Orders</span></a>
        </li>
        <li>
          <a href="{{ route('allOrders', 'delivered_and_remitted') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Delivered & Remitted Orders</span></a>
        </li>
        <li>
          <a href="{{ route('allOrders', 'cancelled') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Cancelled Orders</span></a>
        </li>
        <li>
          <a href="{{ route('allOrders') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>All Orders</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-order-list')) ))
        <li>
          <a href="{{ route('cartAbandon') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Cart Abandoned</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---warehouse--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'warehouse-manager' || $user_role->permissions->contains('slug', 'view-warehouse-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#warehouse-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allWarehouse') || ($routeName=='addWarehouse') || ($routeName=='singleWarehouse') || ($routeName=='editWarehousePost'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-house"></i>
        <span>Warehouse</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="warehouse-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-warehouse')) ))
        <li>
          <a href="{{ route('addWarehouse') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Warehouse</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-warehouse-list')) ))
        <li>
          <a href="{{ route('allWarehouse') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Warehouse</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---purchases--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'purchase-manager' || $user_role->permissions->contains('slug', 'view-purchase-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#purchases-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allPurchase') || ($routeName=='addPurchase') || ($routeName=='singlePurchase') || ($routeName=='editPurchase'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-credit-card"></i>
        <span>Purchases</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="purchases-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-purchase-list')) ))
        <li>
          <a href="{{ route('addPurchase') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Purchase</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-purchase')) ))
        <li>
          <a href="{{ route('allPurchase') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Purchases</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---inventory--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'inventory-manager' || $user_role->permissions->contains('slug', 'view-inventory-dashboard')) ))
    <li class="nav-item">
      <a class="nav-link"href="{{ route('inventoryDashboard') }}"
      @if(($routeName=='inventoryDashboard') || ($routeName=='inStockProductsByWarehouse') || ($routeName=='inStockProductsByWarehouseQuery') || ($routeName=='inStockProductsByOtherAgents') || ($routeName=='allProductInventory') || ($routeName=='singleProductSales') || ($routeName=='singleProductPurchases'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-shop"></i>
        <span>Inventory Management</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      
    </li>
    @endif

    <!---sales--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'sale-manager' || $user_role->permissions->contains('slug', 'view-sale-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#sales-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allSale') || ($routeName=='addSale') || ($routeName=='singleSale') || ($routeName=='editSale'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-cart3"></i>
        <span>Sales</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="sales-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-sale')) ))
        <li>
          <a href="{{ route('addSale') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Sale</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-sale-list')) ))
        <li>
          <a href="{{ route('allSale') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Sales</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---expenses--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'expense-manager' || $user_role->permissions->contains('slug', 'view-expense-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#expenses-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allExpense') || ($routeName=='addExpense') || ($routeName=='singleExpense') || ($routeName=='editExpense') || ($routeName=='allExpenseCategory') || ($routeName=='addExpenseCategory') || ($routeName=='singleExpenseCategory') || ($routeName=='editExpenseCategory'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-credit-card-2-back"></i>
        <span>Expenses</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="expenses-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-expense')) ))
        <li>
          <a href="{{ route('addExpense') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Expense</span></a>
        </li>
        @endif
        
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-expense-list')) ))
        <li>
          <a href="{{ route('allExpense') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Expenses</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-expense-category-list')) ))
        <li>
          <a href="{{ route('allExpenseCategory') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Expense Category</span></a>
        </li>
        @endif

      </ul>
    </li>
    @endif

    <!---not-used accounts--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && $user_role->slug == 'account-manager' ))
    <li class="nav-item d-none">
      <a class="nav-link collapsed" data-bs-target="#accounts-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bank"></i>
        <span>Accounting</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="accounts-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addAccount') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Account</span></a>
        </li>
        <li>
          <a href="{{ route('allAccount') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Account</span></a>
        </li>
        <li>
          <a href="{{ route('allMoneyTransfer') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Money Transfer</span></a>
        </li>
        <li>
          <a href="{{ route('balanceSheet') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Balance Sheet</span></a>
        </li>
      </ul>
    </li>
    @endif

    <!---Human Resource Mgt--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($authUser->type !== 'agent') &&
    ($user_role->slug == 'human-resource-manager' || $user_role->permissions->contains('slug', 'view-hrm-menu')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#hrm-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allRole') || ($routeName=='addRole') || ($routeName=='singleRole') || ($routeName=='editRole') || ($routeName=='allAttendance') || ($routeName=='addAttendance') || ($routeName=='singleAttendance') || ($routeName=='editAttendance'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-people"></i>
        <span>HRM</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="hrm-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-expense-list')) ))
        <li>
          <a href="{{ route('allRole') }}"><i style="font-size: 100%!important;" class="bi bi-list"></i><span>Roles & Permissions</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-employee-list')) ))
        <li>
          <a href="{{ route('allStaff') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Employee</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-attendance-list')) ))
        <li>
          <a href="{{ route('allAttendance') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Attendance</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-payroll-list')) ))
        <li>
          <a href="{{ route('allPayroll') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Payroll</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---suppliers--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'supplier-manager' || $user_role->permissions->contains('slug', 'view-supplier-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#suppliers-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allSupplier') || ($routeName=='addSupplier') || ($routeName=='singleSupplier') || ($routeName=='editSupplier'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-truck"></i>
        <span>Suppliers</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="suppliers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-supplier')) ))
        <li>
          <a href="{{ route('addSupplier') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Supplier</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-supplier-list')) ))
        <li>
          <a href="{{ route('allSupplier') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Suppliers</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif
    
    <!---staff--->
    <li class="nav-item d-none">
      <a class="nav-link collapsed" data-bs-target="#staff-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i>
        <span>Staff</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="staff-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addStaff') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Staff</span></a>
        </li>
        <li>
          <a href="{{ route('allStaff') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Staff</span></a>
        </li>
      </ul>
    </li>
    
    <!---agents--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'agent-manager' || $user_role->permissions->contains('slug', 'view-agent-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#agents-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allAgent') || ($routeName=='addAgent') || ($routeName=='singleAgent') || ($routeName=='editAgent'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-person-workspace"></i>
        <span>Agents</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="agents-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-agent')) ))
        <li>
          <a href="{{ route('addAgent') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Agent</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-agent-list')) ))
        <li>
          <a href="{{ route('allAgent') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Agent</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---customers--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'customer-manager' || $user_role->permissions->contains('slug', 'view-customer-list')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#customers-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allCustomer') || ($routeName=='addCustomer') || ($routeName=='singleCustomer') || ($routeName=='editCustomer') || ($routeName=='singleCustomerSales'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-person-check"></i>
        <span>Customers</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="customers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'create-customer')) ))
        <li>
          <a href="{{ route('addCustomer') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Customer</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-customer-list')) ))
        <li>
          <a href="{{ route('allCustomer') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Customer</span></a>
        </li>
        @endif
      </ul>
    </li>
    @endif

    <!---Accounting--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'accounting-manager' || $user_role->permissions->contains('slug', 'view-accounting-menu')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#finance-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='allAccount') || ($routeName=='addAccount') || ($routeName=='singleAccount') || ($routeName=='editAccount') || ($routeName=='allMoneyTransfer') || ($routeName=='balanceSheet'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-safe-fill"></i>
        <span>Accounting System</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="finance-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-income-statement')) ))
        <li>
          <a href="{{ route('incomeStatement') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Income Statement</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-purchase-revenue')) ))
        <li>
          <a href="{{ route('purchaseRevenue') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Purchase Revenue</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-sale-revenue')) ))
        <li>
          <a href="{{ route('saleRevenue') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Sales Revenue</span></a>
        </li>
        @endif

        <li class="d-none">
          <a href="{{ route('allProducts') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Gross Profit</span></a>
        </li>
      </ul>
    </li>
    @endif

    <!---reports--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'report-manager' || $user_role->permissions->contains('slug', 'view-report-menu')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='productReport') || ($routeName=='saleReport') || ($routeName=='purchaseReport') || ($routeName=='customerReport') || ($routeName=='supplierReport') || ($routeName=='staffReport'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-megaphone"></i>
        <span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="reports-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-product-report')) ))
        <li>
          <a href="{{ route('profitLossReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Profit & Loss Report</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-product-report')) ))
        <li>
          <a href="{{ route('productReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Product Report</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-sale-report')) ))
        <li>
          <a href="{{ route('saleReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Sales Report</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-purchase-report')) ))
        <li>
          <a href="{{ route('purchaseReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Purchase Report</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-customer-report')) ))
        <li>
          <a href="{{ route('customerReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Customer Report</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-supplier-report')) ))
        <li>
          <a href="{{ route('supplierReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Supplier Report</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-staff-report')) ))
        <li>
          <a href="{{ route('staffReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Staff Report</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-staff-report')) ))
        <li>
          <a href="{{ route('activityLogReport') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Activity Log</span></a>
        </li>
        @endif
        
      </ul>
    </li>
    @endif

    <!---Task Manager--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'report-manager' || $user_role->permissions->contains('slug', 'view-report-menu')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#tasks-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='overview') || ($routeName=='addProject') || ($routeName=='allProject') || ($routeName=='singleProject') || ($routeName=='editProject')
      || ($routeName=='addTask') || ($routeName=='allTask') || ($routeName=='singleTask') || ($routeName=='editTask'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-briefcase"></i>
        <span>Task Manager</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="tasks-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-product-report')) ))
        <li>
          <a href="{{ route('overview') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Overview</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-sale-report')) ))
        <li>
          <a href="{{ route('addProject') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Add Project</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-purchase-report')) ))
        <li>
          <a href="{{ route('allProject') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Project List</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-customer-report')) ))
        <li>
          <a href="{{ route('addTask') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Add Task</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-supplier-report')) ))
        <li>
          <a href="{{ route('allTask') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Task List</span></a>
        </li>
        @endif

        @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && ($user_role->permissions->contains('slug', 'view-staff-report')) ))
        <li>
          <a href="{{ route('allTaskCategory') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Task Category List</span></a>
        </li>
        @endif
        
      </ul>
    </li>
    @endif

    <!---messaging--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'messaging-manager' || $user_role->permissions->contains('slug', 'view-message-menu')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#messaging-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='composeSmsMessage') || ($routeName=='sentSmsMessage') || ($routeName=='composeEmailMessage') || ($routeName=='sentEmailMessage') || ($routeName=='mailCustomersByCategory'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-chat-left"></i>
        <span>Messaging System</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="messaging-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('sentWhatsappMessage') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Whatsapp Messages</span></a>
        </li>
        
        <li>
          <a href="{{ route('sentEmailMessage') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Email Messages</span></a>
        </li>

        <li>
          <a href="{{ route('sentSmsMessage') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>SMS Messages</span></a>
        </li>
      </ul>
    </li>
    @endif

    <!---referral--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) && $user_role->slug == 'referral-manager' ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#referral-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-link"></i>
        <span>Referral System</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="referral-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addProduct') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>By Warehouse</span></a>
        </li>
        <li>
          <a href="{{ route('allProducts') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>By Agents</span></a>
        </li>
      </ul>
    </li>
    @endif

    <!---settings--->
    @if ( $authUser->isSuperAdmin || ( ($user_role !== false) &&
    ($user_role->slug == 'settings-manager' || $user_role->permissions->contains('slug', 'view-setting-menu')) ))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#setting-nav" data-bs-toggle="collapse" href="#"
      @if(($routeName=='generalSetting') || ($routeName=='allUpsellTemplates') || ($routeName=='addUpsellTemplate') || ($routeName=='editUpsellTemplate'))
      style="color: #198754; background: #affdd3; border-left: 3px solid #ffc107;" @endif>
        <i class="bi bi-gear-fill"></i>
        <span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="setting-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('generalSetting') }}"><i style="font-size: 100%!important;" class="bi bi-list"></i><span>Company Structure</span></a>
        </li>
        <li>
          <a href="{{ route('allUpsellTemplates') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Upsell Templates</span></a>
        </li>
        <li>
          <a href="{{ route('generalSetting') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>General Settings</span></a>
        </li>
        <li>
          <a href="{{ route('dashboardDocs') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Documentation</span></a>
        </li>
      </ul>
    </li>
    @endif

  </ul>

  </aside>

  