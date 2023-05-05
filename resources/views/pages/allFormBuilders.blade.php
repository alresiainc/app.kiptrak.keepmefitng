@extends('layouts.design')
@section('title')Forms @endsection

@section('extra_css')
    <style>
      td{
        font-size: 14px;
      }
      .btn-light {
          background-color: #fff !important;
          color: #000 !important;
      }
      div.filter-option-inner-inner{
          color: #000 !important;
      }
      .tox .tox-promotion {
        background: repeating-linear-gradient(transparent 0 1px,transparent 1px 39px) center top 39px/100% calc(100% - 39px) no-repeat;
        background-color: #fff;
        grid-column: 2;
        grid-row: 1;
        padding-inline-end: 8px;
        padding-inline-start: 4px;
        padding-top: 5px;
        display: none;
      }
      .tox:not([dir=rtl]) .tox-statusbar__branding {
        margin-left: 2ch;
        display: none;
      }
    </style>
@endsection

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Forms</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Forms</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form>
        <div class="row border rounded py-2 mb-2">

          <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end">
            <div class="d-grid w-100">
              <a href="{{ route('newFormBuilder') }}" class="btn btn-dark rounded-pill btn-block glow users-list-clear mb-0">
                <i class="bx bx-plus"></i>Build Form</a>
            </div>
          </div>

        </div>
      </form>
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
            <div class="float-end text-end d-none">
              <button data-bs-target="#importModal" class="btn btn-sm btn-dark rounded-pill" data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Export Data">
                <i class="bi bi-upload"></i> <span>Import</span></button>
              <button class="btn btn-sm btn-secondary rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Import Data"><i class="bi bi-download"></i> <span>Export</span></button>
              <button class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Delete All"><i class="bi bi-trash"></i> <span>Delete All</span></button>
            </div>
          </div>
          
          <hr>
          
          <div class="table table-responsive">
            <table id="orders-table" class="table table-striped custom-table" style="width:100%">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Form Name</th>
                  {{-- <th scope="col">Subheading</th> --}}
                   
                  {{-- <th scope="col">OrderId</th><!--remove--> --}}
                  <th scope="col">Staff Assigned</th>
                  <th scope="col">OrderBump</th>
                  <th scope="col">UpSell</th>
                  <th scope="col">Thank-You Page</th>
                  <th scope="col">Customer</th>
                  
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>

                @if (count($formHolders) > 0)
                  @foreach ($formHolders as $key=>$formHolder)
                    <tr>
                      <th scope="row">{{ ++$key }}</th>
                      <td>{{ $formHolder->name }} <br>
                        @if (count($formHolder->customers) > 0)
                        <a class="badge badge-info" href="{{ route('editNewFormBuilder', $formHolder->unique_key) }}">Edit</a>
                        <a class="badge badge-dark" href="{{ route('allOrders', $formHolder->unique_key) }}">Entries({{ count($formHolder->customers) }})</a>
                        
                        @else
                        <a class="badge badge-info" href="{{ route('editNewFormBuilder', $formHolder->unique_key) }}">Edit</a>
                        @if($formHolder->entries() > 0) <a href="{{ route('allOrders', $formHolder->unique_key) }}">
                          <span class="badge badge-dark" href="">Entries({{ $formHolder->entries() }})</span>
                        </a> @else <span class="badge badge-dark" href="">Entries({{ $formHolder->entries() }})</span> @endif
                        
                        @endif
                        
                        <a class="badge badge-success" href="{{ route('duplicateForm', $formHolder->unique_key) }}">Duplicate</a>
                      </td>
                      
                        @if (isset($formHolder->staff_assigned_id))
                        <td>
                          {{ $formHolder->staff->name }} <br>
                          <span class="badge badge-dark" onclick="changeAgentModal('{{ $formHolder->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Change Staff">
                            <i class="bi bi-plus"></i> <span>Change Staff</span></span>
                        </td>
                        @else
                        <td style="width: 120px">
                          <span class="badge badge-success" onclick="addAgentModal('{{ $formHolder->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Staff" style="cursor: pointer;">
                            <i class="bi bi-plus"></i> <span>Assign Staff</span></span> 
                        </td>
                        @endif
                      
                        <!--orderbump-section-->
                        @if (!isset($formHolder->order->customer_id))
                          @if (isset($formHolder->orderbump_id))
                          <td>
                            <a
                            href="{{ asset('/storage/products/'.$formHolder->orderbump->product->image) }}"
                            data-fancybox="gallery"
                            data-caption="{{ $formHolder->orderbump->product->name.', as OrderBump for '.$formHolder->name }}"
                            >   
                            <img src="{{ asset('/storage/products/'.$formHolder->orderbump->product->image) }}" width="30"
                            class="img-thumbnail img-fluid"
                            alt="{{$formHolder->orderbump->product->name}}" style="height: 30px;"></a>
    
                            <br>
                            <span class="badge badge-info" onclick="editOrderbump({{ json_encode($formHolder) }}, '{!! $formHolder->orderbump->orderbump_subheading !!}')" style="cursor: pointer;">
                              <i class="bi bi-pencil"></i> Edit</span>
    
                            <!-- Edit Ordernump-Modal Orderbump -->
                            
                            <!--edit ordernump-modal-end--->
                          </td>
                          @else
                          <td><span class="badge badge-primary" onclick="addOrderbump('{{ $formHolder->unique_key }}', '{{ $formHolder->name }}')" style="cursor: pointer;">
                            <i class="bi bi-plus"></i> Add</span>
                          </td>
                          @endif
                        @else
                          <td>
                            @if (isset($formHolder->orderbump_id))
                            <a
                              href="{{ asset('/storage/products/'.$formHolder->orderbump->product->image) }}"
                              data-fancybox="gallery"
                              data-caption="{{ $formHolder->orderbump->product->name.', as OrderBump for '.$formHolder->name }}"
                              >   
                              <img src="{{ asset('/storage/products/'.$formHolder->orderbump->product->image) }}" width="30"
                              class="img-thumbnail img-fluid"
                              alt="{{$formHolder->orderbump->product->name}}" style="height: 30px;">
                            </a>
                            <br>
                            
                            @else
                            None
                            @endif
                          </td>
                        @endif
                      
                        <!--upsell-section-->
                      @if (!isset($formHolder->order->customer_id))
                        @if (isset($formHolder->upsell_id))
                        <td>
                          <a
                          href="{{ asset('/storage/products/'.$formHolder->upsell->product->image) }}"
                          data-fancybox="gallery"
                          data-caption="{{ $formHolder->upsell->product->name.', as Upsell for '.$formHolder->name }}"
                          >   
                          <img src="{{ asset('/storage/products/'.$formHolder->upsell->product->image) }}" width="30"
                          class="img-thumbnail img-fluid"
                          alt="{{$formHolder->upsell->product->name}}" style="height: 30px;"></a>
                          <br>
                          
                          <span class="badge badge-info" onclick="editUpsell({{ json_encode($formHolder) }}, '{{ htmlspecialchars($formHolder->upsell->upsell_subheading) }}')"
                              style="cursor: pointer;">
                            <i class="bi bi-pencil"></i> Edit</span>

                            <!-- Edit Upsell-Modal -->
                            
                            <!--edit Upsell-modal-end--->
                        </td> 
                        @else  
                        <td><span class="badge badge-primary" onclick="addUpsell('{{ $formHolder->unique_key }}', '{{ $formHolder->name }}')" style="cursor: pointer;">
                          <i class="bi bi-plus"></i> Add</span></td>
                        @endif
                      @else
                        <td>
                          @if (isset($formHolder->upsell_id))
                          <a
                            href="{{ asset('/storage/products/'.$formHolder->upsell->product->image) }}"
                            data-fancybox="gallery"
                            data-caption="{{ $formHolder->upsell->product->name.', as Upsell for '.$formHolder->name }}"
                            >   
                            <img src="{{ asset('/storage/products/'.$formHolder->upsell->product->image) }}" width="30"
                            class="img-thumbnail img-fluid"
                            alt="{{$formHolder->upsell->product->name}}" style="height: 30px;">
                          </a>
                          @else
                            None
                          @endif
                        </td>
                      @endif

                      @if (isset($formHolder->thankyou_id))
                        <td>
                          {{ $formHolder->thankyou->template_name }} <span class="badge badge-info" onclick="addThankYouTemplate('{{ $formHolder->unique_key }}', '{{ $formHolder->name }}')" style="cursor: pointer;">
                            <i class="bi bi-pencil"></i> Edit</span>
                          <br>

                          <div class="d-flex mt-1">
                            <a class="btn btn-info btn-sm me-2 clipboard-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                              data-bs-title="Copy Url Link" data-clipboard-text="{{ url('/').'/'.$formHolder->thankyou->url }}">
                              <i class="bi bi-clipboard"></i>
                            </a>
  
                            <a class="btn btn-secondary btn-sm me-2 clipboard-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                              data-bs-title="Copy Embedded Code" data-clipboard-text="{{ $formHolder->thankyou->iframe_tag }}">
                              <i class="bi bi-archive"></i>
                            </a>
  
                            <a href="{{ route('singleThankYouTemplate', $formHolder->thankyou->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          </div>

                            <!-- Edit Upsell-Modal -->
                            
                            <!--edit Upsell-modal-end--->
                        </td> 
                        @else  
                        <td><span class="badge badge-primary" onclick="addThankYouTemplate('{{ $formHolder->unique_key }}', '{{ $formHolder->name }}')" style="cursor: pointer;">
                          <i class="bi bi-plus"></i> Add</span></td>
                      @endif

                      <td><span>{{ isset($formHolder->order->customer_id) ? $formHolder->order->customer->firstname .' '.$formHolder->order->customer->lastname : 'No response' }} </span></td>
                      
                      <td>
                        {{-- <input type="hidden" id="foo" value="https://github.com/zenorocha/clipboard.js.git"> --}}
                        <div class="d-flex">
                          <a class="btn btn-info btn-sm me-2 clipboard-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Copy Url Link" data-clipboard-text="{{ url('/').'/'.$formHolder->url }}">
                            <i class="bi bi-clipboard"></i>
                          </a>

                          <a class="btn btn-secondary btn-sm me-2 clipboard-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Copy Embedded Code" data-clipboard-text="{{ $formHolder->iframe_tag }}">
                            <i class="bi bi-archive"></i>
                          </a>

                          <a href="{{ route('newFormLink', $formHolder->unique_key) }}" class="btn btn-primary btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View"><i class="bi bi-eye"></i></a>
                          <a href="{{ route('editForm', $formHolder->unique_key) }}" class="btn btn-success btn-sm me-2 d-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bi bi-pencil-square"></i></a>
      
                          <a href="{{ route('deleteForm', $formHolder->unique_key) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
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

