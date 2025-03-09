@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">Green Technology</h3>
                    <div class="breadcrumb-nav">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $data = \App\Models\Corporate::where('id', 1)->first();
@endphp

<div class="about-us-top-area d-none">
    <div class="container">
        <div class="row">
            <div class="col-12" data-aos="fade-up"  data-aos-delay="0">
                {!! $data->description !!}
            </div>
        </div>
    </div>
</div>


<div class="about-us-top-area">
    <div class="container">
        <div class="row">
            <div class="pdf-viewer">
                <iframe src="{{ asset('profile.pdf') }}" style="width: 100%; height: 100vh;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>



@endsection