@extends('frontend.layouts.app')
@section('title', $title)
@section('content')

<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">{{ $sub_category->name }}</h3>
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="{{ route('frontend.homepage') }}">Home</a></li>
                                <li class="active" aria-current="page">{{ $sub_category->name }}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-tab-section">
    <div class="product-tab-wrapper" data-aos="fade-up" data-aos-delay="50">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="product-default-slider product-default-slider-4grids-1row">
                        @if ($products->count() > 0)
                            @foreach($products as $product)
                                <div class="product-default-single border-around">
                                    <div class="product-img-warp">
                                        <a href="{{ route('product.show', $product->slug) }}" class="product-default-img-link">
                                            <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-default-img img-fluid d-block mx-auto" style="height: 200px; object-fit: cover;">
                                        </a>
                                        <div class="product-action-icon-link">
                                            <ul>
                                                @if ($product->stock && $product->stock->quantity > 0)
                                                @php
                                                    $colors = $product->stock()
                                                        ->where('quantity', '>', 0)
                                                        ->distinct('color')
                                                        ->pluck('color');

                                                    $sizes = $product->stock()
                                                        ->where('quantity', '>', 0)
                                                        ->distinct('size')
                                                        ->pluck('size');
                                                        
                                                        $images = $product->images()->select('image')->get();
                                                        
                                                @endphp
                                                <li>
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
                                                    data-product-description="{!! $product->short_description !!}"
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
                                                        data-product-description="{!! $product->short_description !!}"
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
                                        <span class="product-default-price">
                                            <del class="product-default-price-off">{{ $currency }}{{ number_format($product->price + 5, 2) }}</del> 
                                            {{ $currency }}{{ number_format($product->price, 2) }}
                                        </span>
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

@endsection