@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">Privacy And Policy</h3>
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="{{ route('frontend.homepage') }}">Home</a></li>
                                <li class="active" aria-current="page">Privacy And Policy</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="about-us-top-area">
    <div class="container">
        <div class="row">
            <div class="col-12" data-aos="fade-up"  data-aos-delay="0">
                {!! $companyDetails->privacy_policy !!}
            </div>
        </div>
    </div>
</div>

@endsection