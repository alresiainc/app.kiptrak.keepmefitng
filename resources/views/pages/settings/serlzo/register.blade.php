@extends('layouts.design')
@section('title')
    Serlzo - Register
@endsection

@section('content')
    <form method="POST" action="{{ route('serlzo.register') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
@endsection
