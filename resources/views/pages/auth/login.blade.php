<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login :: CRM</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mx-auto">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png?vl" alt="">
                  <span class="d-none d-lg-block project-name"></span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body px-4 py-5">

                  <div class="">
                    <h5 class="card-title text-center pb-0 fs-4">Welcome back!</h5>
                    <p class="text-center small">Please login to your account</p>
                  </div>

                  @if(Session::has('success'))
                    <div class="alert alert-success mb-3 text-center">
                        {{Session::get('success')}}
                    </div>
                  @endif

                  @if(Session::has('login_error'))
                    <div class="alert alert-danger mb-3 text-center fw-bold" style="font-size: 11px;">
                        {{Session::get('login_error')}}
                    </div>
                  @endif

                  <form action="{{ route('loginPost') }}" class="row g-3" method="POST">@csrf

                    <div class="col-12">
                      <label for="email" class="form-label small">Email</label>
                      <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
                      @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror                     
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label small">Password</label>
                      <input type="password" name="password" id="yourPassword" class="form-control @error('password') is-invalid @enderror">
                      @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-between">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                      <div>
                        <a href="forgot">Forgot Password?</a>
                      </div>
                    </div>
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary rounded-pill w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12 d-none">
                      <p class="small mb-0">Don't have account? <a href="register">Create an account</a></p>
                    </div>
                  </form>

                </div>
                
              </div>

              <div class="credits text-center small">
                <p>&copy; <span class="copyright-date"></span> <span class="project-name"></span>. All rights reserved.</p>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <script src="{{asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('/assets/vendor/php-email-form/validate.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('/assets/js/main.js')}}"></script>

  <script>
    // $('#submit').on('submit',function(){
    //   $.post('login').done(function(){
    //     alert('Done')
    //   }).fail(function(){
    //     alert('Error');
    //   })
    // })

  </script>
</body>

</html>