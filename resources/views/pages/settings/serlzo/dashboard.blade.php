@extends('layouts.design')
@section('title', 'Serlzo - Dashboard')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>WhatsApp Accounts</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
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

        @if (Session::has('success') || isset($success))
            @php
                $messages = Session::get('success') ?? $success;
                // dd($errors);
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
                            <div>
                                Your Connected WhatsApp Accounts</div>
                            <div>
                                <button class="btn btn-outline-primary rounded-pill d-flex px-4"
                                    data-bs-target="#initializeModal" data-bs-toggle="modal">Initialize
                                    New Session</button>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (count($accounts) > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accounts as $account)
                                            <tr id="account-{{ $account['token'] }}">
                                                <td>{{ $account['publicName'] ?? $account['username'] }}</td>


                                                <td>
                                                    <span
                                                        class="badge {{ $account['status'] == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $account['status'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{-- <div id="status-{{ $account['token'] }}">
                                                        Checking...
                                                    </div> --}}
                                                    <button class="btn btn-sm btn-primary connect-btn"
                                                        data-token="{{ $account['token'] }}" data-bs-toggle="modal"
                                                        data-bs-target="#qrModal">
                                                        Checking...
                                                    </button>
                                                    <form method="POST"
                                                        action="{{ route('serlzo.delete', $account['token']) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-muted">No connected accounts found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal initializeModal -->
            <div class="modal fade" id="initializeModal" tabindex="-1" aria-labelledby="initializeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="initializeModalLabel">Initialize New Session</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('serlzo.initialize') }}" method="post">
                            @csrf
                            <div class="modal-body">


                                <div class="form-group">
                                    <label for="">Username</label>
                                    <div class="d-flex align-items-center product-container mb-2 w-100">
                                        <input type="text" id="username" name="username" class="form-control border">

                                    </div>
                                </div>



                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary addAgentBtn">Initialize</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan QR Code to Connect</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center d-flex justify-content-center align-items-center" id="qrCodeContainer"
                    style="min-height: 400px;">
                    <img id="qrCodeImage" src="{{ asset('assets/img/loading.gif') }}" alt="QR Code" class="img-fluid">

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Status Check & QR Code Refresh -->
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkAccountStatus = async (token) => {
                try {
                    let response = await fetch(`/serlzo-setting/status/${token}`);
                    let data = await response.json();

                    // let statusElem = document.getElementById(`status-${token}`);
                    let btn = document.querySelector(`button[data-token="${token}"]`);

                    console.log("Checking status for:", token, "Response:", data);

                    if (!btn) {
                        console.error(`Missing elements for token: ${token}`);
                        return;
                    }

                    if (data.isConnected) {
                        // Update status to "Connected"
                        // statusElem.classList.replace("bg-secondary", "bg-success");
                        // statusElem.classList.replace("bg-danger", "bg-success");
                        // statusElem.innerText = "Connected";

                        // Change button to "Connected" & disable it
                        btn.innerText = "Connected";
                        btn.classList.replace("btn-primary", "btn-success");
                        btn.disabled = true;
                    } else {
                        // Update status to "Not Connected"
                        // statusElem.classList.replace("bg-secondary", "bg-danger");
                        // statusElem.classList.replace("bg-success", "bg-danger");
                        // statusElem.innerText = "Not Connected";

                        // Show "Connect" button (if not already)
                        btn.innerText = "Connect";
                        btn.classList.replace("btn-success", "btn-primary");
                        btn.disabled = false;
                    }
                } catch (error) {
                    console.error("Error checking status:", error);
                }
            };

            document.querySelectorAll(".connect-btn").forEach(button => {
                button.addEventListener("click", async function() {
                    let token = this.getAttribute("data-token");
                    let qrImg = document.getElementById("qrCodeImage");
                    let modal = document.getElementById("qrModal");

                    if (!token || !qrImg || !modal) {
                        console.error("Missing required elements.");
                        return;
                    }

                    let intervalId; // Store interval ID to clear previous ones
                    const loadQRCode = async () => {
                        try {
                            const response = await fetch(
                                `/serlzo-setting/generate-qr-code/${token}`);
                            const data = await response.json();

                            if (data.qrCode) {
                                qrImg.src = `${data.qrCode}`;
                            } else {
                                qrImg.src =
                                    "{{ asset('assets/img/loading.gif') }}"; // Clear image if an error occurs
                                console.error("Failed to load QR Code:", data.error ||
                                    "Unknown error");
                            }
                        } catch (error) {
                            console.error("Error fetching QR Code:", error);
                        }
                    };

                    // Load QR code immediately
                    await loadQRCode();

                    // Clear existing intervals to prevent duplicates
                    if (qrImg.dataset.intervalId) {
                        clearInterval(qrImg.dataset.intervalId);
                    }

                    // Set interval to refresh QR code every 60 seconds
                    intervalId = setInterval(loadQRCode, 60000);
                    qrImg.dataset.intervalId = intervalId;

                    // Show modal
                    modal.style.display = "block";

                    checkAccountStatus();
                });
            });


            document.querySelectorAll("tr[id^='account-']").forEach(row => {
                let token = row.id.replace("account-", "");
                checkAccountStatus(token);
            });
        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const connectedStatusMap = {};
            const checkAccountStatus = async (token, onModal = false) => {
                try {
                    let response = await fetch(`/serlzo-setting/status/${token}`);
                    let data = await response.json();

                    let btn = document.querySelector(`button[data-token="${token}"]`);
                    console.log("Checking status for:", token, "Response:", data);

                    if (!btn) {
                        console.error(`Missing elements for token: ${token}`);
                        return;
                    }

                    if (data.isConnected) {
                        // Change button to "Connected" & disable it
                        btn.innerText = "Connected";
                        btn.classList.replace("btn-primary", "btn-success");
                        btn.disabled = true;

                        // Only alert once per token
                        if (onModal && !connectedStatusMap[token]) {
                            connectedStatusMap[token] = true; // Mark as connected
                            $('#qrModal').modal('hide');
                            // let modal = document.getElementById("qrModal");
                            // if (modal) {
                            //     modal.style.display = "none";
                            // }
                            alert("Connection successful! The device is now connected.");
                        }

                    } else {
                        // Reset flag so alert works next time user tries
                        connectedStatusMap[token] = false;
                        btn.innerText = "Connect";
                        btn.classList.replace("btn-success", "btn-primary");
                        btn.disabled = false;
                    }
                } catch (error) {
                    console.error("Error checking status:", error);
                }
            };


            document.querySelectorAll(".connect-btn").forEach(button => {
                button.addEventListener("click", async function() {
                    let token = this.getAttribute("data-token");
                    let qrImg = document.getElementById("qrCodeImage");
                    let modal = document.getElementById("qrModal");

                    if (!token || !qrImg || !modal) {
                        console.error("Missing required elements.");
                        return;
                    }

                    const loadingImage = "{{ asset('assets/img/loading.gif') }}";

                    const loadQRCode = async () => {
                        try {
                            const response = await fetch(
                                `/serlzo-setting/generate-qr-code/${token}`);
                            const data = await response.json();

                            if (data.qrCode) {
                                qrImg.src = data.qrCode;
                            } else {
                                qrImg.src = loadingImage;
                                console.error("Failed to load QR Code:", data.error ||
                                    "Unknown error");
                            }
                        } catch (error) {
                            console.error("Error fetching QR Code:", error);
                            qrImg.src = loadingImage;
                        }
                    };

                    // Show modal first
                    // modal.style.display = "block";
                    $('#qrModal').modal('show');

                    // Clear previous intervals
                    if (qrImg.dataset.intervalId) {
                        clearInterval(qrImg.dataset.intervalId);
                    }

                    // Initial load
                    await loadQRCode();

                    // ✅ Check if the image is still loader after a short delay (5s), then reload
                    setTimeout(async () => {
                        if (qrImg.src.includes("loading.gif")) {
                            console.warn(
                                "QR code still showing loader, retrying fetch..."
                            );
                            await loadQRCode();
                        }
                    }, 5000);

                    // Refresh QR every 60 seconds
                    const intervalId = setInterval(loadQRCode, 60000);
                    qrImg.dataset.intervalId = intervalId;

                    // Periodic connection check every 5 seconds
                    const checkConnectionInterval = setInterval(() => {
                        checkAccountStatus(token, true);
                    }, 5000);

                    // Save intervals for cleanup later if needed
                    qrImg.dataset.checkConnectionInterval = checkConnectionInterval;
                });

            });

            // Check status for each account initially
            document.querySelectorAll("tr[id^='account-']").forEach(row => {
                let token = row.id.replace("account-", "");
                checkAccountStatus(token);
            });
        });
    </script>

@endsection
