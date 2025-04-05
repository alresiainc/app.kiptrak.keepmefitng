@extends('layouts.design')
@section('title')
    Serlzo - QR Code
@endsection
@section('content')
    @if (isset($qrCode))
        <img src="{{ $qrCode }}" alt="QR Code">
    @else
        <p>{{ $message ?? 'QR Code not available' }}</p>
    @endif
@endsection
