@extends('layouts.design')
@section('title')Orders @endsection

@section('extra_css')
    <style>
        /* select2 arrow */
        select{
            -webkit-appearance: listbox !important
        }

        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }
    
        div.filter-option-inner-inner{
            color: #000 !important;
        }
          
        /* select2 height proper */
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 35px !important;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }
        /* select2 height proper */

        table .dropdown-menu{
          position: fixed !important;
          top: 50% !important;
          left:92% !important;
          transform: translate(-92%, -50%) !important;
        }

        .whatsapp-icon {
          /* font-size: 22px;
          color: #012970;
          margin-right: 25px; */
          position: relative;
        }

        .whatsapp-icon .whatsapp-icon-number {
          position: absolute;
          inset: -2px -5px auto auto;
          font-weight: normal;
          font-size: 12px;
          padding: 3px 6px;
        }

    </style>
@endsection

@section('content')

<main id="main" class="main">

  @if ($entries)
  <div class="pagetitle">
    <h1>Order Entries for Form: {{ $formHolder->name }}</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('allOrders') }}">Orders</a></li>
        <li class="breadcrumb-item active">Entries for Form: {{ $formHolder->name }}</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->  
  @else
    
  <div class="pagetitle">
    <h1>Orders @if (!isset($status) || $status=='new') New @elseif($status=='pending') Pending
      @elseif($status=='cancelled') Cancelled @elseif($status=='delivered_not_remitted') Delivered not Remitted
      @elseif($status=='delivered_and_remitted') Delivered and Remitted @endif</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('allOrders') }}">Orders</a></li>
        <li class="breadcrumb-item active">@if (!isset($status) || $status=='new') New @elseif($status=='pending') Pending
          @elseif($status=='cancelled') Cancelled @elseif($status=='delivered_not_remitted') Delivered not Remitted
          @elseif($status=='delivered_and_remitted') Delivered and Remitted @endif</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @endif

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      
    </div>

  </section>

  @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
  @endif

  @if(Session::has('whatsapp_server_error'))
    <div class="alert alert-info mb-3 text-center">
        {{Session::get('whatsapp_server_error')}}
    </div>
  @endif

  @if(Session::has('info'))
    <div class="alert alert-info mb-3 text-center">
        {{Session::get('info')}}
    </div>
  @endif
  
  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">

            <div class="float-start text-start d-none">
                <button data-bs-target="#addMoneyTransfer" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                  <i class="bi bi-plus"></i> <span>Add Money Transfer</span></button>
            </div>

            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill d-none" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <button class="btn btn-sm btn-secondary rounded-pill d-none" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
              <button class="btn btn-sm btn-info rounded-pill mail_all" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Mail All"><i class="bi bi-chat-left"></i> <span>Mail All</span></button>
            </div>
          </div>
          <hr>

          <div class="row mb-3">
            <div class="col-lg-3 col-md-6">
              <label for="">Start Date</label>
              <input type="text" name="start_date" id="min" class="form-control filter">
            </div>

            <div class="col-lg-3 col-md-6">
              <label for="">End Date</label>
              <input type="text" name="end_date" id="max" class="form-control filter">
            </div>
          </div>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                    <th><input type="checkbox" id="users-master"></th>
                    @if (!$entries)<th>Order Code</th>@endif
                    <th>Customer</th>
                    <th>Delivery Due Date</th>
                    <th>Delivery Address</th>
                    <th>Agent</th>
                    <th>Message</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
              </thead>
              <tbody>

                @if (count($orders) > 0)
                  @foreach ($orders as $key=>$order)
                  <tr id="tr_{{ isset($order->customer_id) ? $order->customer->id : '' }}">

                    <td><input type="checkbox" class="sub_chk" data-id="{{ isset($order->customer_id) ? $order->customer->id : '' }}" data-order_id="{{ $order->id }}"></td>
                      @if (!$entries)<td>{{ $order->orderCode($order->id) }}</td>@endif
                      <td>{{ $order->customer_id ? $order->customer->firstname : 'No response' }} {{ $order->customer_id ? $order->customer->lastname : '' }}</td>
                      
                      <td>
                        @if (isset($order->customer->delivery_duration))
                        {{ \Carbon\Carbon::parse($order->customer->created_at->addDays($order->customer->delivery_duration))->format('D, jS M Y') }}
                        
                        @else
                         No reponse   
                        @endif
                        
                      </td>
                      <td>{{ $order->customer_id ? $order->customer->delivery_address : 'No response' }}</td>

                      @if (isset($order->agent_assigned_id))
                      <td>
                        {{ $order->agent->name }} <br>
                        <span class="badge badge-dark" onclick="changeAgentModal('{{ $order->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Change Agent" style="cursor: pointer">
                          <i class="bi bi-plus"></i> <span>Change Agent</span></span>
                      </td>
                      @else
                      <td style="width: 120px">
                        <span class="badge badge-success" onclick="addAgentModal('{{ $order->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Agent" style="cursor: pointer">
                          <i class="bi bi-plus"></i> <span>Assign Agent</span></span> 
                      </td>
                      @endif

                      <!--messages--->
                      <td>
                        <div class="d-flex justify-content-between border">
                          <a href="javascript:void(0);" onclick="whatsappModal({{ json_encode($order) }}, '{{ $order->whatsappNewOrderMessage($order) }}')" class="btn btn-success btn-sm rounded-circle m-1 whatsapp-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Whatsapp">
                            <i class="bi bi-whatsapp"></i>
                            @if ($order->whatsappMessages() !== '')
                              <span class="badge badge-dark whatsapp-icon-number">{{ $order->whatsappMessages()->count() }}</span>
                            @endif
                          </a>
                          @if ($order->whatsappMessages() !== '')
                          <a href="{{ route('sentWhatsappMessage', $order->unique_key) }}" class="btn btn-success btn-sm rounded-circle m-1 whatsapp-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Whatsapp Messages">
                            <i class="bi bi-eye"></i></a>
                          @endif
                        </div>
                        
                        <div class="d-flex justify-content-between border">
                          <a href="javascript:void(0);" class="btn btn-info btn-sm rounded-circle m-1 whatsapp-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Whatsapp">
                            <i class="bi bi-chat"></i>
                            @if ($order->emailMessages() !== '')
                              <span class="badge badge-dark whatsapp-icon-number">{{ $order->emailMessages()->count() }}</span>
                            @endif
                          </a>
                          @if ($order->emailMessages() !== '')
                          <a href="javascript:void(0);" class="btn btn-info btn-sm rounded-circle m-1 whatsapp-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Email Messages">
                            <i class="bi bi-eye"></i></a>
                          @endif
                        </div>
                        
                      </td>

                      <td>{{ $order->created_at->format('Y-m-d') }}</td>
                      
                      <td>
  
                        <div class="btn-group">
                          @if (!isset($order->status) || $order->status=='new')
                          <button type="button" class="btn btn-info btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>new</span>
                          </button>
                          @elseif($order->status=='pending')
                          <button type="button" class="btn btn-danger btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>pending</span>
                          </button>
                          @elseif($order->status=='cancelled')
                          <button type="button" class="btn btn-dark btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>cancelled</span>
                          </button>
                          @elseif($order->status=='delivered_not_remitted')
                          <button type="button" class="btn btn-warning btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>delivered not remitted</span>
                          </button>
                          @elseif($order->status=='delivered_and_remitted')
                          <button type="button" class="btn btn-success btn-sm dropdown-toggle rounded-pill fw-bolder" data-bs-toggle="dropdown" style="font-size: 10px;">
                            <span>delivered & remitted</span>
                          </button>
                          

                          @endif
                          <ul class="dropdown-menu">

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'new']) }}">New</a></li>
                            <li><hr class="dropdown-divider"></li>
                              
                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'pending']) }}">Pending</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'cancelled']) }}">Cancelled</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'delivered_not_remitted']) }}">Delivered Not Remitted</a></li>
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item" href="{{ route('updateOrderStatus', [$order->unique_key, 'delivered_and_remitted']) }}">Delivered & Remitted</a></li>
                            <li><hr class="dropdown-divider"></li>

                          </ul>
                        </div>

                      </td>

                      <td>
                        <a class="btn btn-success btn-sm" href="{{ route('singleOrder', $order->unique_key) }}">View</a>
                      </td>
                    </tr>
                  @endforeach
                @endif
                  
              </tbody>
          </table>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main><!-- End #main -->

