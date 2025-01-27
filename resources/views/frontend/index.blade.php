@extends('frontend.layouts.app')

@section('content')

@php
    $company = \App\Models\CompanyDetails::select('company_logo')->first();
@endphp


    <!-- Intro Slider Start-->
    @if($section_status->slider == 1 && count($sliders) > 0)
    <div class="hero-area">
        <div class="hero-area-wrapper hero-slider-dots fix-slider-dots">
            @foreach($sliders as $slider)
                <div class="hero-area-single">
                    <div class="hero-area-bg">
                        <img class="hero-img" src="{{ asset('images/slider/' . $slider->image) }}" alt="{{ $slider->title }}">
                    </div>
                    <div class="hero-content">
                        <div class="container">
                            <div class="row">
                                <div class="col-10 col-md-8 col-xl-6">
                                    <h5>{{ $slider->sub_title }}</h5>
                                    <h2 class="text-white">{{ $slider->title }}</h2>
                                    <p>{{ $slider->description }}</p>
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}" class="hero-button">Shopping Now</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- Intro Slider End -->

    <!-- Categories slider Start -->
    @if($section_status->categories == 1 && count($categories) > 0)
    <div class="product-catagory-section mt-5">
        <div class="section-content-gap">
            <div class="container">
                <div class="row">
                    <div class="section-content">
                        <h3 class="section-title" data-aos="fade-up" data-aos-delay="50">Popular Categories</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-catagory-wrapper">
            <div class="container">
                <div class="row">
                    @foreach($categories as $index => $category)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <a href="{{ route('category.show', $category->slug) }}" class="product-catagory-single" data-aos="fade-up" data-aos-delay="{{ $index * 200 }}">
                                <div class="product-catagory-img">
                                    @if ($category->image == null)
                                        <img src="{{ asset('images/company/' . $company->company_logo) }}" alt="{{ $category->name }}" class="img-fluid" style="height: 100px; object-fit: cover;">
                                        
                                    @else

                                        <img src="{{ asset('images/category/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid" style="height: 100px; object-fit: cover;">

                                    @endif
                                    
                                </div>
                                <div class="product-catagory-content">
                                    <h5 class="product-catagory-title">{{ $category->name }}</h5>
                                    <span class="product-catagory-items">({{ $category->products->count() }} Items)</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Categories slider End -->


    <!-- Category products slider Start-->
    @if ($section_status->category_products == 1 && count($categories) > 0)
    <div class="product-tab-section section-top-gap-100">
        <div class="section-content-gap ">
            <div class="container">
                <div class="row">
                    <div class="section-content d-flex justify-content-between align-items-md-center align-items-start flex-md-row flex-column">
                        <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">Products</h3>
                        <ul class="tablist nav product-tab-btn d-none d-md-inline-block" data-aos="fade-up" data-aos-delay="400">
                            @foreach($categories->take(6) as $index => $category)
                                <li>
                                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $category->slug }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-tab-wrapper" data-aos="fade-up" data-aos-delay="50">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="tab-content tab-animate-zoom">
                            @foreach($categories as $index => $category)
                                <div class="tab-pane {{ $index === 0 ? 'show active' : '' }}" id="{{ $category->slug }}">
                                    <div class="product-default-slider product-default-slider-4grids-1row">
                                        @foreach($category->products as $product)
                                        <div class="product-default-single border-around">
                                            <div class="product-img-warp">
                                                <a href="{{ route('product.show', $product->slug) }}" class="product-default-img-link">
                                                    <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-default-img img-fluid" style="height: 200px; object-fit: cover;">
                                                </a>
                                                <div class="product-action-icon-link">
                                                    <ul>
                                                        @if ($product->stock && $product->stock->quantity > 0)
                                                        
                                                        @php
                                                            $colors = $product->stock()
                                                                ->where('quantity', '>', 0)
                                                                ->distinct('color')
                                                                ->whereNotNull('color')
                                                                ->pluck('color');
                                                            $sizes = $product->stock()
                                                                ->where('quantity', '>', 0)
                                                                ->distinct('size')
                                                                ->whereNotNull('size')
                                                                ->pluck('size');
                                                            $images = $product->images()->select('image')->get();
                                                        @endphp
    
                                                        <li class="">
                                                            <a href="#" class="add-to-wishlist" data-product-id="{{ $product->id }}"
                                                            data-offer-id="0"
                                                            data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                            data-price="{{ $product->price }}">
                                                            <i class="icon-heart"></i>
                                                            </a>
                                                        </li>
    
                                                        <li>
                                                            <a href="#"
                                                            class="quick-view" title="Quick View" 
                                                            data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                            data-product-id="{{ $product->id }}"
                                                            data-offer-id="0" 
                                                            data-price="{{ $product->price }}"
                                                            data-product-name="{{ $product->name }}"
                                                            data-product-description="{{ htmlspecialchars($product->short_description) }}"
                                                            data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                            data-stock="{{ $product->stock->quantity }}"
                                                            data-colors="{{ $colors->toJson() }}"
                                                            data-sizes="{{ $sizes->toJson() }}" 
                                                            ><i class="icon-eye"></i>
                                                            </a>
                                                        </li>
    
                                                        <li>
                                                            <a href="#"
                                                                class="quick-view" title="Quick View" 
                                                                data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                                data-product-id="{{ $product->id }}"
                                                                data-offer-id="0" 
                                                                data-price="{{ $product->price }}"
                                                                data-product-name="{{ $product->name }}"
                                                                data-product-description="{{ htmlspecialchars($product->short_description) }}"
                                                                data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                                data-stock="{{ $product->stock->quantity }}"
                                                                data-colors="{{ $colors->toJson() }}"
                                                                data-sizes="{{ $sizes->toJson() }}" 
                                                                >
                                                                <i class="icon-shopping-cart"></i>
                                                            </a>
                                                        </li>
    
                                                        @else
    
                                                        <li>
                                                            <span class="text-muted">Out of Stock</span>
                                                        </li>
    
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-default-content">
                                                <h6 class="product-default-link"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h6>
                                                @php
                                                    $delprice = $product->price * .10 + $product->price;
    
                                                @endphp
                                                <span class="product-default-price"><del class="product-default-price-off">${{ number_format($delprice, 2) }}</del> ${{ number_format($product->price, 2) }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Category products slider End-->

    <!-- Recent Products Start -->
    @if($section_status->recent_products == 1 && $recentProducts->count() > 0)

        <!-- ...:::: Start Shop Section:::... -->
        <div class="shop-section mt-5">
            <div class="container">
                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-lg-12">
                        <!-- Start Shop Product Sorting Section -->
                        <div class="shop-sort-section" data-aos="fade-up"  data-aos-delay="0">
                            <div class="container">
                                <div class="row">
                                    <!-- Start Sort Wrapper Box -->
                                    <div class="sort-box d-flex justify-content-between align-items-center flex-wrap">
                                        <!-- Start Sort tab Button -->
                                        <div class="sort-tablist">
                                            <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">Recent Products</h3>
                                        </div> <!-- End Sort tab Button -->
    
                                        <!-- Start Sort Select Option -->
                                        <div class="sort-select-list">
                                        </div> <!-- End Sort Select Option -->
    
                                        <!-- Start Page Amount -->
                                        <div class="page-amount">
                                            <span><a href="{{route('getDiffTypeProducts', ['ptype' => "recent-products"])}}" class="show">View All</a></span>
                                        </div> <!-- End Page Amount -->
    
                                    </div> <!-- Start Sort Wrapper Box -->
                                </div>
                            </div>
                        </div> <!-- End Section Content -->
    
                        <!-- Start Tab Wrapper -->
                        <div class="sort-product-tab-wrapper">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="tab-content tab-animate-zoom">
                                            <!-- Start Grid View Product -->
                                            <div class="tab-pane active show sort-layout-single" id="layout-4-grid">
                                                <div class="row">

                                                    
                                                    @if ($recentProducts->count() > 0)
                                                        @foreach($recentProducts as $product)
                                                        <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                                            <!-- Start Product Defautlt Single -->
                                                            <div class="product-default-single border-around" data-aos="fade-up"  data-aos-delay="0">
                                                                <div class="product-img-warp">
                                                                    <a href="{{ route('product.show', $product->slug) }}" class="product-default-img-link">
                                                                        <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-default-img img-fluid" style="height: 200px; object-fit: cover;">
                                                                    </a>
                                                                    <div class="product-action-icon-link">
                                                                        <ul>
                                                                            @if ($product->stock && $product->stock->quantity > 0)
                                                                            
                                                                            @php
                                                                                $colors = $product->stock()
                                                                                    ->where('quantity', '>', 0)
                                                                                    ->distinct('color')
                                                                                    ->whereNotNull('color')
                                                                                    ->pluck('color');
                                                                                $sizes = $product->stock()
                                                                                    ->where('quantity', '>', 0)
                                                                                    ->distinct('size')
                                                                                    ->whereNotNull('size')
                                                                                    ->pluck('size');
                                                                                $images = $product->images()->select('image')->get();
                                                                            @endphp
                        
                                                                            <li class="">
                                                                                <a href="#" class="add-to-wishlist" data-product-id="{{ $product->id }}"
                                                                                data-offer-id="0"
                                                                                data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                                                data-price="{{ $product->price }}">
                                                                                <i class="icon-heart"></i>
                                                                                </a>
                                                                            </li>
                        
                                                                            <li>
                                                                                <a href="#"
                                                                                class="quick-view" title="Quick View" 
                                                                                data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                                                data-product-id="{{ $product->id }}"
                                                                                data-offer-id="0" 
                                                                                data-price="{{ $product->price }}"
                                                                                data-product-name="{{ $product->name }}"
                                                                                data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                                                data-stock="{{ $product->stock->quantity }}"
                                                                                data-colors="{{ $colors->toJson() }}"
                                                                                data-sizes="{{ $sizes->toJson() }}" 
                                                                                ><i class="icon-eye"></i>
                                                                                </a>
                                                                            </li>
                        
                                                                            <li>
                                                                                <a href="#"
                                                                                    class="quick-view" title="Quick View" 
                                                                                    data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                                                    data-product-id="{{ $product->id }}"
                                                                                    data-offer-id="0" 
                                                                                    data-price="{{ $product->price }}"
                                                                                    data-product-name="{{ $product->name }}"
                                                                                    data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                                                    data-stock="{{ $product->stock->quantity }}"
                                                                                    data-colors="{{ $colors->toJson() }}"
                                                                                    data-sizes="{{ $sizes->toJson() }}" 
                                                                                    >
                                                                                    <i class="icon-shopping-cart"></i>
                                                                                </a>
                                                                            </li>
                        
                                                                            @else
                        
                                                                            <li>
                                                                                <span class="text-muted">Out of Stock</span>
                                                                            </li>
                        
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="product-default-content">
                                                                    <h6 class="product-default-link"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h6>
                                                                    @php
                                                                        $delprice = $product->price * .10 + $product->price;
                        
                                                                    @endphp
                                                                    <span class="product-default-price"><del class="product-default-price-off">${{ number_format($delprice, 2) }}</del> ${{ number_format($product->price, 2) }}</span>
                                                                </div>
                                                            </div> <!-- End Product Defautlt Single -->
                                                        </div>
                                                        
                                                        @endforeach
                                                    @endif

                                                </div>
                                            </div> <!-- End Grid View Product -->
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Tab Wrapper -->
    
                    </div> <!-- End Shop Product Sorting Section  -->
                </div>
            </div>
        </div> <!-- ...:::: End Shop Section:::... -->

    @endif
    <!-- Recent Products End -->
    
    <!-- Trending Products Start -->
    @if($section_status->trending_products == 1 && $trendingProducts->count() > 0)

    <!-- ...:::: Start Shop Section:::... -->
    <div class="shop-section mt-5">
        <div class="container">
            <div class="row flex-column-reverse flex-lg-row">
                <div class="col-lg-12">
                    <!-- Start Shop Product Sorting Section -->
                    <div class="shop-sort-section" data-aos="fade-up"  data-aos-delay="0">
                        <div class="container">
                            <div class="row">
                                <!-- Start Sort Wrapper Box -->
                                <div class="sort-box d-flex justify-content-between align-items-center flex-wrap">
                                    <!-- Start Sort tab Button -->
                                    <div class="sort-tablist">
                                        <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">Trending Products</h3>
                                    </div> <!-- End Sort tab Button -->

                                    <!-- Start Sort Select Option -->
                                    <div class="sort-select-list">
                                    </div> <!-- End Sort Select Option -->

                                    <!-- Start Page Amount -->
                                    <div class="page-amount">
                                        <span><a href="{{route('getDiffTypeProducts', ['ptype' => "trending-products"])}}" class="show">View All</a></span>
                                    </div> <!-- End Page Amount -->

                                </div> <!-- Start Sort Wrapper Box -->
                            </div>
                        </div>
                    </div> <!-- End Section Content -->

                    <!-- Start Tab Wrapper -->
                    <div class="sort-product-tab-wrapper">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="tab-content tab-animate-zoom">
                                        <!-- Start Grid View Product -->
                                        <div class="tab-pane active show sort-layout-single" id="layout-4-grid">
                                            <div class="row">

                                                
                                                @if ($trendingProducts->count() > 0)
                                                    @foreach($trendingProducts as $product)
                                                    <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                                        <!-- Start Product Defautlt Single -->
                                                        <div class="product-default-single border-around" data-aos="fade-up"  data-aos-delay="0">
                                                            <div class="product-img-warp">
                                                                <a href="{{ route('product.show', $product->slug) }}" class="product-default-img-link">
                                                                    <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-default-img img-fluid" style="height: 200px; object-fit: cover;">
                                                                </a>
                                                                <div class="product-action-icon-link">
                                                                    <ul>
                                                                        @if ($product->stock && $product->stock->quantity > 0)
                                                                        
                                                                        @php
                                                                            $colors = $product->stock()
                                                                                ->where('quantity', '>', 0)
                                                                                ->distinct('color')
                                                                                ->whereNotNull('color')
                                                                                ->pluck('color');
                                                                            $sizes = $product->stock()
                                                                                ->where('quantity', '>', 0)
                                                                                ->distinct('size')
                                                                                ->whereNotNull('size')
                                                                                ->pluck('size');
                                                                            $images = $product->images()->select('image')->get();
                                                                        @endphp
                    
                                                                        <li class="">
                                                                            <a href="#" class="add-to-wishlist" data-product-id="{{ $product->id }}"
                                                                            data-offer-id="0"
                                                                            data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                                            data-price="{{ $product->price }}">
                                                                            <i class="icon-heart"></i>
                                                                            </a>
                                                                        </li>
                    
                                                                        <li>
                                                                            <a href="#"
                                                                            class="quick-view" title="Quick View" 
                                                                            data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                                            data-product-id="{{ $product->id }}"
                                                                            data-offer-id="0" 
                                                                            data-price="{{ $product->price }}"
                                                                            data-product-name="{{ $product->name }}"
                                                                            data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                                            data-stock="{{ $product->stock->quantity }}"
                                                                            data-colors="{{ $colors->toJson() }}"
                                                                            data-sizes="{{ $sizes->toJson() }}" 
                                                                            ><i class="icon-eye"></i>
                                                                            </a>
                                                                        </li>
                    
                                                                        <li>
                                                                            <a href="#"
                                                                                class="quick-view" title="Quick View" 
                                                                                data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                                                data-product-id="{{ $product->id }}"
                                                                                data-offer-id="0" 
                                                                                data-price="{{ $product->price }}"
                                                                                data-product-name="{{ $product->name }}"
                                                                                data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                                                data-stock="{{ $product->stock->quantity }}"
                                                                                data-colors="{{ $colors->toJson() }}"
                                                                                data-sizes="{{ $sizes->toJson() }}" 
                                                                                >
                                                                                <i class="icon-shopping-cart"></i>
                                                                            </a>
                                                                        </li>
                    
                                                                        @else
                    
                                                                        <li>
                                                                            <span class="text-muted">Out of Stock</span>
                                                                        </li>
                    
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="product-default-content">
                                                                <h6 class="product-default-link"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h6>
                                                                @php
                                                                    $delprice = $product->price * .10 + $product->price;
                    
                                                                @endphp
                                                                <span class="product-default-price"><del class="product-default-price-off">${{ number_format($delprice, 2) }}</del> ${{ number_format($product->price, 2) }}</span>
                                                            </div>
                                                        </div> <!-- End Product Defautlt Single -->
                                                    </div>
                                                    
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div> <!-- End Grid View Product -->
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Tab Wrapper -->

                </div> <!-- End Shop Product Sorting Section  -->
            </div>
        </div>
    </div> <!-- ...:::: End Shop Section:::... -->

    @endif
    <!-- Trending Products End -->

    <!-- Most Viewed Products Start -->
    @if($section_status->most_viewed_products == 1 && $mostViewedProducts->count() > 0)
    <div class="product-tab-section section-top-gap-100 mt-5">
        <div class="section-content-gap">
            <div class="container">
                <div class="row">
                    <div class="section-content d-flex justify-content-between align-items-md-center align-items-start flex-md-row flex-column">
                        <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">Most Viewed Products</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-tab-wrapper" data-aos="fade-up" data-aos-delay="50">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="product-default-slider product-default-slider-4grids-1row">
                            @if ($mostViewedProducts->count() > 0)
                                @foreach($mostViewedProducts as $product)
                                <div class="product-default-single border-around">
                                    <div class="product-img-warp">
                                        <a href="{{ route('product.show', $product->slug) }}" class="product-default-img-link">
                                            <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-default-img img-fluid" style="height: 200px; object-fit: cover;">
                                        </a>
                                        <div class="product-action-icon-link">
                                            <ul>
                                                @if ($product->stock && $product->stock->quantity > 0)
                                                
                                                @php
                                                    $colors = $product->stock()
                                                        ->where('quantity', '>', 0)
                                                        ->distinct('color')
                                                        ->whereNotNull('color')
                                                        ->pluck('color');
                                                    $sizes = $product->stock()
                                                        ->where('quantity', '>', 0)
                                                        ->distinct('size')
                                                        ->whereNotNull('size')
                                                        ->pluck('size');
                                                    $images = $product->images()->select('image')->get();
                                                @endphp

                                                <li class="">
                                                    <a href="#" class="add-to-wishlist" data-product-id="{{ $product->id }}"
                                                    data-offer-id="0"
                                                    data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                    data-price="{{ $product->price }}">
                                                    <i class="icon-heart"></i>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#"
                                                    class="quick-view" title="Quick View" 
                                                    data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                    data-product-id="{{ $product->id }}"
                                                    data-offer-id="0" 
                                                    data-price="{{ $product->price }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-description="{{ htmlspecialchars($product->short_description) }}"
                                                    data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                    data-stock="{{ $product->stock->quantity }}"
                                                    data-colors="{{ $colors->toJson() }}"
                                                    data-sizes="{{ $sizes->toJson() }}" 
                                                    ><i class="icon-eye"></i>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#"
                                                        class="quick-view" title="Quick View" 
                                                        data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                        data-product-id="{{ $product->id }}"
                                                        data-offer-id="0" 
                                                        data-price="{{ $product->price }}"
                                                        data-product-name="{{ $product->name }}"
                                                        data-product-description="{{ htmlspecialchars($product->short_description) }}"
                                                        data-image ="{{ asset('images/products/' . $product->feature_image) }}"
                                                        data-stock="{{ $product->stock->quantity }}"
                                                        data-colors="{{ $colors->toJson() }}"
                                                        data-sizes="{{ $sizes->toJson() }}" 
                                                        >
                                                        <i class="icon-shopping-cart"></i>
                                                    </a>
                                                </li>

                                                @else

                                                <li>
                                                    <span class="text-muted">Out of Stock</span>
                                                </li>

                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-default-content">
                                        <h6 class="product-default-link"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h6>
                                        @php
                                            $delprice = $product->price * .10 + $product->price;

                                        @endphp
                                        <span class="product-default-price"><del class="product-default-price-off">${{ number_format($delprice, 2) }}</del> ${{ number_format($product->price, 2) }}</span>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Most Viewed Products End -->

    {{--
    <!-- Flash Sell Start -->
    @if($section_status->flash_sell == 1 && count($flashSells) > 0)
    <div class="banner-section section-top-gap-100">
        <div class="banner-wrapper">
            <div class="container">
                <div class="row">
                    @foreach($flashSells as $index => $flashSell)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="banner-single" data-aos="fade-up" data-aos-delay="{{ $index * 200 }}">
                                <a href="{{ route('flash-sells.show', $flashSell->slug) }}" class="banner-img-link">
                                    <img class="banner-img img-fluid" src="{{ asset('images/flash_sell/' . $flashSell->flash_sell_image) }}" alt="{{ $flashSell->flash_sell_name }}" style="height: 200px; object-fit: cover;">
                                </a>
                                <div class="banner-content">
                                    <span class="banner-text-tiny">{{ $flashSell->flash_sell_name }}</span>
                                    <h3 class="banner-text-large">{{ $flashSell->flash_sell_title }}</h3>
                                    <a href="{{ route('flash-sells.show', $flashSell->slug) }}" class="banner-link">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Flash Sell End -->
    --}}



    <div class="map-section mt-5" data-aos="fade-up"  data-aos-delay="0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="mapouter">
                        <div class="gmap_canvas">
                            @php
                                $gmap = \App\Models\CompanyDetails::select('google_map','home_footer')->first();
                            @endphp
                            
                            @if ($gmap->google_map)
                                
                            <iframe id="gmap_canvas" src="{{ $gmap->google_map }}"></iframe>
                            
                            @else
                                
                            <iframe id="gmap_canvas" src="https://maps.google.com/maps?q=121%20King%20St%2C%20Melbourne%20VIC%203000%2C%20Australia&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $brands = \App\Models\Brand::whereNotNull('image')->where('status', 1)->get();
    @endphp
    @if ($brands->count() > 0)
    <div class="company-logo-section mt-3">
        <!-- Start Company Logo Wrapper -->
        <div class="company-logo-wrapper">
            <div class="container">
                <div class="row">

                    @foreach ($brands as $brand)
                    <div class="col-3">
                        <img src="{{ asset('images/brand/'.$brand->image) }}" alt="" class="img-fluid company-logo-image">
                    </div>
                    @endforeach

                    
                    


                </div>
            </div>
        </div> <!-- End Company Logo Wrapper -->
    </div>
    @endif
    


    <div class="about-us-top-area section-top-gap-100">
        <div class="container">
            <div class="row">
                <div class="col-12" data-aos="fade-up"  data-aos-delay="0">
                    {!! $gmap->home_footer !!}
                </div>
            </div>
        </div>
    </div>



@endsection

@section('script')

@endsection