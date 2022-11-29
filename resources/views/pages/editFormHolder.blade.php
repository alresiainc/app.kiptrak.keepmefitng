@extends('layouts.design')
@section('title')Edit Form @endsection

@section('extra_css')
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Form</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Edit Form</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <form action="{{ route('editFormPost', $formLabel->unique_key) }}" method="POST">@csrf
        <!--assign agent & discount-->
        <section class="users-list-wrapper">
            <div class="users-list-filter px-1">
            
                <div class="row border rounded py-2 mb-2">
        
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="">Assigned Agent</label>
                    <fieldset class="form-group">
                    <select data-live-search="true" class="custom-select" name="staff_assigned" id="">
                        <option value="1" selected>John Doe</option>
                        <option value="2">Max Lucado</option>
                        <option value="3">Pearce Morgan</option>
                        <option value="5">Andrew Bruce</option>
                    </select>
                    </fieldset>
                </div>
                
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="">Discount (% or Fixed)</label>
                    <fieldset class="form-group">
                        <input type="text" name="discount" class="form-control" value="">
                    </fieldset>
                </div>
        
                <!--buttons here-->
        
                </div>
            
            </div>
        
        </section>

        <!---product orderbmp upsell contact thankyou--->
        <section>
        <div class="row">
            <div class="col-md-12">

            <!--firstpahse heading & sub--->
            <div class="card">

                <div class="card-body">
                    <h6 class="card-title">First Phase: Product View</h6>
                <div class="row g-3">
                    
                    <!--heading & sub--->
                    <div class="col-md-6">
                        <label for="" class="form-label">Heading</label>
                        <input type="text" name="order_heading" class="form-control" value="{{ $formLabel->order_heading }}" id="" required>
                    
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">Subheading</label>
                        <input type="text" name="order_subheading" class="form-control" value="{{ $formLabel->order_subheading }}" id="" required>
                    
                    </div>

                    <!--first-phase products--->
                    @foreach ($orderProducts as $orderPro)
                        <div class="col-md-6">
                            <label for="" class="form-label">{{ $orderPro->product_id }}</label>
                            <select name="product_id[]" data-live-search="true" class="custom-select form-control border" id="">
                                <option value="{{ $orderPro->product_id }}" selected>Fanta 33cl</option>
                                <option value="">Coca Cola</option>
                                <option value="">Heineken</option>
                                <option value="">Maltina</option>
                            </select>
                            
                        </div>
                    @endforeach
                
                    
                </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            <!--orderbump heading & sub--->
            <div class="card">

                <div class="card-body">
                    <h6 class="card-title">OrderBump & UpSell</h6>
                <div class="row g-3">
                    
                    <div class="col-md-6">
                    <label for="" class="form-label">OrderBump Heading</label>
                    <input type="text" class="form-control" value="{{ $formLabel->orderbump_heading }}" placeholder="" required>
                    
                    </div>
                    <div class="col-md-6">
                    <label for="" class="form-label">OrderBump Subheading</label>
                    <input type="text" name="orderbump_heading" class="form-control" value="{{ $formLabel->orderbump_subheading }}" placeholder="" required>
                    
                    </div>
                    
                    <!--upsell heading & sub--->
                    <div class="col-md-6">
                        <label for="" class="form-label">UpSell Heading</label>
                        <input type="text" name="upsell_heading" class="form-control" value="{{ $formLabel->upsell_heading }}" placeholder="" required>
                        
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">UpSell Subheading</label>
                        <input type="text" name="upsell_subheading" class="form-control" value="{{ $formLabel->upsell_subheading }}" placeholder="" required>
                    
                    </div>
                    
                    <!--orderbump & upsell product--->
                    <div class="col-md-6">
                        <label for="" class="form-label">OrderBump Product</label>
                        <select name="orderbump_product_id" data-live-search="true" class="custom-select form-control border" id="">
                            <option value="{{ $formLabel }}" selected>Coca Cola</option>
                            <option value="">Fanta</option>
                        </select>
                        
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">UpSell Product</label>
                        <select name="upsell_product_id" data-live-search="true" class="custom-select form-control border" id="">
                            <option value="coco" selected>Fanta</option>
                            <option value="">Heineken</option>
                        </select>
                        
                    </div>

                </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            <!--contact details label--->
            <div class="card">

                <div class="card-body">
                    <h6 class="card-title">Contact Details Label</h6>
                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label for="" class="form-label">Firstname Label</label>
                        <input type="text" name="firstname" class="form-control" value="{{ $formLabel->customer_firstname_label }}" placeholder="" required>
                    
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Lastname Label</label>
                        <input type="text" name="lastname" class="form-control" value="{{ $formLabel->customer_lastname_label }}" placeholder="" required>
                    
                    </div>
                    
                    <div class="col-md-6">
                        <label for="" class="form-label">Phone Label</label>
                        <input type="text" class="form-control" value="{{ $formLabel->customer_phone_label }}" placeholder="" required>
                        
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Email Label</label>
                        <input type="text" name="email" class="form-control" value="{{ $formLabel->customer_email_label }}" placeholder="" required>
                    
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">City Label</label>
                        <input type="text" name="city" class="form-control" value="{{ $formLabel->customer_state_label }}" placeholder="" required>
                    
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">State Label</label>
                        <input type="text" name="state" class="form-control" value="{{ $formLabel->customer_state_label }}" placeholder="" required>
                    
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">Country Label</label>
                        <input type="text" name="country" class="form-control" value="{{ $formLabel->customer_state_label }}" placeholder="" required>
                    
                    </div>
                    
                    <div class="col-md-6">
                        <label for="" class="form-label">Address Label</label>
                        <input type="text" name="address" class="form-control" value="{{ $formLabel->customer_address_label }}" placeholder="" required>
                    
                    </div>
                    
                </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            <!--thankYou label--->
            <div class="card">

                <div class="card-body">
                    <h6 class="card-title">ThankYou Label</h6>
                <div class="row g-3">
                    
                    <div class="col-md-12">
                    <label for="" class="form-label">Heading</label>
                    <input type="text" name="thankyou_heading" class="form-control" value="{{ $formLabel->thankyou_heading }}" placeholder="" required>
                    
                    </div>
                    <div class="col-md-12">
                    <label for="" class="form-label">Subheading</label>
                    <input type="text" name="thankyou_subheading" class="form-control" value="{{ $formLabel->thankyou_subheading }}" placeholder="" required>
                    
                    </div>
                    
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Update Form</button>                    
                    </div>
                </div><!-- End Multi Columns Form -->
                
                </div>

            </div>

            </div>
        </div>
        </section>
    </form>
  </main><!-- End #main -->


@endsection

@section('extra_js')
@endsection