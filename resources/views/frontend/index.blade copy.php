@extends('frontend.layouts.app')

@section('content')

    <!-- Intro Slider Start-->
    @if($section_status->slider == 1)
    <div class="intro-section pb-3 mb-2">
        <div class="container mobile-margin">
            <div class="row">
                <!-- First Column (Slider) -->
                <div class="col-12 col-lg-8 mb-3 mb-lg-0">
                    <div class="intro-slider-container slider-container-ratio mb-2 mb-lg-0">
                        <div class="intro-slider owl-carousel owl-simple owl-dark owl-nav-inside" data-toggle="owl"
                            data-owl-options='{
                                "dots": true,
                                "nav": false,
                                "responsive": {
                                    "1200": {
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>
                            @foreach($sliders as $slider)
                                <div class="intro-slide" style="background-image: url('{{ asset('images/slider/' . $slider->image) }}'); background-size: cover; background-position: center; height: 500px;">
                                    <div class="intro-content" style="padding: 20px; display: flex; align-items: center; justify-content: center; height: 100%;">
                                        <div class="row justify-content-left">
                                            <div class="col-auto col-sm-7 col-md-6 col-lg-5" style="background: rgba(0, 0, 0, 0.5); padding: 20px; border-radius: 10px;">
                                                <h3 class="intro-subtitle text-primary" style="color: #fff;">{{ $slider->sub_title }}</h3>
                                                <h1 class="intro-title" style="color: #fff;">{{ $slider->title }}</h1>
                                                @if($slider->link)
                                                <a href="{{ $slider->link }}" class="btn btn-primary btn-round">
                                                    <span>Shop More</span>
                                                    <i class="icon-long-arrow-right"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <span class="slider-loader"></span>
                    </div>
                </div>
                <!-- End of First Column -->

                <!-- Second Column (Categories) -->
                <div class="col-12 col-lg-4 d-none d-lg-block">
                    <div class="intro-banners">
                        @foreach($categories->take(3) as $category)
                        <div class="banner mb-3">
                            <a href="{{ route('category.show', $category->slug) }}">
                                <img src="{{ asset('images/category/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid" style="object-fit: cover; height: 145px; width: 100%;">
                            </a>

                            <div class="banner-content" style="background: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 10px;">
                                <h4 class="banner-title text-center">
                                    <a href="{{ route('category.show', $category->slug) }}" style="color: #fff;">{{ $category->name }}</a>
                                </h4>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- End of Second Column -->
            </div>
        </div>
    </div>
    @endif
    <!-- Intro Slider End -->

    <!-- Special Offer Start -->
    @if($section_status->special_offer == 1)
    <div class="container">
        <div class="row justify-content-center pt-2">
            @foreach($specialOffers->take(3) as $specialOffer)
                <div class="col-md-6 col-lg-4">
                    <div class="banner banner-overlay banner-overlay-light">
                        <a href="{{ route('special-offers.show', $specialOffer->slug) }}">
                            <img src="{{ asset('images/special_offer/' . $specialOffer->offer_image) }}" alt="Banner" style="height: 300px; object-fit: cover;">
                        </a>
                        <div class="banner-content" style="background: rgba(0, 0, 0, 0.5); padding: 20px; border-radius: 10px;">
                            <h4 class="banner-subtitle">
                                <a href="{{ route('special-offers.show', $specialOffer->slug) }}" style="color: #fff;">
                                    {{ $specialOffer->offer_name }}
                                </a>
                            </h4>
                            <h3 class="banner-title">
                                <a href="{{ route('special-offers.show', $specialOffer->slug) }}">
                                    <strong style="color: #fff;">{{ $specialOffer->offer_title }}</strong>
                                </a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- Special Offer End -->

    <!-- Category products slider Start-->
    @if ($section_status->category_products == 1 && count($categories) > 0)
    <div class="container new-arrivals">   
        <div class="heading heading-flex">
            <div class="heading-right" style="width: 100%; text-align: center;">
                <ul class="nav nav-pills nav-border-anim nav-big justify-content-center" role="tablist">
                     @foreach($categories->take(3) as $index => $category)
                        <li class="nav-item">
                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}" 
                            id="category-{{ $category->id }}-link" 
                            data-toggle="tab" 
                            href="#category-{{ $category->id }}-tab" 
                            role="tab" 
                            aria-controls="category-{{ $category->id }}-tab" 
                            aria-selected="{{ $index == 0 ? 'true' : 'false' }}"
                            index="{{ $index }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="tab-content">
            @foreach($categories as $index => $category)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="category-{{ $category->id }}-tab" role="tabpanel" aria-labelledby="category-{{ $category->id }}-link">
                    <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                        data-owl-options='{
                            "nav": true, 
                            "dots": true,
                            "margin": 20,
                            "loop": true,
                            "responsive": {
                                "0": {
                                    "items":2
                                },
                                "480": {
                                    "items":2
                                },
                                "768": {
                                    "items":3
                                },
                                "992": {
                                    "items":4
                                }
                            }
                        }'>
                        @foreach($category->products as $product)
                        <div class="product product-2">
                            <figure class="product-media">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-image">
                                </a>
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
                                @endphp
                                    <div class="product-action-vertical">
                                        <a href="#" class="btn-product-icon btn-wishlist add-to-wishlist btn-expandable" 
                                        title="Add to wishlist" 
                                        data-product-id="{{ $product->id }}" 
                                        data-offer-id="0" 
                                        data-price="{{ $product->price }}"><span>Add to wishlist</span>      
                                        </a>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="btn-product btn-cart" title="Add to cart"
                                        data-product-id="{{ $product->id }}" 
                                        data-offer-id="0" 
                                        data-price="{{ $product->price }}" 
                                        data-toggle="modal" data-target="#quickAddToCartModal" 
                                        data-image ="{{ asset('images/products/' . $product->feature_image) }}" data-stock="{{ $product->stock->quantity }}"
                                        data-colors="{{ $colors->toJson() }}"
                                        data-sizes="{{ $sizes->toJson() }}">
                                            <span>add to cart</span>
                                        </a>
                                    </div>
                                @else
                                    <span class="product-label label-out-stock">Out of stock</span>
                                @endif
                            </figure>
                            <div class="product-body">
                                <div class="product-cat">
                                    <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                                </div>
                                <h3 class="product-title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                                <div class="product-price">
                                    {{ $currency }}{{ number_format($product->price, 2) }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- Category products slider End-->

    <!-- Recent advertisements start-->
    @if($advertisements->contains('type', 'recent'))
    <div class="container mt-5">
        @foreach($advertisements as $advertisement)
            @if($advertisement->type == 'recent')
                <div class="cta cta-border" style="background-image: url('{{ asset('images/ads/' . $advertisement->image) }}');">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="cta-content">
                                <div class="cta-text text-right text-white">
                                </div>
                                <a href="{{ $advertisement->link }}" class="btn btn-primary btn-round" target="_blank">
                                    <span>Shop Now</span><i class="icon-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif
    <!-- Recent advertisements end-->

    <!-- Recent Products Start -->
    @if($section_status->recent_products == 1)
    <div class="pt-5">
        <div class="container trending-products">
            <div class="heading heading-flex mb-3">
                <div class="heading-left">
                    <h2 class="title">Recent Products</h2>
                </div>
            </div>

            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                data-owl-options='{
                    "nav": true, 
                    "dots": false,
                    "margin": 20,
                    "loop": true,
                    "responsive": {
                        "0": {
                            "items":2
                        },
                        "480": {
                            "items":2
                        },
                        "768": {
                            "items":3
                        },
                        "992": {
                            "items":4
                        }
                    }
                }'>
                @if ($recentProducts->count() > 0)
                    @foreach($recentProducts as $product)
                    <div class="product product-2">
                        <figure class="product-media">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-image">
                            </a>
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
                                @endphp

                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-wishlist add-to-wishlist btn-expandable" title="Add to wishlist" data-product-id="{{ $product->id }}" data-offer-id="0" data-price="{{ $product->price }}">
                                        <span>Add to wishlist</span>
                                    </a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-cart" title="Add to cart"
                                    data-product-id="{{ $product->id }}" 
                                    data-offer-id="0" 
                                    data-price="{{ $product->price }}" 
                                    data-toggle="modal" data-target="#quickAddToCartModal" 
                                    data-image ="{{ asset('images/products/' . $product->feature_image) }}" data-stock="{{ $product->stock->quantity }}"
                                    data-colors="{{ $colors->toJson() }}"
                                     data-sizes="{{ $sizes->toJson() }}">
                                        <span>add to cart</span>
                                    </a>
                                </div>
                            @else
                                <span class="product-label label-out-stock">Out of stock</span>
                            @endif
                        </figure>

                        <div class="product-body">
                            <div class="product-cat">
                                <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                            </div>
                            <h3 class="product-title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                            <div class="product-price">
                            {{ $currency }}{{ number_format($product->price, 2) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif
    <!-- Recent Products End -->

    <!-- Supplier advertisements start-->
    <div class="container">
        @foreach($advertisements as $advertisement)
            @if($advertisement->type == 'vendor')
                <div class="cta cta-border" style="background-image: url('{{ asset('images/ads/' . $advertisement->image) }}');">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="cta-content">
                                <div class="cta-text text-right text-white">
                                </div>
                                <a href="{{ $advertisement->link }}" class="btn btn-primary btn-round" target="_blank">
                                    <span>Shop Now</span><i class="icon-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <!-- Supplier advertisements end-->

    <!-- Trending Products Start -->
    @if($section_status->trending_products == 1)
    <div class="pt-5">
        <div class="container trending-products">
            <div class="heading heading-flex mb-3">
                <div class="heading-left">
                    <h2 class="title">Trending Products</h2>
                </div>
            </div>

            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                data-owl-options='{
                    "nav": true, 
                    "dots": false,
                    "margin": 20,
                    "loop": true,
                    "responsive": {
                        "0": {
                            "items":2
                        },
                        "480": {
                            "items":2
                        },
                        "768": {
                            "items":3
                        },
                        "992": {
                            "items":4
                        }
                    }
                }'>
                @if ($trendingProducts->count() > 0)
                    @foreach($trendingProducts as $product)
                    <div class="product product-2">
                        <figure class="product-media">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-image">
                            </a>
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
                                @endphp
                                
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-wishlist add-to-wishlist btn-expandable" title="Add to wishlist" data-product-id="{{ $product->id }}" data-offer-id="0" data-price="{{ $product->price }}">
                                        <span>Add to wishlist</span>
                                    </a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-cart" title="Add to cart"
                                    data-product-id="{{ $product->id }}" 
                                    data-offer-id="0" 
                                    data-price="{{ $product->price }}" 
                                    data-toggle="modal" data-target="#quickAddToCartModal" 
                                    data-image ="{{ asset('images/products/' . $product->feature_image) }}" data-stock="{{ $product->stock->quantity }}"
                                    data-colors="{{ $colors->toJson() }}" data-sizes="{{ $sizes->toJson() }}">
                                        <span>add to cart</span>
                                    </a>
                                </div>
                            @else
                                <span class="product-label label-out-stock">Out of stock</span>
                            @endif
                        </figure>

                        <div class="product-body">
                            <div class="product-cat">
                                <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                            </div>
                            <h3 class="product-title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                            <div class="product-price">
                            {{ $currency }}{{ number_format( $product->price, 2) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif
    <!-- Trending Products End -->

    <!-- Most Viewed Products Start -->
    @if($section_status->most_viewed_products == 1 && $mostViewedProducts->count() > 0)
    <div class="pt-5">
        <div class="container trending-products">
            <div class="heading heading-flex mb-3">
                <div class="heading-left">
                    <h2 class="title">Most Viewed Products</h2>
                </div>
            </div>

            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                data-owl-options='{
                    "nav": true, 
                    "dots": false,
                    "margin": 20,
                    "loop": true,
                    "responsive": {
                        "0": {
                            "items":2
                        },
                        "480": {
                            "items":2
                        },
                        "768": {
                            "items":3
                        },
                        "992": {
                            "items":4
                        }
                    }
                }'>
                @if ($mostViewedProducts->count() > 0)
                    @foreach($mostViewedProducts as $product)
                    <div class="product product-2">
                        <figure class="product-media">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-image">
                            </a>
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
                                @endphp
                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-wishlist add-to-wishlist btn-expandable" title="Add to wishlist" data-product-id="{{ $product->id }}" data-offer-id="0" data-price="{{ $product->price }}">
                                        <span>Add to wishlist</span>
                                    </a>
                                </div>
                                <div class="product-action">
                                    <a href="#" class="btn-product btn-cart" title="Add to cart"
                                    data-product-id="{{ $product->id }}" 
                                    data-offer-id="0" 
                                    data-price="{{ $product->price }}" 
                                    data-toggle="modal" data-target="#quickAddToCartModal" 
                                    data-image ="{{ asset('images/products/' . $product->feature_image) }}" data-stock="{{ $product->stock->quantity }}"
                                    data-colors="{{ $colors->toJson() }}" data-sizes="{{ $sizes->toJson() }}">
                                        <span>add to cart</span>
                                    </a>
                                </div>
                            @else
                                <span class="product-label label-out-stock">Out of stock</span>
                            @endif
                        </figure>

                        <div class="product-body">
                            <div class="product-cat">
                                <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                            </div>
                            <h3 class="product-title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
                            <div class="product-price">
                            {{ $currency }}{{ number_format( $product->price, 2) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif
    <!-- Most Viewed Products End -->

    <!-- Flash Sell Start -->
    @if($section_status->flash_sell == 1)
    <div class="container mt-2 mb-2">
        <div class="row justify-content-center">
            @foreach($flashSells as $flashSell)
                <div class="col-md-6 col-lg-4">
                    <div class="banner banner-overlay banner-overlay-light">
                        <a href="{{ route('flash-sells.show', $flashSell->slug) }}">
                            <img src="{{ asset('images/flash_sell/' . $flashSell->flash_sell_image) }}" alt="Banner" style="height: 300px; object-fit: cover;">
                        </a>
                        <div class="banner-content" style="background: rgba(0, 0, 0, 0.5); padding: 20px; border-radius: 10px;">
                            <h4 class="banner-subtitle">
                                <a href="{{ route('flash-sells.show', $flashSell->slug) }}" style="color: #fff;">
                                    {{ $flashSell->flash_sell_name }}
                                </a>
                            </h4>
                            <h3 class="banner-title">
                                <a href="{{ route('flash-sells.show', $flashSell->slug) }}">
                                    <strong style="color: #fff;">{{ $flashSell->flash_sell_title }}</strong>
                                </a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- Flash Sell End -->

    <!-- Features Start -->
    @if($section_status->features == 1)
    <div class="icon-boxes-container bg-transparent">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="icon-box icon-box-side">
                        <span class="icon-box-icon text-dark">
                            <i class="icon-rocket"></i>
                        </span>
                        <div class="icon-box-content">
                            <h3 class="icon-box-title">Free Shipping</h3>
                            <p>Orders $50 or more</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="icon-box icon-box-side">
                        <span class="icon-box-icon text-dark">
                            <i class="icon-rotate-left"></i>
                        </span>

                        <div class="icon-box-content">
                            <h3 class="icon-box-title">Free Returns</h3>
                            <p>Within 30 days</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="icon-box icon-box-side">
                        <span class="icon-box-icon text-dark">
                            <i class="icon-info-circle"></i>
                        </span>

                        <div class="icon-box-content">
                            <h3 class="icon-box-title">Get 20% Off 1 Item</h3>
                            <p>when you sign up</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="icon-box icon-box-side">
                        <span class="icon-box-icon text-dark">
                            <i class="icon-life-ring"></i>
                        </span>

                        <div class="icon-box-content">
                            <h3 class="icon-box-title">We Support</h3>
                            <p>24/7 amazing services</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Features End -->

@endsection

@section('script')

<script>
    $(document).ready(function() {
        $('.related-carousel').each(function() {
            var $carousel = $(this);
            var categoryId = $carousel.data('category-id');
            var page = $carousel.data('page');
            var isLoading = false;

            $carousel.on('changed.owl.carousel', function(event) {
                if (event.item.index + event.page.size >= event.item.count && !isLoading) {
                    isLoading = true;
                    $.ajax({
                        url: '{{ route('getCategoryProducts') }}',
                        method: 'GET',
                        data: {
                            category_id: categoryId,
                            page: page
                        },
                        success: function(response) {
                            // console.log(response);
                            page++;
                            $carousel.data('page', page);
                            $.each(response.data, function(index, product) {
                                var productHtml = `
                                    <div class="product-item bg-light">
                                        <div class="product-img position-relative overflow-hidden" style="height: 250px;">
                                            <img class="img-fluid w-100 h-100" src="/images/products/${product.feature_image}" alt="${product.name}" style="object-fit: cover;"/>
                                            <div class="product-action">
                                                ${product.stock && product.stock.quantity > 0 ? 
                                                    `<a class="btn btn-outline-dark btn-square add-to-cart" data-product-id="${product.id}" data-offer-id="0" data-price="${product.price}">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>` :
                                                    `<a class="btn btn-outline-dark btn-square disabled" aria-disabled="true">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>`
                                                }
                                                <a class="btn btn-outline-dark btn-square add-to-wishlist" data-product-id="${product.id}" data-offer-id="0" data-price="${product.price}">
                                                    <i class="fa fa-heart"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center py-4">
                                            <a class="h6 text-decoration-none text-truncate" href="/product/${product.slug}">${product.name}</a>
                                            <div class="d-flex align-items-center justify-content-center mt-2">
                                                <h5>${product.price}</h5>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center mt-2">
                                                ${product.stock && product.stock.quantity > 0 ? 
                                                    `<p>Available: ${product.stock.quantity}</p>` : 
                                                    `<p>Out of Stock</p>`
                                                }
                                            </div>
                                        </div>
                                    </div>`;
                                $carousel.trigger('add.owl.carousel', [$(productHtml)]).trigger('refresh.owl.carousel');
                            });
                            isLoading = false;
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching products:', error);
                            isLoading = false;
                        }
                    });
                }
            });
        });
    });
</script>

@endsection