<!-- Modal addOrderbump -->
<div class="modal fade addOrderbump" id="addOrderbump" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addOrderbumpTitle">Add Order-Bump to this Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('addOrderbumpToForm') }}" method="POST">@csrf

        <div class="modal-body">
          
          <input type="hidden" id="form_unique_key" class="form_unique_key" name="form_unique_key" value="">

          <div class="mt-3">
            <label for="" class="form-label">Heading | Optional</label>
            <input type="text" id="orderbump_heading" name="orderbump_heading"  class="form-control" value="">
          </div>

          <div class="mt-3">
            <label for="" class="form-label">Sub Heading | Optional</label>
            <input type="text" id="orderbump_subheading2" name="orderbump_subheading"  class="form-control d-none" value="">
            <textarea name="orderbump_subheading" id="" cols="30" rows="5" class="mytextarea form-control"></textarea>
          </div>

          <div class="mt-3">
            <label for="orderbump_product" class="form-label">Select Product Package</label>
            <select id="orderbump_product" name="orderbump_product" data-live-search="true" class="custom-select form-control border btn-dark @error('orderbump_product') is-invalid @enderror"
                      id="" style="color: black !important;">
              <option value="">Nothing Selected</option>
              @if (count($products) > 0)

                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach

              @endif
      
            </select>

            @error('orderbump_product')
              <span class="invalid-feedback mb-3" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Discount Type</label>
            <select name="ordernump_discount_type" class="custom-select form-control border btn-dark">
              <option value="">Nothing Selected</option>
              <option value="fixed">Fixed</option>
              <option value="percentage">Percentage</option>
            </select>
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Discount Amount</label>
            <input type="text" name="orderbump_discount" class="form-control" value="">
          </div>

        </div>
        
         <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> SUBMIT</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Modal editOrderbump -->
