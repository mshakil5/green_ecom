@extends('frontend.layouts.app')
@section('title', $title)
@section('content')

@php
    $company = \App\Models\CompanyDetails::select('company_logo','currency')->first();
@endphp


<div class="breadcrumb-section d-none">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">{{$product->name}}</h3>
                    {{-- <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="index.html">Home</a></li>
                                <li class="active" aria-current="page">{{ $product->name }}</li>
                            </ul>
                        </nav>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-details-section mt-5">
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <div class="product-details-gallery-area d-flex align-items-center flex-row-reverse" data-aos="fade-up"  data-aos-delay="0">
                    <div class="product-large-image product-large-image-vertical ml-15">


                        <div class="product-image-large-single zoom-image-hover">
                            <img src="{{ asset('/images/products/' . $product->feature_image) }}" alt="Feature Image">
                        </div>

                        @foreach($product->images as $image)
                            <div class="product-image-large-single zoom-image-hover">
                                <img src="{{ asset('/images/products/' . $image->image) }}" alt="Product Image {{ $loop->index + 1 }}">
                            </div>
                        @endforeach
                        
                    </div>
                    <div class="product-image-thumb product-image-thumb-vertical pos-relative">



                        <div class="zoom-active product-image-thumb-single">
                            <img class="img-fluid" src="{{ asset('/images/products/' . $product->feature_image) }}" alt="Feature Thumbnail">
                        </div>

                        @foreach($product->images as $image)
                            <div class="product-image-thumb-single">
                                <img class="img-fluid" src="{{ asset('/images/products/' . $image->image) }}" alt="Thumbnail {{ $loop->index + 1 }}">
                            </div>
                        @endforeach
                        



                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="product-details-content-area" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-details-text">
                        <h4 class="title">{{ $product->name }}</h4>
                        <div class="price">
                            @if($regularPrice)
                                <del>{{ $currency }}{{ $regularPrice }}</del>
                            @endif
                            {{ $currency }}{{ $product->price }} <span class="text-black">excl VAT</span>
                        </div>
                    </div>

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

                    <div class="product-details-variable">

                        @if (($colors && $colors->isNotEmpty()) || ($sizes && $sizes->isNotEmpty()))
                            <h4 class="title">Available Options</h4>

                            @if($colors && $colors->isNotEmpty())
                                <div class="variable-single-item">
                                    <span>Color</span>
                                    <div class="product-variable-color">
                                        @foreach($colors as $color)
                                            <label for="product-color-{{ $color }}">
                                                <input name="product-color" id="product-color-{{ $color }}" class="color-select" type="radio" {{ $loop->first ? 'checked' : '' }}>
                                                <span class="product-color-{{ strtolower(str_replace(' ', '-', $color)) }}"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($sizes && $sizes->isNotEmpty())
                                <div class="variable-single-item">
                                    <span>Size</span>
                                    <div class="product-variable-color">
                                        @foreach($sizes as $size)
                                            <label for="product-size-{{ $size }}">
                                                <input name="product-size" id="product-size-{{ $size }}" class="size-select" type="radio" {{ $loop->first ? 'checked' : '' }}>
                                                <span class="product-size-{{ strtolower(str_replace(' ', '-', $size)) }}">{{ $size }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        @endif

                        

                        @if($product->stock && $product->stock->quantity > 0)

                            <div class="product-block product-block--sales-point">
                                <ul class="sales-points">
                                <li class="sales-point">
                                    <span class="icon-and-text inventory--low">
                                    <span class="icon icon--inventory"></span>
                                    <span data-product-inventory="" data-threshold="2" data-enabled="false">{{ number_format($product->stock->quantity, 0) }} items left</span>
                                    </span>
                                </li>
                                </ul>
                            </div>


                            <div class="d-flex align-items-center">
                                <div class="variable-single-item">
                                    <span>Quantity</span>
                                    <div class="product-variable-quantity">
                                        <input min="1" max="{{ $product->stock->quantity }}" value="1" type="number" class="quantity-input">
                                    </div>
                                </div>

                                <div class="product-add-to-cart-btn add-to-cart" data-product-id="{{ $product->id }}" data-offer-id="0" data-price="{{ $product->price }}" data-image="{{ asset('images/products/' . $product->feature_image) }}">
                                    <a href="">Add To Cart</a>
                                </div>
                            </div>
                        @else
                            <p class="text-danger">Out of Stock</p>
                        @endif
                    </div>

                    <div class="product-details-meta mb-20 d-none">
                        <ul>
                            <li><a href=""><i class="icon-heart"></i>Add to wishlist</a></li>
                        </ul>
                    </div>

                    <div class="">
                        <p>{!! $product->short_description !!}</p>
                        <br>
                        
                    </div>

                    <div style="display: flex; align-items: center; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px;width: 100%;">
                        <i class="fa fa-truck" style="font-size: 24px; color: red; margin-right: 10px;"></i>
                        <span style="font-size: 16px; color: #000;">Delivery all over Bangladesh</span>
                    </div>
                    <div style="display: flex; align-items: center; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px;width: 100%;">
                        <i class="fa fa-truck" style="font-size: 24px; color: red; margin-right: 10px;"></i>
                        <span style="font-size: 16px; color: #000;">Home delivery within 2-5 days</span>
                    </div>
                    <div style="display: flex; align-items: center; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px;width: 100%;">
                        <i class="fa fa-check-circle" style="font-size: 24px; color: green; margin-right: 10px;"></i>
                        <span style="font-size: 16px; color: #000;">Cash on Delivery Available</span>
                    </div>
                    

                    <div class="product-details-social">
                        <ul>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"  class="facebook" target="_blank">
                                <i class="fa fa-facebook"></i> Share
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Start Product Content Tab Section -->
    <div class="product-details-content-tab-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="product-details-content-tab-wrapper" data-aos="fade-up"  data-aos-delay="0">

                        <!-- Start Product Details Tab Button -->
                        <ul class="nav tablist product-details-content-tab-btn d-flex justify-content-center">
                            <li><a class="nav-link active" data-bs-toggle="tab" href="#description">
                                    <h5>Description</h5>
                                </a></li>
                            {{-- <li><a class="nav-link" data-bs-toggle="tab" href="#specification">
                                    <h5>Specification</h5>
                                </a></li> --}}
                            <li><a class="nav-link" data-bs-toggle="tab" href="#review">
                                    <h5>Reviews ({{ $product->reviews->count() }})</h5>
                                </a></li>
                        </ul> <!-- End Product Details Tab Button -->

                        <!-- Start Product Details Tab Content -->
                        <div class="product-details-content-tab">
                            <div class="tab-content">
                                <!-- Start Product Details Tab Content Singel -->
                                <div class="tab-pane active show" id="description">
                                    <div class="single-tab-content-item">
                                        
                                        {!! $product->description !!}

                                    </div>
                                </div> <!-- End Product Details Tab Content Singel -->
                                <!-- Start Product Details Tab Content Singel -->
                                <div class="tab-pane d-none" id="specification">
                                    <div class="single-tab-content-item">
                                        <table class="table table-bordered mb-20">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Compositions</th>
                                                    <td>Polyester</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Styles</th>
                                                    <td>Girly</td>
                                                <tr>
                                                    <th scope="row">Properties</th>
                                                    <td>Short Dress</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p>Fashion has been creating well-designed collections since 2010. The brand offers feminine designs delivering stylish separates and statement dresses which have since evolved into a full ready-to-wear collection in which every item is a vital part of a woman's wardrobe. The result? Cool, easy, chic looks with youthful elegance and unmistakable signature style. All the beautiful pieces are made in Italy and manufactured with the greatest attention. Now Fashion extends to a range of accessories including shoes, hats, belts and more!</p>
                                    </div>
                                </div> <!-- End Product Details Tab Content Singel -->
                                <!-- Start Product Details Tab Content Singel -->
                                <div class="tab-pane" id="review">
                                    <div class="single-tab-content-item">
                                        <div class="reviews">
                                            <h3>Reviews ({{ $product->reviews->count() }})</h3>
                    
                                            @foreach ($product->reviews as $review)
                                                <div class="review">
                                                    <div class="row no-gutters">
                                                        <div class="col-auto">
                                                            <h4><a href="#">{{ $review->user->name ?? '' }}</a></h4>
                                                            <div class="ratings-container">
                                                                <div class="ratings">
                                                                    <div class="ratings-val" style="width: {{ $review->rating * 20 }}%;"></div>
                                                                </div>
                                                            </div>
                                                            <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <div class="col">
                                                            <h4>{{ $review->title ?? '' }}</h4>
                    
                                                            <div class="review-content">
                                                                <p>{{ Str::limit($review->description, 200) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                    
                                        @auth
                                        <div class="review-form">
                                            <h4 class="mt-3">Submit a Review</h4>
                                            <form id="reviewForm" method="POST">
                                                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label for="reviewTitle">Title</label>
                                                        <input type="text" id="reviewTitle" name="title" class="form-control" placeholder="Review Title" required>
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="reviewRating">Rating</label>
                                                        <select id="reviewRating" name="rating" class="form-control" required>
                                                            <option value="5">5 Stars</option>
                                                            <option value="4">4 Stars</option>
                                                            <option value="3">3 Stars</option>
                                                            <option value="2">2 Stars</option>
                                                            <option value="1">1 Star</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="reviewDescription">Description</label>
                                                    <textarea id="reviewDescription" name="description" class="form-control" rows="3" placeholder="Your review" required></textarea>
                                                </div>
                                                <button type="submit" class="form-submit-btn">Submit Review</button>
                                            </form>
                                        </div>
                                        @endauth
                    
                                        @guest
                                        <p>You need to <a href="{{ route('login') }}" style="color: green">log in</a> to submit a review.</p>
                                        @endguest
                                    </div>
                                </div> <!-- End Product Details Tab Content Singel -->
                            </div>
                        </div> <!-- End Product Details Tab Content -->

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Product Content Tab Section -->

    <!-- ...:::: Start Product  Section:::... -->
    <div class="product-section">
        <!-- Start Section Content -->
        <div class="section-content-gap">
            <div class="container">
                <div class="row">
                    <div class="section-content">
                        <h3 class="section-title" data-aos="fade-up"  data-aos-delay="0">Related Products</h3>
                    </div>
                </div>
            </div>
        </div> <!-- End Section Content -->

        <!-- Start Product Wrapper -->
        @php
            $relatedProducts = App\Models\Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->limit(4)
                ->get();
        @endphp
        
        <div class="product-tab-wrapper" data-aos="fade-up" data-aos-delay="50">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="product-default-slider product-default-slider-4grids-1row">
                            @if ($relatedProducts->count() > 0)
                                @foreach($relatedProducts as $product)
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
                                            <span class="product-default-price"><del class="product-default-price-off">{{$company->currency}}{{ number_format($delprice, 2) }}</del> {{$company->currency}}{{ number_format($product->price, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- ...:::: End Product Section:::... -->


    
@endsection

@section('script')

<script>
    $(document).ready(function() {
        $('#reviewForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '{{ route('reviews.store') }}',
                data: {
                    _token: $('input[name="_token"]').val(),
                    product_id: $('#product_id').val(),
                    title: $('#reviewTitle').val(),
                    description: $('#reviewDescription').val(),
                    rating: $('#reviewRating').val(),
                },
                success: function(response) {
                    toastr.success('Review submitted successfully!');
                    $('#reviewForm')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    toastr.error('An error occurred while submitting your review.');
                }
            });
        });
    });
</script>
@endsection