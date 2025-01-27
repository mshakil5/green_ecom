@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">Contact Us</h3>
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="{{ route('frontend.homepage') }}">Home</a></li>
                                <li class="active" aria-current="page">Contact Us</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="map-section" data-aos="fade-up"  data-aos-delay="0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="mapouter">
                    <div class="gmap_canvas">
                        
                        @if ($companyDetails->google_map)
                            
                        <iframe id="gmap_canvas" src="{{ $companyDetails->google_map }}"></iframe>
                        
                        @else
                            
                        <iframe id="gmap_canvas" src="https://maps.google.com/maps?q=121%20King%20St%2C%20Melbourne%20VIC%203000%2C%20Australia&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="contact-details-wrapper section-top-gap-100" data-aos="fade-up"  data-aos-delay="0">
                    <div class="contact-details">
                        <div class="contact-details-single-item">
                            <div class="contact-details-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="contact-details-content contact-phone">
                                <a href="tel:{{ $companyDetails->phone1 }}">{{ $companyDetails->phone1 }}</a>
                                <a href="tel:{{ $companyDetails->phone2 }}">{{ $companyDetails->phone2 }}</a>
                            </div>
                        </div>
                        <div class="contact-details-single-item">
                            <div class="contact-details-icon">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div class="contact-details-content contact-phone">
                                <a href="mailto:{{ $companyDetails->email1 }}">{{ $companyDetails->email1 }}</a>
                                <a href="{{ $companyDetails->website }}">{{ $companyDetails->website }}</a>
                            </div>
                        </div>
                        <div class="contact-details-single-item">
                            <div class="contact-details-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="contact-details-content contact-phone">
                                <span>{{ $companyDetails->address1 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="contact-social">
                        <h4>Follow Us</h4>
                        <ul>
                            @if($companyDetails->facebook)
                                <li>
                                    <a href="{{ $companyDetails->facebook }}" target="_blank" rel="noopener">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                            @endif
                            @if($companyDetails->twitter)
                                <li>
                                    <a href="{{ $companyDetails->twitter }}" target="_blank" rel="noopener">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if($companyDetails->youtube)
                                <li>
                                    <a href="{{ $companyDetails->youtube }}" target="_blank" rel="noopener">
                                        <i class="fa fa-youtube"></i>
                                    </a>
                                </li>
                            @endif
                            @if($companyDetails->instagram)
                                <li>
                                    <a href="{{ $companyDetails->instagram }}" target="_blank" rel="noopener">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="contact-form section-top-gap-100" data-aos="fade-up"  data-aos-delay="200">
                    <div id="success" class="mb-3">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <h3>Get In Touch</h3>
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="default-form-box mb-20">
                                    <label for="contact-name">Name <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        id="contact-name" 
                                        class="form-control" 
                                        placeholder="Your Name" 
                                        required="required" 
                                        data-validation-required-message="Please enter your name"
                                        @auth 
                                            value="{{ auth()->user()->name }}" 
                                        @endauth
                                    />
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="default-form-box mb-20">
                                    <label for="contact-email">Email <span class="text-danger">*</span></label>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="contact-email" 
                                        class="form-control" 
                                        placeholder="Your Email" 
                                        required="required" 
                                        data-validation-required-message="Please enter your email"
                                        @auth 
                                            value="{{ auth()->user()->email }}" 
                                        @endauth
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="default-form-box mb-20">
                                    <label for="contact-phone">Phone <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        name="phone" 
                                        id="contact-phone" 
                                        class="form-control" 
                                        placeholder="Your Phone" 
                                        required="required" 
                                        data-validation-required-message="Please enter your phone number"
                                        @auth 
                                            value="{{ auth()->user()->phone }}" 
                                        @endauth
                                    />
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="default-form-box mb-20">
                                    <label for="contact-subject">Subject <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        name="subject" 
                                        id="contact-subject" 
                                        class="form-control" 
                                        placeholder="Subject" 
                                        required="required" 
                                        data-validation-required-message="Please enter a subject"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="default-form-box mb-20">
                                    <label for="contact-message">Your Message <span class="text-danger">*</span></label>
                                    <textarea 
                                        name="message" 
                                        id="contact-message" 
                                        class="form-control" 
                                        cols="30" 
                                        rows="10" 
                                        placeholder="Message" 
                                        required="required" 
                                        data-validation-required-message="Please enter your message"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <button class="contact-submit-btn" type="submit">
                                    SEND
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection