@extends('layouts.design')
@section('title')
    Integrations
@endsection

@section('extra_css')
    <style>
        .camera {
            cursor: pointer;
        }

        .integration-card {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .logo {
            max-width: 80px;
            margin-right: 20px;
        }
    </style>
@endsection

@section('content')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        const download = () => {
            html2canvas(document.querySelector('#upsell-section')).then(canvas => {
                document.getElementById('output').appendChild(canvas);
            });
        }
    </script>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Integrations</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/">Settings</a></li>
                    <li class="breadcrumb-item active">Integrations</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <hr>
        <section>
            <div class="row view" id="upsell-section">
                <div class="col-md-12">
                    <article class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <!-- Integration Card Starts -->
                                    <div class="integration-card">
                                        <div>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/WordPress_logo.svg"
                                                alt="WordPress Logo" class="logo">
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <h4>WordPress Integration</h4>
                                            <p>Integrate your site effortlessly with WordPress using our custom plugin.
                                                Enhance functionality and customize your experience directly from your
                                                WordPress dashboard.</p>
                                            <a href="{{ route('wordpress.plugin.download', 'kiptrak-backend') }}"
                                                style=" display: inline-block;"
                                                class="btn btn-outline-primary rounded-pill">
                                                <i class="bi bi-download"></i><span class="ms-1">Download Plugin</span>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Integration Card Ends -->
                                </div>
                            </div> <!-- row.// -->
                        </div> <!-- card-body end.// -->
                    </article>
                </div>
            </div>
        </section>
    </main><!-- End #main -->
@endsection

@section('extra_js')
@endsection