<div class="modal fade editOrderbumpModal" id="editOrderbumpModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h1 id="editOrderbump_topic" class="modal-title fs-5">Edit Order-Bump to this Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('editOrderbumpToForm') }}" method="POST">@csrf

        <div class="modal-body">
          <input type="hidden" id="editOrderbump_form_unique_key" name="editOrderbump_form_unique_key">
          <div class="mt-3">
            <label for="" class="form-label">Heading | Optional</label>
            <input type="text" name="orderbump_heading" id="editOrderbump_heading"  class="form-control" value="">
          </div>

          <div class="mt-3">
            <label for="" class="form-label">Sub Heading | Optional</label>
            <input type="text" name="orderbump_subheading2" id=""  class="form-control d-none" value="">
            <textarea name="orderbump_subheading" id="editOrderbump_subheading" cols="30" rows="5" class="mytextarea form-control"></textarea>
          </div>

          <div class="mt-3">
            <label for="orderbump_product" class="form-label">Select Product Package</label>
            <select name="orderbump_product" data-live-search="true" class="custom-select form-control border btn-dark @error('orderbump_product') is-invalid @enderror"
              id="" style="color: black !important;">
              <option value="{{ isset($formHolder->orderbump_id) ? $formHolder->orderbump->product->id : '' }}" selected>{{ isset($formHolder->orderbump_id) ? $formHolder->orderbump->product->name : 'Nothing Selected' }}</option>
              @if (count($products) > 0)

                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach

              @endif
      
            </select>

            @error('orderbump_product')
              <span class="invalid-feedback mb-3" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          @if (isset($formHolder->orderbump_id))
          <div class="mt-3 d-flex align-items-center" style="gap: 20px;">
            <div class='category'>
              <input type="radio" name="switch_orderbump" value="on" id="on" checked />
              <label for="on" class="ml-1">On</label>
            </div>
              
            <div class='category'>
                <input type="radio" name="switch_orderbump" value="off" id="off" />
              <label for="off">Off</label>
            </div>
          </div>
          @endif
          
          <div class="mt-3 d-none">
            <label for="" class="form-label">Discount Amount</label>
            <input type="text" name="orderbump_discount" class="form-control" value="">
          </div>

        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info"><i class="bi bi-send"></i> UPDATE</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Modal Upsell -->
