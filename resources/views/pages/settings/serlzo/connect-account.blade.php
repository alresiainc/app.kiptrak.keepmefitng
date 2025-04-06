@extends('layouts.design')
@section('title')
    Login / Register
@endsection
@section('extra_css')
    <style>
        .toggle-link {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }
    </style>
@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Login or Register</h1>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('error'))
                                @php $errors = Session::get('error'); @endphp

                                @if (is_array($errors))
                                    @foreach ($errors as $err)
                                        <div class="alert alert-danger text-center">{{ $err }}</div>
                                    @endforeach
                                @else
                                    <div class="alert alert-danger text-center">{{ $errors }}</div>
                                @endif
                            @endif

                            <div id="login-form">
                                <h4 class="text-center">Login</h4>
                                <form action="{{ route('serlzo.login') }}" method="POST">@csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Login</button>
                                    <p class="mt-3 text-center">Don't have an account? <span class="toggle-link"
                                            onclick="toggleForms()">Register here</span></p>
                                </form>
                            </div>

                            <div id="register-form" class="hidden">
                                <h4 class="text-center">Register</h4>
                                <form action="{{ route('serlzo.register') }}" method="POST">@csrf
                                    <div class="mb-3">
                                        <label for="firstname" class="form-label">First Name</label>
                                        <input type="text" name="firstname"
                                            class="form-control @error('firstname') is-invalid @enderror" required>
                                        @error('firstname')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="lastname" class="form-label">Last Name</label>
                                        <input type="text" name="lastname"
                                            class="form-control @error('lastname') is-invalid @enderror" required>
                                        @error('lastname')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Register</button>
                                    <p class="mt-3 text-center">Already have an account? <span class="toggle-link"
                                            onclick="toggleForms()">Login here</span></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('extra_js')
    <script>
        function toggleForms() {
            document.getElementById('login-form').classList.toggle('hidden');
            document.getElementById('register-form').classList.toggle('hidden');
        }
    </script>
@endsection