<!-- Modal addAgentModal -->
<div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addAgentModalLabel">Assign Agent</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('assignAgentToOrder') }}" method="POST">@csrf
                <div class="modal-body">
                    
                    <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                    <div class="d-grid mb-3">
                        <label for="">Select Agent</label>
                        <select name="agent_id" id="" data-live-search="true" class="custom-select form-control border border-dark">
                            <option value="">Nothing Selected</option>

                            @foreach ($agents as $agent)
                              <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->id }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary addAgentBtn">Assign Agent</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal changeAgentModal -->
<div class="modal fade" id="changeAgentModal" tabindex="-1" aria-labelledby="changeAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="changeAgentModalLabel">Change Assigned Agent</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('assignAgentToOrder') }}" method="POST">@csrf
              <div class="modal-body">
                  
                  <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                  <div class="d-grid mb-3">
                      <label for="">Select Agent</label>
                      <select name="agent_id" id="changeAgentModalSelect" data-live-search="true" class="custom-select form-control border border-dark">
                          <option value="" selected>Nothing Selected</option>

                          @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }} | {{ $agent->id }}</option>
                          @endforeach
                          
                      </select>
                  </div>
              
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addAgentBtn">Assign Agent</button>
              </div>
          </form>
      </div>
  </div>
</div>

