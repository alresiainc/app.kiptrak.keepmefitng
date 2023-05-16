@php
    $newOrders = \App\Models\SoundNotification::newOrders();
    $pendingOrders = \App\Models\SoundNotification::pendingOrders();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, user-scalable=0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') :: Kiptrak CRM</title>
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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" />

  <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css">

  <!-- Template Main CSS File -->
  <link href="{{asset('/assets/css/style.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/css/colors.css')}}" rel="stylesheet" />
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"/>
  
  
<style>
    @media only screen and (max-width: 600px) {
      .logo img {
        max-width: 60px;
      }
    }
    .btn {
      background-color: #04512d !important;
      border-color: #04512d !important;
      color: #fff !important;
    }
  .btn:hover {
    background-color: #fff !important;
    border-color: #04512d !important;
    color: #04512d !important;
  }
</style>
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

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script><!--imp-->
  <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

  <!--my files-->
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script> --}}
  <script src="{{asset('/myassets/js/jquery.fancybox.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.2.0/js/dataTables.dateTime.min.js"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('/assets/js/main.js')}}"></script>
  <script src="{{asset('/assets/js/navigation.js')}}"></script>

  <!--------------------------------------------------------------------->
  
@yield('extra_js')

  <script>
      $('#mobile-hamburger').click(function(){
          document.querySelector('body').classList.toggle('toggle-sidebar');
      })
      $('#desktop-hamburger').click(function(){
          // document.querySelector('body').classList.toggle('toggle-sidebar');
          $("body").toggleClass("toggle-sidebar");
      })
      
  </script>

  <script type="text/javascript">
    $(".select2").select2();
  </script>

  <!---start & end date field -->
  <script>

    $(document).ready(function () {
      //disable default alpha
      // Create date inputs
      minDate = new DateTime($('#min'), {
          format: 'MMMM Do YYYY'
      });
      maxDate = new DateTime($('#max'), {
          format: 'MMMM Do YYYY'
      });

      // DataTables initialisation
      var table = $('.custom-table').DataTable({
        "bSort" : false,
        dom: 'Blfrtip',
        buttons: [
          'pdf', 'print', 'excel', 'csv', 'copy' 
        ],
          
      });

      // Refilter the table
      $('#min, #max').on('change', function () {
        table.draw();
      });

      $('.custom-select').selectpicker();
    });
  </script>

  <!---soundNotification -->
  <script>
    var timeInterval = 300000; //5mins
    var sound_notification = "{{ asset('/assets/audio/sound_notification.mp3') }}";

    //initially
    // if (localStorage.getItem("sound_value") === null) {
    //   localStorage.setItem("sound_value", 0);
    // } else {
    //   var stored_sound_value = localStorage.getItem("sound_value");
    // }

    var sound_value = $('.sound_value').val();
    
    // var soundNotification = function() {
    //   $.ajax({
    //     url: "{{ route('soundNotification') }}",
    //     type: 'GET',
    //     dataType: 'json',
    //     headers: {
    //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     success: function(resp) {
    //       if(resp.status){
    //         $('.alarm_count').text(resp.count);
    //         //console.log(resp.data)
    //         var notes = resp.data;

    //         $("ul.messages notes").html('');

    //         $('ul.messages').append('<li class="dropdown-header">You have <span class="alarm_count">'+resp.count+'</span> new messages<a href="/orders/new_from_alarm"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a></li><li><hr class="dropdown-divider" /></li>')

    //           $.each(notes,function(key,note){
                
    //               $('ul.messages').append('<li class="message-item" onclick="deleteNotification('+note.id+')"><div><h4>'+note.topic+' #000'+note.order_id+'</h4><p>'+note.content+'</p><p>'+momentsAgo(note.created_at)+'</p></div></li><li><hr class="dropdown-divider"/></li>');
    //           });

              
    //         var audio = new Audio(sound_notification);

    //         $("body").hover(function(){
    //           // audio.play();
    //         });

    //       } else {
    //         console.log('no')
    //       }
    //     },
    //     error: function(data){
    //       console.log(data);
    //     }
    //   });
    // }
    
    $(document).ready(function(){
          
      $('body').click(function(){

        if (!localStorage.hasOwnProperty("sound_value")) {
          localStorage.setItem("sound_value", 0);
        } else {
          var stored_sound_value = localStorage.getItem("sound_value");

          if (($('.sound_value').val() == 0) || (sound_value == stored_sound_value)) {
            return true;
          } else {
            localStorage.setItem("sound_value", sound_value);
            var audio = new Audio(sound_notification);
            audio.play();
          }
          
        }
          
      });
      
    });

  </script>
      
</body>

</html>