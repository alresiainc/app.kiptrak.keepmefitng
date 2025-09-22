@extends('layouts.design')
@section('title', 'Serlzo - Logs')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>WhatsApp Logs</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('serlzo.index') }}">WhatsApp Accounts</a></li>
                    <li class="breadcrumb-item active">Logs</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        @if (Session::has('error') || isset($error))
            @php
                $errors = Session::get('error') ?? $error;
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

        @if (Session::has('success') || isset($success))
            @php
                $messages = Session::get('success') ?? $success;
            @endphp

            @if (is_array($messages))
                <div class="alert alert-success text-center">
                    @foreach ($messages as $msg)
                        {{ $msg }},
                    @endforeach
                </div>
            @else
                <div class="alert alert-success text-center">{{ $messages }}</div>
            @endif
        @endif

        <section class="section dashboard">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>WhatsApp Activity Logs</div>
                            <div>
                                <a href="{{ route('serlzo.index') }}"
                                    class="btn btn-outline-primary rounded-pill d-flex px-4">
                                    Back to Dashboard
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (count($logs) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                {{-- <th>Token</th> --}}
                                                <th>Phone</th>
                                                <th>Message Type</th>
                                                <th>Message Content</th>
                                                <th>Message ID</th>
                                                <th>Status</th>
                                                {{-- <th>Response Code</th> --}}
                                                <th>Details</th>
                                                <th>Sent At</th>
                                                {{-- <th>Updated At</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($logs as $log)
                                                @php
                                                    $data = $log['data'];
                                                    $response = $log['response'];

                                                    // Extract data fields
                                                    $token = $log['token'] ?? 'N/A';
                                                    $phone = $data['phone'] ?? 'N/A';
                                                    $messageId = $data['id'] ?? 'N/A';
                                                    $messageBody = $data['body'] ?? 'N/A';

                                                    // Try to determine message type
                                                    $messageType = 'Text';
                                                    if (isset($data['mediaUrl'])) {
                                                        $messageType = 'Media';
                                                    } elseif (isset($data['location'])) {
                                                        $messageType = 'Location';
                                                    } elseif (isset($data['contacts'])) {
                                                        $messageType = 'Contact';
                                                    }

                                                    // Extract response fields
                                                    $responseCode = $response['code'] ?? 'N/A';
                                                    $responseSuccess = $response['success'] ?? false;
                                                    $responseDetails =
                                                        $response['data']['Details'] ?? ($response['message'] ?? 'N/A');
                                                    $timestamp = $response['data']['Timestamp'] ?? 'N/A';

                                                    // Format status badge
                                                    $statusBadge = $responseSuccess
                                                        ? '<span class="badge bg-success">Sent</span>'
                                                        : '<span class="badge bg-danger">Failed</span>';

                                                    // Format timestamp
                                                    $formattedTimestamp =
                                                        $timestamp !== 'N/A'
                                                            ? date('Y-m-d H:i:s', strtotime($timestamp))
                                                            : 'N/A';
                                                    $formattedUpdatedAt = date(
                                                        'Y-m-d H:i:s',
                                                        strtotime($log['updatedAt']),
                                                    );
                                                @endphp
                                                <tr>
                                                    {{-- <td><code>{{ $token }}</code></td> --}}
                                                    <td>{{ $phone }}</td>
                                                    <td>{{ $messageType }}</td>
                                                    <td>
                                                        <div class="message-cell" style="max-width: 250px;">
                                                            <div class="message-preview"
                                                                style="max-height: 60px; overflow-y: auto;"
                                                                title="{{ $messageBody }}">
                                                                {{ $messageBody }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><code>{{ $messageId }}</code></td>
                                                    <td>{!! $statusBadge !!}</td>
                                                    {{-- <td>{{ $responseCode }}</td> --}}
                                                    <td>{{ $responseDetails }}</td>
                                                    <td>{{ $formattedTimestamp }}</td>
                                                    {{-- <td>{{ $formattedUpdatedAt }}</td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Laravel Pagination Links -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="text-muted">
                                            Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of
                                            {{ $logs->total() }} results
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            {{ $logs->links('pagination.custom-pagination') }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-center text-muted">No logs found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