<!--sendMailModal -->
<div class="modal fade" id="sendMailModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="sendMailModalLabel">Send Mail to Customers</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="sendMailForm" action="{{ route('sendCustomerMail') }}" method="POST">@csrf
        <div class="modal-body">
            <input type="hidden" name="user_id" id="user_id" value="">
            <input type="hidden" name="mail_customer_order_id" id="mail_customer_order_id" value="">

            <div class="d-grid mb-3">
                <label for="">Topic</label>
                <input type="text" name="topic" class="form-control" placeholder="">
            </div>

            <div class="d-grid mb-2">
                <label for="">Message</label>
                <textarea name="message" id="" class="form-control" cols="30" rows="10"></textarea>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary sendMailBtn">Send Message</button>
        </div>
    </form>

    </div>
  </div>
</div>

<!--whatsappModal -->
<div class="modal fade" id="whatsappModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="whatsappModalLabel">Send Whatsapp to Customer: <span></span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="sendWhatsappForm" action="{{ route('sendCustomerWhatsapp') }}" method="POST">@csrf
        <div class="modal-body">
            <input type="hidden" name="whatsapp_customer_id" id="whatsapp_customer_id" value="">
            <input type="hidden" name="whatsapp_customer_order_id" id="whatsapp_customer_order_id" value="">

            <div class="d-grid mb-2">
              <label for="">Phone format: 23480xxxx</label>
              <input type="text" name="recepient_phone_number" id="recepient_phone_number" class="form-control">
            </div>

            <div class="d-grid mb-2">
                <label for="">Message</label>
                <textarea name="message" id="whatsapp_message" class="form-control" cols="30" rows="10"></textarea>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary sendWhatsappBtn">Send Message</button>
        </div>
    </form>

    </div>
  </div>
</div>

@endsection

@section('extra_js')

