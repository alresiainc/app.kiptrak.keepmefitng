@extends('layouts.design')
@section('title')Edit Product @endsection

@section('extra_css')
<style>
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
</style>
@endsection

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Product</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('allProducts') }}">Products</a></li>
          <li class="breadcrumb-item active">Edit Product</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

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
              
                <form id="createForm" class="row g-3" action="{{ route('editProductPost', $product->unique_key) }}"
                    method="POST" enctype="multipart/form-data">@csrf

                    <div class="gallery-uploader-wrap">
                        <label for="" class="form-label">Image</label>
                        <br>
                        <label class="uploader-img">
                          @if (isset($product->image))
                          <a
                            href="{{ asset('/storage/products/'.$product->image) }}"
                            data-fancybox="gallery"
                            data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                          >  
                          <img src="{{ asset('/storage/products/'.$product->image) }}" width="100" class="img-fluid" alt="Upload Photo"></a>
                          
                          @else
                            <img src="{{ asset('/storage/products/default.png') }}" width="50" class="rounded-circle img-thumbnail img-fluid"
                            alt="{{$product->name}}"></a> 
                          @endif
                        </label>
                    </div>

                    <div class="col-md-12">
                      <label for="" class="form-label">Name<span class="text-danger fw-bolder">*</span></label>
                      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                      id="" value="{{ $product->name }}">
                      @error('name')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    
                    <div class="col-md-6">
                      <label for="" class="form-label">
                        Qty Avail:<span class="text-dark fw-bolder"> ({{ $stock_available }})</span>
                        <span class="text-sm text-success fw-bolder"> use negative numbers to decrease qty, e.g: -5</span>
                      </label>
                      <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                      value="0">
                      @error('quantity')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>

                    <!---categories--->
                <div class="col-md-6">
                  <label for="" class="form-label">Select Category *</label>

                  <div class="d-flex @error('category') is-invalid @enderror">

                    <select name="category" id="addCategorySelect" class="select2 form-control @error('category') is-invalid @enderror">
                      <option value="{{ isset($product->category_id) ?? $product->category_id }}">{{ isset($product->category_id) ? $product->category->name : 'Nothing Selected' }}</option>
                      
                      @foreach ($categories as $category)
                          <option value="{{ $category->id }}">
                              {{ $category->name }}
                          </option>
                      @endforeach
                      
                   </select>
                      
                   <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategory">
                    <i class="bi bi-plus"></i></button>
                  </div>
                  @error('category')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Product Cost of Production<span class="text-danger fw-bolder">*</span></label>
                  <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" placeholder="" value="{{ $product->purchase_price }}">
                  @error('purchase_price')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Selling Price*</label>
                  <input type="number" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" placeholder="" value="{{ $product->sale_price }}">
                  @error('sale_price')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Change WareHouse | optional</label>
                  <div class="d-flex @error('warehouse') is-invalid @enderror">

                    <select name="warehouse" id="addWarehouseSelect" class="select2 form-control @error('warehouse') is-invalid @enderror">
                    <option value="">Nothing Selected</option>
                    
                    @if (count($warehouses))
                    @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">
                        {{ $warehouse->name }}
                    </option>
                    @endforeach
                    @endif
                     
                    </select>
                    
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addWarehouse">
                        <i class="bi bi-plus"></i></button>
                </div>
                @error('warehouse')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                </div>
    
                <div class="col-md-4">
                  <label for="" class="form-label">Size or Weight | optional</label>
                  <input type="text" name="size" class="form-control" placeholder="e.g: 5kg" value="{{ isset($product->size) ?? $product->size  }}">
                  
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Currency<span class="text-danger fw-bolder">*</span></label>
                  <select name="currency" class="custom-select form-control border @error('currency') is-invalid @enderror" id="">
                    <option value="{{ $product->country_id }}" selected>{{ $currency_nationality }} | {{ $currency_symbol }}</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">
                          {{ $country->name }} | {{ $country->symbol }}
                        </option>
                    @endforeach
                  </select>
                  @error('currency')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="" class="form-label">Color | optional</label>
                  <input type="text" name="color" class="form-control" placeholder="" value="{{ isset($product->color) ?? $product->color  }}">
                </div>

    
    
                    @if ($features != '')
                      <hr>

                      <div class="col-md-12">
                        <label for="" class="form-label">Features | optional</label>
                      </div>
                    
                    @foreach ($features as $feature)
                        <div class="col-md-6 mt-1 element">
                            <input type="text" name="features[]" class="form-control" value="{{ $feature }}">
                        </div>
                    @endforeach
                        <!--add extra features-->
                        <div class="product-clone-section wrapper">
                          <div class="col-md-12 mt-1 element">
                          <label for="" class="form-label">More Features | optional</label>
                          <input type="text" name="features[]" class="form-control" placeholder="" value="">
                          </div>
      
                          <!--append elements to-->
                          <div class="results"></div>
      
                          <div class="buttons d-flex justify-content-between">
                          <button type="button" class="clone btn btn-success btn-sm rounded-pill"><i class="bi bi-plus"></i></button>
                          <button type="button" class="remove btn btn-danger btn-sm rounded-pill"><i class="bi bi-dash"></i></button>
                          </div>
                        </div>
                        
                        <hr>
                    @else
                    
                        <div class="product-clone-section wrapper">
                            <div class="col-md-12 mt-1 element">
                            <label for="" class="form-label">Features | optional</label>
                            <input type="text" name="features[]" class="form-control" placeholder="" value="">
                            </div>
        
                            <!--append elements to-->
                            <div class="results"></div>
        
                            <div class="buttons d-flex justify-content-between">
                            <button type="button" class="clone btn btn-success btn-sm rounded-pill"><i class="bi bi-plus"></i></button>
                            <button type="button" class="remove btn btn-danger btn-sm rounded-pill"><i class="bi bi-dash"></i></button>
                            </div>
                        </div>

                    @endif
                    
                    <div class="col-md-8">
                      <label for="" class="form-label">Select WareHouse | optional</label>
                      <select name="warehouse_id" class="custom-select form-control border" id="">
                        <option value="">Nothing Selected</option>
                        <option value="1">Warehouse 1</option>
                        <option value="2">Warehouse 2</option>
                      </select>
                      
                    </div>
    
                    <div class="col-md-4">
                      <label for="" class="form-label">Image<span class="text-danger fw-bolder">*</span></label>
                      <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="">
                      @error('image')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="text-end">
                      <button type="submit" class="btn btn-primary">Update Product</button>
                      <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                  </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

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

