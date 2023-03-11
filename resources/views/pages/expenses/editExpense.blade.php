@extends('layouts.design')
@section('title')Edit Expense @endsection
@section('extra_css')
    <style>
        /* select2 arrow */
        select{
            -webkit-appearance: listbox !important
        }

        /* custom-select border & inline edit */
        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }
        div.filter-option-inner-inner{
            color: #000 !important;
        }
        /* custom-select border & inline edit */

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
    </style>
@endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Expense</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Expense</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

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
            <div class="card-body">
              
                <form class="row g-3 needs-validation" action="{{ route('editExpensePost', $expense->unique_key) }}" method="POST"
                enctype="multipart/form-data">@csrf
                <div class="col-md-12 mb-3">The field labels marked with * are required input fields.</div>
  
                  
                  <div class="col-md-4">
                      <label for="" class="form-label">Select Category *</label>
  
                      <div class="d-flex">
  
                          <select name="category" id="addCategorySelect" class="select2 form-control border @error('category') is-invalid @enderror" id="">
                          <option value="{{ $expense->category->id }}" selected>{{ $expense->category->name }}</option>
      
                          @foreach ($categories as $category)
                              <option value="{{ $category->id }}">
                                  {{ $category->name }}
                              </option>
                          @endforeach
                              
                          </select>
                          
                          <button type="button" class="btn btn-outline-primary d-none" data-bs-toggle="modal" data-bs-target="#addCategory">
                              <i class="bi bi-plus"></i></button>
                      </div>
                      @error('customer')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
  
                  <div class="col-md-4">
                      <label for="" class="form-label">Select Warehouse  | Optional</label>
                      <select name="warehouse" class="select2 form-control border @error('warehouse') is-invalid @enderror" id="">
                        <option value="{{ isset($expense->warehouse_id) ? $expense->warehouse->id : '' }}" selected>
                            {{ isset($expense->warehouse_id) ? $expense->warehouse->name : 'Nothing Selected' }}</option>
    
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                            
                      </select>
                      @error('warehouse')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
  
                  <div class="col-md-4">
                    <label for="" class="form-label">Expenses For | Optional</label>
                    <select name="staff_id" class="select2 form-control border @error('staff_id') is-invalid @enderror" id="">
                      <option value="{{ isset($expense->staff_id) ? $expense->staff->name : '' }}">{{ isset($expense->staff_id) ? $expense->staff->name : 'Nothing Selected' }}</option>
  
                      @foreach ($staffs as $staff)
                          <option value="{{ $staff->id }}">
                              {{ $staff->name }} {{ isset($staff->current_salary) ? '| '.$staff->current_salary : '' }}
                          </option>
                      @endforeach
                          
                    </select>
                    @error('staff_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
  
                  
  
                  <div class="col-md-12">
                      <label for="" class="form-label">Amount</label>
                      <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="" value="{{ $expense->amount }}">
                      @error('amount')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
  
                  <div class="col-md-12">
                      <label for="" class="form-label">Note <span class="text-danger">*</span></label>
                      <textarea name="note" id="" name="note" class="form-control @error('note') is-invalid @enderror" cols="30" rows="10">{{ $expense->note }}</textarea>
                      
                      @error('note')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
                  
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save Expense</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                  </div>
                </form>
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add
                    Customer</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('addCustomerPost') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="modal-body">
                    
                    <div class="d-grid mb-2">
                        <label for="">First Name</label>
                        <input type="text" name="firstname" class="form-control" placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Last Name</label>
                        <input type="text" name="lastname" class="form-control" placeholder="">
                    </div>
                    <div class="d-grid mb-2">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control"
                            placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Whatsapp Number</label>
                        <input type="text" name="whatsapp_phone_number" class="form-control"
                            placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Address</label>
                        <input type="text" name="delivery_address" class="form-control" placeholder="">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="">@csrf
                <div class="modal-body">
                    
                    <div class="d-grid mb-2">
                        <label for="">Category Name</label>
                        <input type="text" name="name" class="form-control category_name" placeholder="">
                    </div>

                                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary addCategoryBtn">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal addAccount -->
<div class="modal fade" id="addAccount" tabindex="-1" aria-labelledby="addAccountLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Account</h1>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="">@csrf
                <div class="modal-body">
                    
                    <div class="d-grid mb-2">
                        <label for="">Account No</label>
                        <input type="text" name="account_no" class="form-control account_no" placeholder="" value="{{ $account_no }}" readonly>
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">Account Name</label>
                        <input type="text" name="name" class="form-control name" placeholder="">
                    </div>
                    <div class="d-grid mb-2">
                        <label for="">Initial Balance</label>
                        <input type="number" name="initial_balance" class="form-control initial_balance" placeholder="">
                    </div>

                    <div class="d-grid mb-2">
                        <label for="">note</label>
                        <input type="text" name="note" class="form-control note" placeholder="">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary addAccountBtn">Add Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra_js')

<script>
    //addCategory Modal
    $('.addCategoryBtn').click(function(e){
         e.preventDefault();
         var category_name = $("form .category_name").val();
         // alert(category_name)
         if (category_name != '') {
             $('#addCategory').modal('hide');
 
             $.ajax({
                 type:'get',
                 url:'/ajax-create-expense-category',
                 data:{ category_name:category_name },
                 success:function(resp){
                     
                     if (resp.status) {
                         
                         var datas = {
                             id: resp.data.category.id,
                             text: resp.data.category.name
                         };
                         var newOption = new Option(datas.text, datas.id, false, false);
                         $('#addCategorySelect').prepend(newOption).trigger('change');
                         
                         //$('#addCategorySelect').prepend('<option value='+resp.data.category.id+'>'+resp.data.category.name+'</option>')
                         alert('Category Added Successfully')
                         // return false;
                     } 
                         
                 },error:function(){
                     alert("Error");
                 }
             });
         
         } else {
             alert('Error: Something went wrong')
         }
    });
 
    //addAccount Modal
    $('.addAccountBtn').click(function(e){
         e.preventDefault();
         var account_no = $("form .account_no").val();
         var name = $("form .name").val();
         var initial_balance = $("form .initial_balance").val();
         var note = $("form .note").val();
         
         // alert(category_name)
         
             $('#addAccount').modal('hide');
 
             $.ajax({
                 type:'get',
                 url:'/ajax-create-account',
                 data:{ account_no:account_no, name:name, initial_balance:initial_balance, note:note },
                 success:function(resp){
                     
                     if (resp.status) {
                         
                         var datas = {
                             id: resp.data.account.id,
                             text: resp.data.account.name
                         };
                         var newOption = new Option(datas.text, datas.id, false, false);
                         $('#addAccountSelect').prepend(newOption).trigger('change');
                         
                         //$('#addCategorySelect').prepend('<option value='+resp.data.category.id+'>'+resp.data.category.name+'</option>')
                         alert('Account Added Successfully')
                         // return false;
                     } 
                         
                 },error:function(){
                     alert("Error");
                 }
             });
         
         
    });
 
 
</script>

@endsection