<!---add & change agent---->
<script>
  function addAgentModal($orderId="") {
    $('#addAgentModal').modal("show");
    $('.order_id').val($orderId);
  }

  function changeAgentModal($orderId="") {
    $('#changeAgentModal').modal("show");
    $('.order_id').val($orderId);

  //  var option = $('#changeAgentModalSelect').val();
  //  console.log(option)

  }

  
</script>

<!---sending multi-mail---->
<script>
  //toggle all checks
  $('#users-master').on('click', function(e) {
    if($(this).is(':checked',true))  
    {
      $(".sub_chk").prop('checked', true);  
    } else {  
      $(".sub_chk").prop('checked',false);  
    }  
  });

  //mail_all
  $('.mail_all').on('click', function(e) {

      var allVals = []; var allOrderIds = [];
      $(".sub_chk:checked").each(function() {  
          allVals.push($(this).attr('data-id')); //['2', '1']
          allOrderIds.push($(this).attr('data-order_id')); //['2', '1']
      });  

      //check if any is checked
      if(allVals.length <= 0)
      {  
        alert("Please select customer(s) to mail.");  
      }  else {  
          var check = confirm("Are you sure you want to mail this customer(s)?");  
          if(check == true){  

            //var join_selected_values = allVals.join(","); //2,1
            console.log(allVals) //[2,1]
            $('#sendMailModal').modal('show');
            $('#user_id').val(allVals);
            $('#mail_customer_order_id').val(allOrderIds);
          
          }  
      }  
  }); 
</script>

<!---sending whatsapp---->
<script>
function whatsappModal($order="", $message="") {
  //console.log($orderId);
  $('#whatsappModal').modal("show");
  $('#whatsapp_customer_id').val($order.customer.id);
  $('#whatsapp_customer_order_id').val($order.id);
  $('#whatsapp_message').val($message);
  $part = $order.customer.whatsapp_phone_number.substring(0,1);
  if ($part == '0') {
    $whatsapp_phone_number = '234'+$order.customer.whatsapp_phone_number.substring(1);
    $('#recepient_phone_number').val($whatsapp_phone_number);
  } else {
    $('#recepient_phone_number').val($order.customer.whatsapp_phone_number);
  }
  $name = $order.customer.firstname+' '+$order.customer.lastname;
  $('#whatsappModalLabel span').text($name);
  //console.log($whatsapp_phone_number)
  
}
</script>

<!---network connect b4 sending whatsapp---->
<script>
  $('.sendWhatsappBtn').on('click', function(e){
    e.preventDefault();
    if (window.navigator.onLine) {
      // console.log('online')
      $('#sendWhatsappForm').submit();
    } else {
      $('#whatsappModal').modal("hide");
      alert('No Internet Connection');
      // console.log('offline')
    }

  });
</script>

<?php if($entries) : ?>
<script>
  var minDate, maxDate;
 
 // Custom filtering function which will search data in column four between two values(dates)
 $.fn.dataTable.ext.search.push(
     function( settings, data, dataIndex ) {
         var min = minDate.val();
         var max = maxDate.val();
         var date = new Date( data[6] );
  
         if (
             ( min === null && max === null ) ||
             ( min === null && date <= max ) ||
             ( min <= date   && max === null ) ||
             ( min <= date   && date <= max )
         ) {
             return true;
         }
         return false;
     }
 );
</script>
<?php endif ?>

<?php if(!$entries) : ?>
<script>
  var minDate, maxDate;
 
 // Custom filtering function which will search data in column four between two values(dates)
 $.fn.dataTable.ext.search.push(
     function( settings, data, dataIndex ) {
         var min = minDate.val();
         var max = maxDate.val();
         var date = new Date( data[7] );
  
         if (
             ( min === null && max === null ) ||
             ( min === null && date <= max ) ||
             ( min <= date   && max === null ) ||
             ( min <= date   && date <= max )
         ) {
             return true;
         }
         return false;
     }
 );
</script>
<?php endif ?>
    
@endsection