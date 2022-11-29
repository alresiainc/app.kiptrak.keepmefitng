@extends('layouts.design')
@section('title')Form Builder @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Form Builder</h1>
      <nav>
        <div class="d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Add Form</li>
            </ol>

            <input type="text" id="form-name" class="form-control me-5" value="" placeholder="Form name | optional" style="width: 30%;">

            <button type="button" id="saveData" class="btn btn-success" style="width: 30%;">Save Form</button>
        </div>
        
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
            <div class="card-header d-none">
                <div class="d-flex justify-content-center">
                    <label for="" class="form-label btn btn-outline border d-flex align-items-center me-3">
                        <span class="me-1 fw-bolder">Contact/Delivery Info</span><input type="radio" name="forminfo" id="contact-info"
                        class="contact-check" checked/>
                    </label>
                    <label for="" class="form-label btn btn-outline border d-flex align-items-center me-3">
                        <span class="me-1 fw-bolder">Product/Package Info</span><input type="radio" name="forminfo" id="product-info" class="contact-check"/>
                    </label>
                </div>
            </div>
            <div class="card-body">
                <div id="build-wrap"></div>
              
              
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection

@section('extra_js')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> --}}
<script src="{{ asset('/assets/js/jquery-ui.min.js') }}"></script>
{{-- <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script> --}}
<script src="{{ asset('/assets/js/form-builder.min.js') }}"></script>
<script>
    document.getElementById("contact-info").addEventListener("click", () => {
        // $( "#product-info-btn" ).prop( "disabled", true );
    });
    document.getElementById("product-info").addEventListener("click", () => {
        alert('123');
    });
    
</script>

<script>
    jQuery(($) => {
  const fbEditor = document.getElementById("build-wrap");
  const formBuilder = $(fbEditor).formBuilder();

  document.getElementById("saveData").addEventListener("click", () => {
    console.log("external save clicked");
    const form_name = $('#form-name').val();
    const result = formBuilder.actions.save();
    console.log("result:", result);

    //grab all total
    
    $.ajax({
        type:'get',
        url:'/form-builder-save',
        data:{result:result, form_name:form_name
            },
        success:function(resp){
            console.log(resp)
            if (resp.data == 'error') {
              alert('Error, this form name already exist');
            } else {
              $('#form-name').val(resp.data.form_name)
              alert('Saved Successfully');
            }
            
            
                
        },error:function(){
            alert("Error");
        }
    });

  });
});
</script>
@endsection