<div class="modal fade @error('product') show @enderror" id="addUpsell" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addUpsellTitle">Add Upsell to this Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('addUpsellToForm') }}" method="POST">@csrf
        <div class="modal-body">

          <input type="hidden" name="addUpsell_form_unique_key" id="addUpsell_form_unique_key" class="addUpsell_form_unique_key" value="">

          <div class="mt-3 d-none">
            <label for="" class="form-label">Heading | Optional</label>
            <textarea name="upsell_heading" class="form-control" id="upsell_heading" cols="30" rows="3"></textarea>
            
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Sub Heading | Optional</label>
            <textarea name="upsell_subheading" id="" cols="30" rows="5" class="mytextarea form-control"></textarea>
          </div>
          
          <div class="mt-3">
            <label for="upsell_product" class="form-label">Select Template</label>
            <select name="upsell_setting_id" data-live-search="true" class="custom-select form-control border btn-dark @error('product') is-invalid @enderror"
              id="" style="color: black !important;">
              <option value="">Nothing Selected</option>
              @if (count($upsellTemplates) > 0)

              @foreach ($upsellTemplates as $template)
                <option value="{{ $template->id }}">{{ $template->template_code }}</option>
              @endforeach

              @endif
      
            </select>

            @error('product')
              <span class="invalid-feedback mb-3" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="mt-3">
            <label for="upsell_product" class="form-label">Select Product Package</label>
            <select name="upsell_product" data-live-search="true" class="custom-select form-control border btn-dark @error('product') is-invalid @enderror"
                      id="" style="color: black !important;">
              <option value="">Nothing Selected</option>
              @if (count($products) > 0)

                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach

              @endif
      
            </select>

            @error('product')
              <span class="invalid-feedback mb-3" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Discount Type</label>
            <select name="upsell_discount_type" class="custom-select form-control border btn-dark">
              <option value="">Nothing Selected</option>
              <option value="fixed">Fixed</option>
              <option value="percentage">Percentage</option>
            </select>
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Discount Amount</label>
            <input type="text" name="upsell_discount" class="form-control" value="">
          </div>

        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> SUBMIT</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Modal editUpsell -->
