@extends('layouts.design')
@section('title')Customer @endsection
@section('extra_css')
  <style>
    .delete_all{
        background-color: #DC3545 !important;
        border-color: #DC3545 !important; 
      }
  </style>
@endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Customer</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Customer</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  
  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      
    </div>
  </section>

  @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
  @endif

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">
            <div class="float-start text-start">
              <a href="{{ route('addCustomer') }}" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Add Agent">
                <i class="bi bi-plus"></i> <span>Add Customer</span></a>
            </div>

            <div class="float-end text-end">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <a href="{{ route('customersExport') }}"><button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-download"></i> <span>Export</span></button></a>
              <button class="btn btn-sm btn-info rounded-pill mail_all" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Mail All"><i class="bi bi-chat-left"></i> <span>Mail All</span></button>
              <button class="btn btn-sm btn-info rounded-pill delete_all" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All" data-url="{{ url('/delete-all-customers') }}"><i class="bi bi-trash"></i> <span>Delete All</span></button>
            </div>
          </div>
          <hr>
          
          <div class="table table-responsive">
            <table id="products-table" class="table custom-table" style="width:100%">
              <thead>
                  <tr>
                      <th><input type="checkbox" id="users-master"></th>
                      <th>Photo</th>
                      <th>Name</th>
            
                      <th>City/Town</th>
                      <th>State | Country</th>
                      <th>Date Joined</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @if (count($customers) > 0)
                    @foreach ($customers as $customer)
                    <tr id="tr_{{$customer->id}}">
                      <td><input type="checkbox" class="sub_chk" data-id="{{ $customer->id }}" data-phone_number="{{ $customer->whatsapp_phone_number }}"></td>
                      <td>
                        @if (isset($customer->profile_picture))
                            <a
                            href="{{ asset('/storage/customer/'.$customer->profile_picture) }}"
                            data-fancybox="gallery"
                            data-caption="{{ isset($customer->profile_picture) ? $customer->name : 'no caption' }}"
                            >   
                            <img src="{{ asset('/storage/customer/'.$customer->profile_picture) }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                            alt="{{$customer->name}}"></a>
                        @else
                        <img src="{{ asset('/storage/customer/person.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                            alt="{{$customer->name}}">
                        @endif
                        
                      </td>
                      <td>{{ $customer->firstname }} {{ $customer->lastname }} <br> {{ $customer->email }}</td>
                      <td>{{ isset($customer->city) ? $customer->city : 'N/A' }}</td>
                      
                      <td>{{ $customer->state }} | {{ isset($customer->country_id) ? $customer->country->name : '' }}</td>
                      
                      <td>{{ $customer->created_at }}</td>
                      <td>
                        <div class="d-flex">
                          <div class="me-2 btn-group">
                            <button type="button" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown"
                            class="dropdown-toggle btn btn-primary btn-sm fw-bolder" style="font-size: 10px;">Filter</button>

                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start"
                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 33px, 0px);">
            
                                <a href="{{ route('singleCustomerSales', $customer->unique_key) }}">
                                    <button type="button" tabindex="0" class="dropdown-item">Products Bought</button></a>
                                <div tabindex="-1" class="dropdown-divider"></div>
                                
                            </div>
                          </div>

                          <a href="javascript:void(0);" onclick="whatsappModal({{ json_encode($customer) }})" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Whatsapp">
                            <i class="bi bi-whatsapp"></i></a>
                          <a href="{{ route('singleCustomer', $customer->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editCustomer', $customer->unique_key) }}" class="btn btn-success btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
                          <a href="{{ route('deleteCustomer', $customer->unique_key) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
                        </div>
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

      <form id="sendMailForm" action="{{ route('sendCustomerWhatsapp') }}" method="POST">@csrf
        <div class="modal-body">
            <input type="hidden" name="whatsapp_customer_id" id="whatsapp_customer_id" value="">
            <input type="hidden" name="whatsapp_customer_order_id" id="whatsapp_customer_order_id" value="">
            <div class="d-grid mb-2">
              <label for="">Phone format: 23480xxxx</label>
              <input type="text" name="recepient_phone_number" id="recepient_phone_number" class="form-control">
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

@endsection

@section('extra_js')
  
<script>
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

      var allVals = [];  
      $(".sub_chk:checked").each(function() {  
          allVals.push($(this).attr('data-id')); //['2', '1']
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
          
          }  
      }  
  }); 
</script>

<script>
  function whatsappModal($customer="") {
    $('#whatsappModal').modal("show");
    $('#whatsapp_customer_id').val($customer.id);
    $part = $customer.whatsapp_phone_number.substring(0,1);
    if ($part == '0') {
      $whatsapp_phone_number = '234'+$customer.whatsapp_phone_number.substring(1);
      $('#recepient_phone_number').val($whatsapp_phone_number);
    } else {
      $('#recepient_phone_number').val($customer.whatsapp_phone_number);
    }
    $name = $customer.firstname+' '+$customer.lastname;
    $('#whatsappModalLabel span').text($name);
    //console.log($whatsapp_phone_number)
    
  }
</script>

<script>
  //delete_all
  $('.delete_all').on('click', function(e) {

  var allVals = [];  
  $(".sub_chk:checked").each(function() {  
      allVals.push($(this).attr('data-id'));
  });  

  //check if any is checked
  if(allVals.length <=0)  
  {  
    alert("Please select row(s) to delete.");  
  }  else {  
    var check = confirm("Are you sure you want to delete this row?");  
    if(check == true){  

      var join_selected_values = allVals.join(",");

      $.ajax({
          url: $(this).data('url'),
          type: 'GET',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: 'ids='+join_selected_values,
          success: function (data) {
            if (data['success']) {
                $(".sub_chk:checked").each(function() {  
                    $(this).parents("tr").remove();
                });
                alert(data['success']);
            } else if (data['error']) {
                alert(data['error']);
            } else {
                alert('Whoops Something went wrong!!');
            }
          },
          error: function (data) {
              alert(data.responseText);
          }
      });

      $.each(allVals, function( index, value ) {
          $('table tr').filter("[data-row-id='" + value + "']").remove();
      });
    }  
  }  
  });
</script>

@endsection