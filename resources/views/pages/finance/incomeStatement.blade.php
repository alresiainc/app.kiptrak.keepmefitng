@extends('layouts.design')
@section('title')Inventory @endsection
@section('extra_css')@endsection

@section('content')
    
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Inventory Management</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Inventory Management</li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <!-- Alert -->
  <div id="liveAlertPlaceholder d-none"></div>
  <!-- /Alert -->

  <div class="text-lg-end text-center mb-3 d-none">
    <div class="btn-group" role="group" aria-label="Basic example">
      <button type="button" class="btn btn-sm btn-light-success active">
        Today
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        Weekly
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        Monthly
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        Yearly
      </button>
      <button type="button" class="btn btn-sm btn-light-success">
        All
      </button>
    </div>
  </div>
  <hr />

  @if(Session::has('error'))
  <div class="alert alert-danger mb-3 text-center">
      {{Session::get('error')}}
  </div>
  @endif

  <section class="users-list-wrapper">
    <div class="users-list-filter px-1">
      <form action="{{ route('incomeStatementQuery') }}" method="POST">@csrf
        <div class="row border rounded py-2 mb-2">

          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">Start Date</label>
            <fieldset class="form-group">
              <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="" value="{{ $start_date != '' ? $start_date : '' }}">
              @error('start_date')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
            </fieldset>
          </div>
          
          <div class="col-12 col-md-6 col-lg-3 mb-3">
            <label for="">End Date</label>
            <fieldset class="form-group">
              <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="" value="{{ $end_date != '' ? $end_date : '' }}">
              @error('end_date')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
            </fieldset>
          </div>

          <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end mb-3">
            <div class="d-grid w-100">
              <button class="btn btn-primary btn-block glow users-list-clear mb-0"><i class="bx bx-plus"></i>Submit</button>
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end mb-3">
            <div class="d-grid w-100">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-sm btn-light-success">
                  CSV
                </button>
                <button type="button" class="btn btn-sm btn-light-success">
                  EXCEL
                </button>
                <button type="button" class="btn btn-sm btn-light-success">
                  PDF
                </button>
                <button type="button" class="btn btn-sm btn-light-success">
                  WORD
                </button>
              </div>
            </div>
          </div>
          
        </div>
      </form>
    </div>

  </section>

  
  <section class="section">
    <div class="row">

      @if ($start_date != '')
      <div class="col-md-12">
        <div class="text-center">
          <h5>From: <span class="badge badge-info">{{ $start_date_info }}</span> To: <span class="badge badge-info">{{ $end_date_info }}</span>
            @if ($daysDiff != 0)

              @if ($daysDiff == 1)
                ({{ $daysDiff }} day)
              @else
                ({{ $daysDiff }} days)
              @endif
            
            @endif
            
          </h5>
        </div>
      </div> 
      @endif
      

      <div class="col-md-12">
        <div class="card card-top-border border-top-primary">

          <div class="card-body">
            <div class="card-title">Revenue ({{ $currency }})</div>
            <div class="table table-responsive">
              <table id="stock-table" class="table table-striped" style="width: 100%">
                <thead>
                    <tr class="bg-primary text-white">
                        <td>Sales</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <th scope="col">Total Sales</th>
                        <th scope="col">{{ number_format((float)$sales_sum, 2, '.', ',') }}</th>
                    </tr>
                    
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-body">
            <div class="card-title">Expenses</div>
            <div class="table table-responsive">
              <table id="stock-table" class="table table-striped" style="width: 100%">
                <thead>
                    <tr class="bg-primary text-white">
                        <td>Purchases (Cost of Goods)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Total Purchases</th>
                        <th>{{ number_format((float)$purchase_sum, 2, '.', ',') }}</th>
                    </tr>


                    <tr class="bg-primary text-white">
                        <td>Operating Expenses:</td>
                        <td></td>
                    </tr>

                    @foreach ($expenses_by_category as $expense)
                    <tr>
                      <td>{{ $expense->category->name }}</td>
                      <td>{{ number_format((float)$expense->amount_spent, 2, '.', ',') }}</td>
                    </tr>
                    @endforeach
                    
                    <tr>
                        <th>Total Operating Expenses</th>
                        <th>{{ number_format((float)$expense_sum, 2, '.', ',') }}</th>
                    </tr>

                </thead>
                <tbody>

                    <tr class="bg-danger">
                        <th scope="col" class="text-white">Total Expenses</th>
                        <th scope="col" class="text-white">{{ number_format((float)$total_expenses, 2, '.', ',') }}</th>
                    </tr>
                    
                </tbody>
              </table>
            </div>

            <div class="table table-responsive">
              <table id="stock-table" class="table table-striped" style="width: 100%">
                
                <tbody>

                    <tr class="bg-success">
                        <th scope="col" class="text-white">Net Profit</th>
                        <th scope="col" class="text-white">{{ number_format((float)$net_profit, 2, '.', ',') }}</th>
                    </tr>
                    
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <hr />

</main>

@endsection

@section('extra_js')
@endsection