<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">
      
    <li class="nav-item"><a class="nav-link" data-bs-target="#dashboard-nav" href="/">
      <i class="bi bi-grid"></i><span>Dashboard</span></a>
      <ul id="dashboard-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav"></ul>
    </li>

    <!---products--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-list"></i>
        <span>Products</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="products-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addProduct') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Product</span></a>
        </li>
        <li>
          <a href="{{ route('allProducts') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Products</span></a>
        </li>
      </ul>
    </li>

    <!---form-builder--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-textarea-resize"></i>
        <span>Form Builder</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('newFormBuilder') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Build Form</span></a>
        </li>
        <li>
          <a href="{{ route('allNewFormBuilders') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Forms</span></a>
        </li>
      </ul>
    </li>

    <!---orders--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cart3"></i>
        <span>Orders</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="orders-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('allOrders') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Orders</span></a>
        </li>
        <li>
          <a href="{{ route('cartAbandon') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Cart Abandoned</span></a>
        </li>
      </ul>
    </li>

    <!---warehouse--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#warehouse-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-house"></i>
        <span>Warehouse</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="warehouse-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addWarehouse') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Warehouse</span></a>
        </li>
        <li>
          <a href="{{ route('allWarehouse') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>View Warehouse</span></a>
        </li>
      </ul>
    </li>

    <!---purchases--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#purchases-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-credit-card"></i>
        <span>Purchases</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="purchases-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addPurchase') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Purchase</span></a>
        </li>
        <li>
          <a href="{{ route('allPurchase') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Purchases</span></a>
        </li>
      </ul>
    </li>

    <!---sales--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#sales-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cart3"></i>
        <span>Sales</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="sales-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addSale') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Sale</span></a>
        </li>
        <li>
          <a href="{{ route('allSale') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Sales</span></a>
        </li>
      </ul>
    </li>

    <!---expenses--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#expenses-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-credit-card-2-back"></i>
        <span>Expenses</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="expenses-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addExpense') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Expense</span></a>
        </li>
        
        <li>
          <a href="{{ route('allExpense') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Expenses</span></a>
        </li>

        <li>
          <a href="{{ route('allExpenseCategory') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Expense Category</span></a>
        </li>
      </ul>
    </li>

    <!---accounts--->
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

    <!---Human Resource Mgt--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#hrm-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i>
        <span>HRM</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="hrm-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('allRole') }}"><i style="font-size: 100%!important;" class="bi bi-list"></i><span>Roles & Permissions</span></a>
        </li>
        <li>
          <a href="{{ route('allStaff') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Employee</span></a>
        </li>
        <li>
          <a href="{{ route('allAttendance') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Attendance</span></a>
        </li>
        <li>
          <a href="{{ route('allPayroll') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Payroll</span></a>
        </li>
      </ul>
    </li>

    <!---suppliers--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#suppliers-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-truck"></i>
        <span>Suppliers</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="suppliers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addSupplier') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Supplier</span></a>
        </li>
        <li>
          <a href="{{ route('allSupplier') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Suppliers</span></a>
        </li>
      </ul>
    </li>
    
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
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#agents-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-person-workspace"></i>
        <span>Agents</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="agents-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addAgent') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Agent</span></a>
        </li>
        <li>
          <a href="{{ route('allAgent') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Agent</span></a>
        </li>
      </ul>
    </li>

    <!---customers--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#customers-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-person-check"></i>
        <span>Customers</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="customers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addCustomer') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Add Customer</span></a>
        </li>
        <li>
          <a href="{{ route('allCustomer') }}"><i style="font-size: 100%!important;" class="bi bi-cart3"></i><span>View Customer</span></a>
        </li>
      </ul>
    </li>

    <!---finance--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#finance-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-safe-fill"></i>
        <span>Finance System</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="finance-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('purchaseRevenue') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Purchase Revenue</span></a>
        </li>
        <li>
          <a href="{{ route('saleRevenue') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Sales Revenue</span></a>
        </li>
        <li class="d-none">
          <a href="{{ route('allProducts') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>Gross Profit</span></a>
        </li>
      </ul>
    </li>

    <!---messaging--->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#messaging-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-chat-left"></i>
        <span>Messaging System</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="messaging-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('addProduct') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>By Warehouse</span></a>
        </li>
        <li>
          <a href="{{ route('allProducts') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>By Agents</span></a>
        </li>
      </ul>
    </li>

    <!---referral--->
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

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#setting-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-gear-fill"></i>
        <span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="setting-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('allUpsellTemplates') }}"><i style="font-size: 100%!important;" class="bi bi-plus"></i><span>Upsell Templates</span></a>
        </li>
        <li>
          <a href="{{ route('generalSetting') }}"><i style="font-size: 100%!important;" class="bi bi-card-list"></i><span>General Settings</span></a>
        </li>
      </ul>
    </li>

    

  </ul>

  </aside>