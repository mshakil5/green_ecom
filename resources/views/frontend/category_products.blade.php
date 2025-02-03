@extends('frontend.layouts.app')
@section('title', $title)
@section('content')


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
                                    <h3 class="section-title" data-aos="fade-up" data-aos-delay="0">{{ $category->name }}</h3>
                                </div> <!-- End Sort tab Button -->

                                <!-- Start Sort Select Option -->
                                <div class="sort-select-list">
                                </div> <!-- End Sort Select Option -->

                                <!-- Start Page Amount -->
                                <div class="page-amount">
                                    
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

                                            
                                            @if ($products->count() > 0)
                                                @foreach($products as $product)
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
                                                                        <a href="#offcanvas"
                                                                            class="add-to-cart offcanvas-toggle" title="Add to cart"
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
                                                                $discountPercent = $product->discount_percent ?? 10;
                                                                $delprice = $product->price * ($discountPercent / 100) + $product->price;
                
                                                            @endphp
                                                            <span class="product-default-price"><del class="product-default-price-off">{{$currency}}{{ number_format($delprice, 2) }}</del> {{$currency}}{{ number_format($product->price, 2) }}</span>
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


@endsection