<div class="modal fade editUpsellModal" id="editUpsellModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h1 id="editUpsell_topic" class="modal-title fs-5">Edit Up-sell to this Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('editUpsellToForm') }}" method="POST">@csrf

        <div class="modal-body">
          <input type="hidden" id="editUpsell_form_unique_key" name="editUpsell_form_unique_key">
          <div class="mt-3 d-none">
            <label for="" class="form-label">Heading</label>
            <input type="text" name="upsell_heading" id="editUpsell_heading"  class="form-control" value="">
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Sub Heading</label>
            <textarea name="upsell_subheading" id="editUpsell_subheading" cols="30" rows="5" class="mytextarea form-control"></textarea>
          </div>

          <div class="mt-3">
            <label for="upsell_product" class="form-label">Select Template</label>
            <select name="upsell_setting_id" id="upsell_setting_id" data-live-search="true" class="custom-select form-control border btn-dark @error('upsell_product') is-invalid @enderror"
                      id="" style="color: black !important;">
              <option value="{{ isset($formHolder->upsell_id) ? $formHolder->upsell->template->id : '' }}">{{ isset($formHolder->upsell_id) ? $formHolder->upsell->template->template_code : 'Nothing Selected' }}</option>
              @if (count($upsellTemplates) > 0)

                @foreach ($upsellTemplates as $template)
                  <option value="{{ $template->id }}">{{ $template->template_code }}</option>
                @endforeach

              @endif
      
            </select>

            @error('upsell_product')
              <span class="invalid-feedback mb-3" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="mt-3">
            <label for="upsell_product" class="form-label">Select Product Package</label>
            <select name="upsell_product" data-live-search="true" class="custom-select form-control border btn-dark @error('upsell_product') is-invalid @enderror"
              id="" style="color: black !important;">
              <option value="{{ isset($formHolder->upsell_id) ? $formHolder->upsell->product->id : '' }}" selected>{{ isset($formHolder->upsell_id) ? $formHolder->upsell->product->name : 'Nothing Selected' }}</option>
              @if (count($products) > 0)

                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach

              @endif
      
            </select>

            @error('upsell_product')
              <span class="invalid-feedback mb-3" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Discount Type</label>
            <select name="ordernump_discount_type" class="custom-select form-control border btn-dark">
              <option value="">Nothing Selected</option>
              <option value="fixed">Fixed</option>
              <option value="percentage">Percentage</option>
            </select>
          </div>

          <div class="mt-3 d-none">
            <label for="" class="form-label">Discount Amount</label>
            <input type="text" name="orderbump_discount" class="form-control" value="">
          </div>

          @if (isset($formHolder->upsell_id))
          <div class="mt-3 d-flex align-items-center" style="gap: 20px;">
            <div class='category'>
              <input type="radio" name="switch_upsell" value="on" id="on" checked />
              <label for="on" class="ml-1">On</label>
            </div>
              
            <div class='category'>
                <input type="radio" name="switch_upsell" value="off" id="off" />
              <label for="off">Off</label>
            </div>
          </div>
          @endif

        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info"><i class="bi bi-send"></i> UPDATE</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Modal ThankYou -->
<div class="modal fade @error('product') show @enderror" id="addThankYou" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addThankYouTitle">Add Thank-You to this Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('addThankYouTemplateToForm') }}" method="POST">@csrf
        <div class="modal-body">

          <input type="hidden" name="addThankYou_form_unique_key" id="addThankYou_form_unique_key" class="addThankYou_form_unique_key" value="">

          <div class="mt-3">
            <label for="upsell_product" class="form-label">Select Template</label>
            <select name="thankyou_template_id" data-live-search="true" class="custom-select form-control border btn-dark @error('thankyou_template_id') is-invalid @enderror"
              id="" style="color: black !important;">
              <option value="">Nothing Selected</option>
              @if (count($thankYouTemplates) > 0)

              @foreach ($thankYouTemplates as $template)
                <option value="{{ $template->id }}">{{ $template->template_name }}</option>
              @endforeach

              @endif
      
            </select>

            @error('product')
              <span class="invalid-feedback mb-3" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> SUBMIT</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Modal addAgentModal -->
