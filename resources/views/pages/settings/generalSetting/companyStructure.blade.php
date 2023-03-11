@extends('layouts.design')
@section('title')Organizational Chart @endsection
@section('extra_css')
    <style>
        .right-line {
            border-right: 5px #ccc solid;
            height:2em
        }

        .top-line {
            border-top: 5px #ccc solid;
        }

        .halved {
            width: 50%;
            float:left;
        }
    </style>
@endsection 
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Organizational Chart</h1>
      <div class="float-end text-end d-none"><a href="{{ route('addPermission') }}"><button class="btn btn-sm btn-dark rounded-pill">
        <i class="bi bi-plus"></i> <span>Add</span></button></a></div>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Organizational Chart</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard mb-3">
      <div class="row">
        <div class="col-md-12">
          <a href="" class="badge badge-dark"></a>
        </div>
      </div>
    </section>

    @if(Session::has('success'))
    <div class="alert alert-success mb-3 text-center">
        {{Session::get('success')}}
    </div>
    @endif

    @if(Session::has('role_error'))
    <div class="alert alert-danger mb-3 text-center">
        {{Session::get('role_error')}}
    </div>
    @endif

    {{-- <div class="card card-right-border shadow">
        <div class="card-body p-2 text-center">Empty</div>
    </div> --}}

    <section>
        <div class="container text-center">
            <div class="row">
              <div class="col-12"><span class="border shadow px-1"><b>C.E.O</b></span></div>
            </div>
            <!--one-straight-line--->
            <div class="row">
              <div class="col-6 right-line"></div>
              <div class="col-6"></div>
            </div>
            <!--one-straight-line--->

            <div class="row">
                <div class="col-12"><span class="border shadow px-1 fw-bold" style="font-size: 13px;">Manager / Admin</span></div>
            </div>
            <!--one-straight-line--->
            <div class="row">
                <div class="col-6 right-line"></div>
                <div class="col-6"></div>
            </div>
              <!--one-straight-line--->

            <div class="row">
              <div class="col-3 right-line"></div>
              <div class="col-6 right-line top-line"></div>
              <div class="col-3 right-line top-line d-none"></div>
              <div class="col-3"></div>
            </div>

            <div class="row">
                <div class="col-6"><span class="border shadow px-1 fw-bold" style="font-size: 13px;">Customer Service</span></div>
                <div class="col-6"><span class="border shadow px-1 fw-bold" style="font-size: 13px;">Creative Hub</span></div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-6 right-line"></div>
                        <div class="col-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-3 right-line"></div>
                        <div class="col-6 right-line top-line"></div>
                        <div class="col-3 right-line top-line d-none"></div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-6"><span class="border shadow px-1 fw-bold" style="font-size: 13px;">Customer Support One</span></div>
                        <div class="col-6"><span class="border shadow px-1 fw-bold" style="font-size: 13px;">Customer Support Two</span></div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="row">
                        <div class="col-6 right-line"></div>
                        <div class="col-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-3 right-line"></div>
                        <div class="col-6 right-line top-line"></div>
                        <div class="col-3 right-line top-line d-none"></div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-6"><span class="border shadow px-1 fw-bold" style="font-size: 13px;">Graphics Designer / Video Editor</span></div>
                        <div class="col-6"><span class="border shadow px-1 fw-bold" style="font-size: 13px;">Digital Marketer / Content Creator</span></div>
                    </div>
                </div>
            </div>
            
            <!------------------------------------------>
            <div class="row d-none">
                <div class="col-3 right-line"></div>
                <div class="col-3 right-line top-line"></div>
                <div class="col-3 right-line top-line"></div>
                <div class="col-3"></div>
            </div>
            <div class="row d-none">
              <div class="col-2"></div>
              <div class="col-2">Child</div>
              <div class="col-4">Bigger Child</div>
              <div class="col-2">Child</div>
              <div class="col-2"></div>
            </div>
            <div class="row d-none">
              <div class="col-6 right-line"></div>
              <div class="col-6"></div>
            </div>
            <div class="row d-none">
              <div class="col-3 p-0">
                <div class="halved right-line"></div>
                <div class="halved top-line"></div>
              </div>
              <div class="col-3 p-0 d-none">
                <div class="halved right-line top-line"></div>
                <div class="halved top-line"></div>
              </div>
              <div class="col-3 p-0 d-none">
                <div class="halved right-line top-line"></div>
                <div class="halved top-line"></div>
              </div>
              <div class="col-3 p-0 d-none">
                <div class="halved right-line top-line"></div>
                <div class="halved"></div>
              </div>
            </div>
            <div class="row d-none">
              <div class="col-3" contenteditable="true">GrandChild</div>
              <div class="col-3">GrandChild</div>
              <div class="col-3">GrandChild</div>
              <div class="col-3">GrandChild</div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

@endsection