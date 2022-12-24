@extends('layouts.design')
@section('title'){{ $category->name }} Customers @endsection
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>{{ $category->name }}'s Customers</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">{{ $category->name }} Customers</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  
  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      
    </div>

  </section>

  <section>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body pt-3">
            
          <div class="clearfix mb-2">
            <div class="float-end text-end">
              <button class="btn btn-sm btn-dark rounded-pill mail_all" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Send Mail">
                <i class="bi bi-envelope"></i> <span>Send Mail</span></button>
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
                      <th>Email</th>
                      <th>City/Town</th>
                      <th>State</th>
                      <th>Date Joined</th> 
                      <th>Action</th> 
                  </tr>
              </thead>
              <tbody>
                @if (count($customers) > 0)
                    @foreach ($customers as $customer)
                    <tr>
                      <td><input type="checkbox" class="sub_chk" data-id="{{ $customer->id }}"></td>
                      <td>
                        @if (isset($customer->profile_picture))
                            <a
                            href="{{ asset('/storage/customer/'.$customer->profile_picture) }}"
                            data-fancybox="gallery"
                            data-caption="{{ isset($customer->profile_picture) ? $customer->firstname.' '.$customer->lastname : 'no caption' }}"
                            >   
                            <img src="{{ asset('/storage/customer/'.$customer->profile_picture) }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                            alt="{{$customer->firstname}} {{$customer->lastname}}"></a>
                        @else
                        <img src="{{ asset('/storage/customer/person.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                            alt="{{$customer->firstname}} {{$customer->lastname}}">
                        @endif
                        
                      </td>
                      <td>{{ $customer->firstname }} {{ $customer->lastname }}</td>
                      <td>{{ $customer->email }} </td>
                      <td>{{ isset($customer->city) ? $customer->city : 'N/A' }}</td>
                      
                      <td>{{ $customer->state }} </td>
                      
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

<!-- Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Product CSV File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>Download sample product CSV file <a href="#" class="btn btn-sm rounded-pill btn-primary"><i class="bi bi-download me-1"></i> Download</a></div>
        <div class="mt-3">
          <label for="formFileSm" class="form-label">Click to upload file</label>
          <input class="form-control form-control-sm" id="formFileSm" type="file">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
      </div>
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
        //////

        //mail_all
        $('.mail_all').on('click', function(e) {

            var selectedCategory = "{{ $category->unique_key }}";
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id')); //['1', '2']
            });  

            //check if any is checked
            if(allVals.length <= 0)  
            {  
                alert("Please select customer(s) to mail.");  
            }  else {  
                var check = confirm("Are you sure you want to mail this user(s)?");  
                if(check == true){  

                var join_selected_values = allVals.join(","); //1,2
                //console.log(allVals)

                localStorage.setItem('allselectedUsers', allVals);
                window.location.href = "/mail-customers-by-category/"+selectedCategory+"/"+allVals
                //ajax end

                    $.each(allVals, function( index, value ) {
                        $('table tr').filter("[data-row-id='" + value + "']").remove();
                    });
                }
                //end of true  
            }  
        });
    </script>
@endsection