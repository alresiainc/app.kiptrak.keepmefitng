<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ url('/') }}" class="logo d-flex align-items-center">
      <img src="{{asset('/assets/img/logo.png')}}" alt="Kiptrak Logo" />
      <span class="d-none d-lg-block project-name"></span>
    </a>
    <!---for desktop--->
    <i id="desktop-hamburger" class="bi bi-list toggle-sidebar-btn d-none d-md-block"></i>
  </div>
  <!-- End Logo -->

  <div class="search-bar">
    <form class="search-form d-flex align-items-center rounded-pill" method="POST" action="#">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword" class="rounded-pill"/>
      <button type="submit" title="Search">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>
  <!-- End Search Bar -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#">
          <i class="bi bi-search"></i>
        </a>
      </li>
      <!-- End Search Icon-->

      <li class="nav-item me-3">
        <a href="{{ route('newFormBuilder') }}" class="btn btn-outline-primary rounded-pill d-flex">
          <i class="bi bi-basket"></i><span class="ms-1 d-none d-md-block">Form Builder</span>
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link nav-icon nav-link-expand" id="btnFullscreen" href="javascript:void(0)">
          <i class="bi bi-fullscreen"></i>
        </a>
      </li>
      <!-- End Notification Nav -->

      <!-- Start Notification Nav -->
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          @if(count($newOrders) > 0 || count($pendingOrders) > 0)
          <span id="sound_alarm" class="badge-number alarm_count">
            <span class="spinner-grow spinner-grow-sm bg-danger" role="status" aria-hidden="true"></span>  
          </span>
          @endif
        </a>
        <!-- End Messages Icon -->
        
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
          @if(count($newOrders) > 0)
          <li class="dropdown-header">
            You have <span class="alarm_count">{{ count($newOrders) }}</span> <span class="badge rounded-pill bg-danger">New</span> Orders
            <a href="{{ route('allOrders', 'new') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>
          @endif

          @if(count($pendingOrders) > 0)
          <li class="dropdown-header">
            You have <span class="alarm_count">{{ count($pendingOrders) }}</span> <span class="badge rounded-pill bg-success">Pending</span> Orders
            <a href="{{ route('allOrders', 'pending') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>
          @endif

          {{-- <div class="notes"></div> --}}
          @if(count($newOrders) > 0)
          @foreach ($newOrders as $newOrder)
          <li class="message-item">
            <div>
              <h4>Order: {{ $newOrder->orderCode($newOrder->id)}} &nbsp; <span>{{ ucFirst($newOrder->status) }}</span></h4>
              <h6>Customer: {{ $newOrder->customer->firstname.' '.$newOrder->customer->lastname}}</h6>
              <p class="text-dark">Due Date: {{ \Carbon\Carbon::parse($newOrder->expected_delivery_date)->format('D, jS M Y') }}</p>

              @if($newOrder->checkExpectedDeliveryDate() == 'Due')
              <p class="text-danger">Due</p>
              @else
              <p class="text-success">{{ $newOrder->checkExpectedDeliveryDate() }}</p>
              @endif
            </div>
          </li>
          @endforeach
          @endif

          @if(count($pendingOrders) > 0)
          @foreach ($pendingOrders as $pendingOrder)
          <li class="message-item">
            <div>
              <h4>Order: {{ $pendingOrder->orderCode($pendingOrder->id)}} &nbsp; <span>{{ ucFirst($pendingOrder->status) }}</span></h4>
              <h6>Customer: {{ $pendingOrder->customer->firstname.' '.$pendingOrder->customer->lastname}}</h6>
              <p class="text-dark">Due Date: {{ \Carbon\Carbon::parse($pendingOrder->expected_delivery_date)->format('D, jS M Y') }}</p>

              @if($pendingOrder->checkExpectedDeliveryDate() == 'Due')
              <p class="text-danger">Due</p>
              @else
              <p class="text-success">{{ $pendingOrder->checkExpectedDeliveryDate() }}</p>
              @endif
            </div>
          </li>
          @endforeach
          @endif

        </ul>
        <!-- End Messages Dropdown Items -->
      </li>
      <input type="hidden" class="sound_value" value="{{ count($newOrders) + count($pendingOrders) }}">
      <!-- End Notification Nav -->

      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <span class="d-none d-md-block dropdown-toggle ps-2">
            @if($authUser->isSuperAdmin) Super Admin | @elseif($user_role !== false) {{ $user_role->name }} |  @endif
            {{ Auth::user()->name }}
          </span>
          <span class="text-muted">|</span>
          @if (isset(Auth::user()->profile_picture))
            <img src="{{ asset('/storage/staff/'.Auth::user()->profile_picture) }}" alt="Profile" class="rounded-circle" /> 
          @else
            <span class="bg-dark rounded-circle p-2 text-white">{{ Auth::user()->shortName(Auth::user()->name) }}</span>
          @endif
          
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ Auth::user()->name }}</h6>
            <span>Admin</span>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a
              class="dropdown-item d-flex align-items-center"
              href="{{ route('accountProfile') }}"
            >
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('accountSetting') }}">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a
              class="dropdown-item d-flex align-items-center" href="{{ route('faq') }}">
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>
        <!-- End Profile Dropdown Items -->
      </li>
      <!-- End Profile Nav -->

      <!---for mobile--->
      <li id="mobile-hamburger" class="d-block d-lg-none"><i class="btn bi bi-list toggle-sidebar-btn"></i></li>
      
      @guest
      <li class="nav-item"><a href="{{ route('login') }}">Login</a></li>
      @endguest
      
      
    </ul>
  </nav>
  <!-- End Icons Navigation -->
</header>