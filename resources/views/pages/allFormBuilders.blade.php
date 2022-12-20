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
    </style>
@endsection

@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Forms</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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
              <a href="{{ route('newFormBuilder') }}" class="btn btn-primary btn-block glow users-list-clear mb-0">
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
            <div class="float-end text-end">
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
                  
                  <th scope="col">OrderId</th>
                  <th scope="col">Staff Assigned</th>
                  <th scope="col">OrderBump</th>
                  <th scope="col">UpSell</th>
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
                        <a class="badge badge-info" href="{{ route('editNewFormBuilder', $formHolder->unique_key) }}">
                          <i class="bi bi-pencil"></i> Edit</a>
                      </td>
                      {{-- <td>{{ $label->order_subheading }}</td> --}}
                      {{-- <td>
                        @if ($label->order->hasOrderbump())
                          <span class="text-success">Yes</span>
                        @else
                          <span class="text-danger">No</span>
                        @endif
                        
                      </td> --}}
                      {{-- <td>
                        @if ($label->order->hasUpsell())
                          <span class="text-success">Yes</span>
                        @else
                        <span class="text-danger">No</span>
                        @endif
                      </td> --}}
                      <td>
                        <a href="{{ route('singleOrder', $formHolder->order->unique_key) }}" class="badge badge-dark">
                          <!-- < 10 -->
                          @if ($formHolder->order->id < 10) 0000{{ $formHolder->order->id }} @endif
                          <!-- > 10 < 100 -->
                          @if (($formHolder->order->id > 10) && ($formHolder->order->id < 100)) 000{{ $formHolder->order->id }} @endif
                          <!-- > 100 < 1000 -->
                          @if (($formHolder->order->id) > 100 && ($formHolder->order->id < 100)) 00{{ $formHolder->order->id }} @endif
                          <!-- > 1000 < 10000++ -->
                          @if (($formHolder->order->id) > 100 && ($formHolder->order->id < 100)) 0{{ $formHolder->order->id }} @endif
                          
                        </a>
                      </td>

                      
                        @if (isset($formHolder->order->staff_assigned_id))
                            <td>
                              {{ $formHolder->order->staff->name }} <br>
                              <span class="badge badge-dark" onclick="changeAgentModal('{{ $formHolder->order->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Change Staff">
                                <i class="bi bi-plus"></i> <span>Change Staff</span></span>
                            </td>
                        @else
                        <td style="width: 120px">
                          <span class="badge badge-success" onclick="addAgentModal('{{ $formHolder->order->id }}')" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Assign Staff">
                            <i class="bi bi-plus"></i> <span>Assign Staff</span></span> 
                        </td>
                        @endif
                      

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
                        <button class="btn btn-info btn-sm rounded" data-bs-target="#orderbumpEditModal{{ $formHolder->id }}" data-bs-toggle="modal">
                          <i class="bi bi-pencil"></i> Edit</button>

                        <!-- Edit Ordernump-Modal Orderbump -->
                        <div class="modal fade orderbumpEditModal" id="orderbumpEditModal{{ $formHolder->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content bg-white">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5">Edit Order-Bump to this Form</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form action="{{ route('editOrderbumpToForm', $formHolder->unique_key) }}" method="POST">@csrf

                                <div class="modal-body">
                                  
                                  <div class="mt-3">
                                    <label for="" class="form-label">Heading</label>
                                    <input type="text" name="orderbump_heading"  class="form-control" value="{{ $formHolder->orderbump->orderbump_heading }}">
                                  </div>

                                  <div class="mt-3">
                                    <label for="" class="form-label">Sub Heading</label>
                                    <input type="text" name="orderbump_subheading"  class="form-control" value="{{ $formHolder->orderbump->orderbump_subheading }}">
                                  </div>

                                  <div class="mt-3">
                                    <label for="orderbump_product" class="form-label">Select Product Package</label>
                                    <select name="orderbump_product" data-live-search="true" class="custom-select form-control border btn-dark @error('orderbump_product') is-invalid @enderror"
                                              id="" style="color: black !important;">
                                      <option value="{{ $formHolder->orderbump->product->id }}" selected>{{ $formHolder->orderbump->product->name }}</option>
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

                                  <div class="mt-3">
                                    <label for="" class="form-label">Discount Type</label>
                                    <select name="ordernump_discount_type" class="custom-select form-control border btn-dark">
                                      <option value="">Nothing Selected</option>
                                      <option value="fixed">Fixed</option>
                                      <option value="percentage">Percentage</option>
                                    </select>
                                  </div>

                                  <div class="mt-3">
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
                        <!--edit ordernump-modal-end--->
                      </td>

                      @else

                      <td><button class="btn btn-primary btn-sm rounded" data-bs-target="#orderbumpModal{{ $formHolder->id }}" data-bs-toggle="modal">
                        <i class="bi bi-plus"></i> Add</button></td>

                      <!-- Modal Orderbump -->
                      <div class="modal fade orderbumpModal" id="orderbumpModal{{ $formHolder->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content bg-white">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5">Add Order-Bump to this Form</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('addOrderbumpToForm', $formHolder->unique_key) }}" method="POST">@csrf

                              <div class="modal-body">
                                
                                <div class="mt-3">
                                  <label for="" class="form-label">Heading</label>
                                  <input type="text" name="orderbump_heading"  class="form-control" value="">
                                </div>

                                <div class="mt-3">
                                  <label for="" class="form-label">Sub Heading</label>
                                  <input type="text" name="orderbump_subheading"  class="form-control" value="">
                                </div>

                                <div class="mt-3">
                                  <label for="orderbump_product" class="form-label">Select Product Package</label>
                                  <select name="orderbump_product" data-live-search="true" class="custom-select form-control border btn-dark @error('orderbump_product') is-invalid @enderror"
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

                                <div class="mt-3">
                                  <label for="" class="form-label">Discount Type</label>
                                  <select name="ordernump_discount_type" class="custom-select form-control border btn-dark">
                                    <option value="">Nothing Selected</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option>
                                  </select>
                                </div>

                                <div class="mt-3">
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
                      <!--modal-end--->
                      @endif
                      
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

                        <button class="btn btn-info btn-sm rounded" data-bs-target="#upsellEditModal{{ $formHolder->id }}" data-bs-toggle="modal">
                          <i class="bi bi-pencil"></i> Edit</button>

                          <!-- Edit Upsell-Modal -->
                          <div class="modal fade upsellEditModal" id="upsellEditModal{{ $formHolder->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content bg-white">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5">Edit Upsell to this Form</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('editUpsellToForm', $formHolder->unique_key) }}" method="POST">@csrf

                                  <div class="modal-body">
                                    
                                    <div class="mt-3">
                                      <label for="" class="form-label">Heading</label>
                                      <textarea name="upsell_heading" id="upsell_heading" class="form-control" cols="30" rows="3">{{ $formHolder->upsell->upsell_heading }}</textarea>
                                      
                                    </div>

                                    <div class="mt-3">
                                      <label for="" class="form-label">Sub Heading</label>
                                      <textarea name="upsell_heading" class="form-control" id="" cols="30" rows="3">{{ $formHolder->upsell->upsell_subheading }}</textarea>
                                      
                                    </div>

                                    <div class="mt-3">
                                      <label for="upsell_product" class="form-label">Select Template</label>
                                      <select name="upsell_setting_id" data-live-search="true" class="custom-select form-control border btn-dark @error('upsell_product') is-invalid @enderror"
                                                id="" style="color: black !important;">
                                        <option value="{{ $formHolder->upsell->template->id }}" selected>{{ $formHolder->upsell->template->template_code }}</option>
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
                                        <option value="{{ $formHolder->upsell->product->id }}" selected>{{ $formHolder->upsell->product->name }}</option>
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

                                    <div class="mt-3">
                                      <label for="" class="form-label">Discount Type</label>
                                      <select name="upsell_discount_type" class="custom-select form-control border btn-dark">
                                        <option value="">Nothing Selected</option>
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                      </select>
                                    </div>

                                    <div class="mt-3">
                                      <label for="" class="form-label">Discount Amount</label>
                                      <input type="text" name="upsell_discount" class="form-control" value="">
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
                          <!--edit Upsell-modal-end--->
                      </td>
                          
                      @else
                          
                      <td><button class="btn btn-primary btn-sm rounded" data-bs-target="#upsellModal{{ $formHolder->id }}" data-bs-toggle="modal">
                        <i class="bi bi-plus"></i> Add</button></td>

                      <!-- Modal Upsell -->
                      <div class="modal fade @error('product') show @enderror" id="upsellModal{{ $formHolder->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content bg-white">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5">Add Upsell to this Form</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('addUpsellToForm', $formHolder->unique_key) }}" method="POST">@csrf
                              <div class="modal-body">

                                <div class="mt-3">
                                  <label for="" class="form-label">Heading</label>
                                  <textarea name="upsell_heading" class="form-control" id="" cols="30" rows="3"></textarea>
                                  
                                </div>

                                <div class="mt-3">
                                  <label for="" class="form-label">Sub Heading</label>
                                  <textarea name="upsell_subheading" class="form-control" id="" cols="30" rows="3"></textarea>
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

                                <div class="mt-3">
                                  <label for="" class="form-label">Discount Type</label>
                                  <select name="upsell_discount_type" class="custom-select form-control border btn-dark">
                                    <option value="">Nothing Selected</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option>
                                  </select>
                                </div>

                                <div class="mt-3">
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
                      <!--modal-end---->

                      @endif

                      <td><span>{{ $formHolder->order->customer_id ? $formHolder->order->customer->firstname : 'No response' }} {{ $formHolder->order->customer_id ? $formHolder->order->customer->lastname : '' }}</span></td>
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
                          <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash"></i></a>
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



