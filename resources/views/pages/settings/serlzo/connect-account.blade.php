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
                            @if (Session::has('error') || isset($error))
                                @php
                                    $errors = Session::get('error') ?? $error;
                                    // dd($errors);
                                @endphp

                                @if (is_array($errors))
                                    <div class="alert alert-danger text-center">
                                        @foreach ($errors as $err)
                                            {{ $err }},
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-danger text-center">{{ $errors }}</div>
                                @endif
                            @endif

                            <div id="login-form">
                                <h4 class="text-center">Login</h4>
                                <form action="{{ route('serlzo.login') }}" method="POST">@csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>

                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required>

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
                                        <input type="text" name="firstname" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="lastname" class="form-label">Last Name</label>
                                        <input type="text" name="lastname" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="business_name" class="form-label">Business Name</label>
                                        <input type="text" name="business_name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" name="country" class="form-control" value="Nigeria" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">Register</button>

                                    <p class="mt-3 text-center">
                                        Already have an account? <span class="toggle-link" onclick="toggleForms()">Login
                                            here</span>
                                    </p>
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