<div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5" id="addAgentModalLabel">Assign Staff</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('assignStaffToForm') }}" method="POST">@csrf
              <div class="modal-body">
                  
                  <input type="hidden" id="form_id" class="form_id" name="form_id" value="">
                  <div class="d-grid mb-3">
                      <label for="">Select Staff</label>
                      <select name="staff_id" id="" data-live-search="true" class="custom-select form-control border border-dark">
                          <option value="">Nothing Selected</option>

                          @if (count($staffs) > 0)
                            @foreach ($staffs as $staff)
                              <option value="{{ $staff->id }}">{{ $staff->name }} | {{ $staff->id }}</option>
                            @endforeach
                          @endif
                          
                      </select>
                  </div>
              
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addAgentBtn">Assign Staff</button>
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
              <h1 class="modal-title fs-5" id="changeAgentModalLabel">Change Assigned Staff</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('assignStaffToForm') }}" method="POST">@csrf
              <div class="modal-body">
                  
                  <input type="hidden" id="form_id" class="form_id" name="form_id" value="">
                  <div class="d-grid mb-3">
                      <label for="">Select Staff</label>
                      <select name="staff_id" id="changeAgentModalSelect" data-live-search="true" class="custom-select form-control border border-dark">

                          <option value="" selected>Nothing Selected</option>
                          @if (count($staffs) > 0)
                            @foreach ($staffs as $staff)
                              <option value="{{ $staff->id }}">{{ $staff->name }} | {{ $staff->id }}</option>
                            @endforeach
                          @endif
               
                      </select>
                  </div>
              
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addAgentBtn">Assign Staff</button>
              </div>
          </form>
      </div>
  </div>
</div>

@endsection

@section('extra_js')
<script>
  new ClipboardJS('.clipboard-btn');
</script>

<?php if(count($errors) > 0) : ?>
  <script>
      $( document ).ready(function() {
          $('#addOrderbump').modal('show');
      });
  </script>
<?php endif ?>

<script>
  function addOrderbump($form_unique_key="", $form_name="") {
    $('#addOrderbump').modal("show");
    $('#form_unique_key').val($form_unique_key);
    $('#addOrderbumpTitle').text('Add Order-Bump to this Form: '+$form_name);
  }
  function editOrderbump($formHolder="", $orderbump_subheading="") {
    $('#editOrderbumpModal').modal("show");
    $('#editOrderbump_form_unique_key').val($formHolder.unique_key);
    $('#editOrderbump_topic').text('Edit Order-Bump to this Form: '+$formHolder.name);
    $('#editOrderbump_heading').val($formHolder.orderbump.orderbump_heading);
    //$('#editOrderbump_subheading').val('lorem');
    tinyMCE.get('editOrderbump_subheading').setContent($orderbump_subheading);

    var datas = {
        id: $formHolder.orderbump.product.id,
        text: $formHolder.orderbump.product.name
    };
    var newOption = new Option(datas.text, datas.id, false, false);
    $('#editOrderbumpModal').prepend(newOption).trigger('change');

    //$("orderbump_product").val($formHolder.orderbump.product.id).change();
  }

  //upsell side
  function addUpsell($form_unique_key="", $form_name="") {
    $('#addUpsell').modal("show");
    $('#addUpsell_form_unique_key').val($form_unique_key);
    $('#addUpsellTitle').text('Add Up-Sell to this Form: '+$form_name);
  }
  function editUpsell($formHolder="", $upsell_subheading="") {
    $('#editUpsellModal').modal("show");
    $('#editUpsell_form_unique_key').val($formHolder.unique_key);
    $('#editUpsell_topic').text('Edit Up-Sell to this Form: '+$formHolder.name);
    
    //$('#editUpsell_heading').val($formHolder.upsell.upsell_heading);
    //$('#editUpsell_subheading').val($formHolder.upsell.upsell_subheading);
    //tinyMCE.get('editUpsell_subheading').setContent($upsell_subheading);

    var datas = {
        id: $formHolder.upsell.product.id,
        text: $formHolder.upsell.product.name
    };
    var newOption = new Option(datas.text, datas.id, false, false);
    $('#editUpsellModal').prepend(newOption).trigger('change');

    //$("orderbump_product").val($formHolder.orderbump.product.id).change();
  }

  function addThankYouTemplate($form_unique_key="", $form_name="") {
    $('#addThankYou').modal("show");
    $('#addThankYou_form_unique_key').val($form_unique_key);
    $('#addThankYouTitle').text('Add Thank-you template to this Form: '+$form_name);
  }

</script>

<script>
  function addAgentModal($formId="") {
    $('#addAgentModal').modal("show");
    $('.form_id').val($formId);
  }

  function changeAgentModal($formId="") {
    $('#changeAgentModal').modal("show");
    $('.form_id').val($formId);
  }
</script>

<script>
  tinymce.init({
    selector: '.mytextarea',
    height: "200",
  });
</script>

{{-- @if ($errors->has('orderbump_product')) --}}
    {{-- <script type="text/javascript">
        $( document ).ready(function() {
             $('.orderbumModal').modal('show');
        });
    </script> --}}
{{-- @endif --}}
    
@endsection