<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, user-scalable=0" name="viewport">

  <title>@yield('title') :: CRM</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('/assets/img/favicon.png')}}" rel="icon">
  <link href="{{asset('/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

  <link href="{{asset('/assets/css/select2.min.css')}}" rel="stylesheet">

  <!---my-files--->
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css"> --}}
  <link href="{{asset('/myassets/css/jquery.fancybox.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('/assets/css/style.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/css/colors.css')}}" rel="stylesheet" />
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"/>
  

  @yield('extra_css')

</head>



<body>

  <!-- ======= Header ======= -->
  @include('layouts.header')
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  @include('layouts.sidebar')
  <!-- End Sidebar-->

  <!-- main -->
  @yield('content')
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  @include('layouts.footer')
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
  {{-- @apexchartsScripts --}}
  {{-- <script src="{{asset('/assets/vendor/apexcharts/apexcharts.min.js')}}"></script> --}}
  <script src="{{asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/charts/chart.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/quill/quill.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{asset('/assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/php-email-form/validate.js')}}"></script>

  <!--enquire on this js-->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-*.min.js"></script> --}}

  <script src="{{asset('/assets/js/select2.min.js')}}"></script>

  <script
      type="text/javascript"
      charset="utf8"
      src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"
    ></script><!--imp-->
    <script
      type="text/javascript"
      charset="utf8"
      src="//cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"
    ></script>
    <script
      type="text/javascript"
      charset="utf8"
      src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"
    ></script>
    <script
      type="text/javascript"
      charset="utf8"
      src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"
    ></script>
    <script
      type="text/javascript"
      charset="utf8"
      src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"
    ></script>
    
    <script
      type="text/javascript"
      charset="utf8"
      src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"
    ></script>
    <script
      type="text/javascript"
      charset="utf8"
      src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"
    ></script>
    <script
      type="text/javascript"
      charset="utf8"
      src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"
    ></script>

  <!--my files-->
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script> --}}
  <script src="{{asset('/myassets/js/jquery.fancybox.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('/assets/js/main.js')}}"></script>
  <script src="{{asset('/assets/js/navigation.js')}}"></script>


  <!--------------------------------------------------------------------->
  
    

    

  @yield('extra_js')

  <script type="text/javascript">
    $(".select2").select2();
  </script>

  <script>

    $(document).ready(function () {
      $('.custom-table').DataTable();
      $('.custom-select').selectpicker();
    });
  </script>
      
</body>

</html>