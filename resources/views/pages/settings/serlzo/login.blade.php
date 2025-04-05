@extends('layouts.design')
@section('title')
    Serlzo - Login
@endsection

@section('content')
    <main id="main" class="main">
        <form method="POST" action="{{ route('serlzo.login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </main>
@endsection