<!-- addWarehouse -->
<div class="modal fade" id="addWarehouse" tabindex="-1" aria-labelledby="addWarehouseLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Create New WareHouse</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="">@csrf
              <div class="modal-body">
                  
                  <div class="d-grid mb-2">
                    <label for="" class="form-label">Name<span class="text-danger fw-bolder">*</span></label>
                    <input type="text" name="name" class="form-control name @error('name') is-invalid @enderror"
                    id="" value="{{ old('name') }}">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label">Select Type</label>
                    <select name="type" class="custom-select type form-control border">
                      <option value="major" selected>Major</option>
                      <option value="minor">Minor</option>
                    </select>
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label">Select Agent | optional</label>
                    <select name="agent_id" class="custom-select agent_id form-control border" id="">
                      <option value="" selected>Nothing Selected</option>
                      @if (count($agents) > 0)
                          @foreach ($agents as $agent)
                          <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                          @endforeach
                      @endif
                    </select>
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label">City or Town</label>
                    <input type="text" name="city" class="form-control city @error('city') is-invalid @enderror"
                    id="" value="{{ old('city') }}">
                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label">State</label>
                    <input type="text" name="state" class="form-control state @error('state') is-invalid @enderror"
                    id="" value="{{ old('state') }}">
                    @error('state')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label">Country</label>
                    <select name="country" class="custom-select country form-control border" id="">
                      <option value="1">Nigeria</option>
                      @if (count($countries) > 0)
                          @foreach ($countries as $country)
                          <option value="{{ $country->id }}">{{ $country->name }}</option>
                          @endforeach
                      @endif
                    </select>
                    @error('country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="d-grid mb-2">
                    <label for="" class="form-label">Address</label>
                    <textarea name="address" class="form-control address @error('address') is-invalid @enderror" id="" cols="30" rows="2">{{ old('address') }}</textarea>
                    
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                                  
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addWarehouseBtn">Add Warehouse</button>
              </div>
          </form>
      </div>
  </div>
</div>

@endsection

@section('extra_js')

<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/js/additional-methods.min.js') }}"></script>

<!--image validation--->
<script>
  var message = '<span class="text-danger">File size must be less than 2mb</span>';
  $.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
  }, message);
  
  jQuery(function ($) {
      "use strict";
      $('#createForm').validate({
          rules: {
              // FirstName: {
              //     required: true,
              //     maxlength: 20
              // },
              image: {
                  required: false,
                  extension: "jpg,png,jpeg,gif,svg,webp",
                  filesize: 2048576, //2mb
              }, 
          },
      });
  });
</script>

<!--add & remove features--->
<script>
    //clone
    $('.wrapper').on('click', '.remove', function() {
        $('.remove').closest('.wrapper').find('.element').not(':first').last().remove();
    });
    $('.wrapper').on('click', '.clone', function() {
        $('.clone').closest('.wrapper').find('.element').first().clone().appendTo('.results');
    });
</script>

<!--add by modal--->
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
              url:'/ajax-create-product-category',
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
</script>

<script>
  //addWarehouse Modal
  $('.addWarehouseBtn').click(function(e){
        e.preventDefault();
        var name = $("form .name").val();
        var type = $('form .type').find(":selected").val();
        var agent_id = $('form .agent_id').find(":selected").val();
        var city = $("form .city").val();
        var state = $("form .state").val();
        var country = $("form .country").val();
        var address = $("form .address").val();

        // alert(category_name)
        if (name != '') {
            $('#addWarehouse').modal('hide');

            $.ajax({
                type:'get',
                url:'/ajax-create-warehouse',
                data:{ name:name, type:type, agent_id:agent_id, city:city, state:state, country:country, address:address },
                success:function(resp){
                    
                    if (resp.status) {
                        console.log(resp.data.warehouse)
                        var datas = {
                            id: resp.data.warehouse.id,
                            text: resp.data.warehouse.name
                        };
                        var newOption = new Option(datas.text, datas.id, false, false);
                        $('#addWarehouseSelect').prepend(newOption).trigger('change');
                        
                        //$('#addCategorySelect').prepend('<option value='+resp.data.category.id+'>'+resp.data.category.name+'</option>')
                        alert('Warehouse Added Successfully')
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
</script>
@endsection