<!-- Modal Upsell -->
<div class="modal fade" id="upsellModal" tabindex="-1" aria-labelledby="upsellModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="upsellModalLabel">Add Upsell to this Form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="">
      <div class="modal-body">
        
        <div class="mt-3">
          <label for="product" class="form-label">Select Product Package</label>
          <select name="agent_assigned" data-live-search="true" class="custom-select form-control border btn-dark @error('agent_assigned') is-invalid @enderror"
                    id="" style="color: black !important;">
            <option value="">Nothing Selected</option>
            @if (count($products) > 0)

              @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
              @endforeach

            @endif
    
          </select>
        </div>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-send"></i> SUBMIT</button>
      </div>
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
          <form action="{{ route('assignStaffToOrder') }}" method="POST">@csrf
              <div class="modal-body">
                  
                  <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                  <div class="d-grid mb-3">
                      <label for="">Select Staff</label>
                      <select name="staff_id" id="" data-live-search="true" class="custom-select form-control border border-dark">
                          <option value="">Nothing Selected</option>

                          @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }} | kpu-{{ $staff->id }}</option>
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
              <h1 class="modal-title fs-5" id="changeAgentModalLabel">Change Assigned Staff</h1>
              <button type="button" class="btn-close"
                  data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('assignStaffToOrder') }}" method="POST">@csrf
              <div class="modal-body">
                  
                  <input type="hidden" id="order_id" class="order_id" name="order_id" value="">
                  <div class="d-grid mb-3">
                      <label for="">Select Staff</label>
                      <select name="staff_id" id="changeAgentModalSelect" data-live-search="true" class="custom-select form-control border border-dark">
                          <option value="" selected>Nothing Selected</option>
                          
                          @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }} | kpu-{{ $staff->id }}</option>
                          @endforeach
                          
                      </select>
                  </div>
                
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary addAgentBtn">Update Assigned Staff</button>
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

<script>
  function addAgentModal($orderId="") {
    $('#addAgentModal').modal("show");
    $('.order_id').val($orderId);
  }

  function changeAgentModal($orderId="") {
    $('#changeAgentModal').modal("show");
    $('.order_id').val($orderId);
  }
</script>

{{-- @if ($errors->has('orderbump_product')) --}}
    {{-- <script type="text/javascript">
        $( document ).ready(function() {
             $('.orderbumModal').modal('show');
        });
    </script> --}}
{{-- @endif --}}
    
@endsection