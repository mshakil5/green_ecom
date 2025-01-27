@extends('frontend.layouts.app')

@section('content')
<div class="container for-you">

    @php
        $currency = \App\Models\CompanyDetails::value('currency');
    @endphp

    <div class="breadcrumb-section">
        <div class="breadcrumb-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                        <h3 class="breadcrumb-title">Wishlist</h3>
                        <div class="breadcrumb-nav">
                            <nav aria-label="breadcrumb">
                                <ul>
                                    <li><a href="{{ route('frontend.homepage') }}">Home</a></li>
                                    <li class="active" aria-current="page">Wishlist</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($products->isEmpty())
    <h1 class="title text-center mb-5 mt-4">Wishlist is empty</h1>
    @else
        <h1 class="title text-center mb-5 mt-4">Wishlist</h1>
        <div class="wishlist-section">
            <div class="wishlish-table-wrapper" data-aos="fade-up" data-aos-delay="0">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="table_desc">
                                <div class="table_page table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product_remove">Delete</th>
                                                <th class="product_thumb">Image</th>
                                                <th class="product_name">Product</th>
                                                <th class="product-price">Price</th>
                                                <th class="product_stock d-none">Stock Status</th>
                                                <th class="product_addcart">Add To Cart</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $product)
                                                <tr>
                                                    <td class="product_remove">
                                                        <a href="#" class="remove-from-wishlist" 
                                                        data-product-id="{{ $product->id }}"
                                                        data-offer-id="{{ $product->offer_id }}"
                                                        >
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </td>
                                                    <td class="product_thumb">
                                                        <a href="{{ route('product.show', $product->slug) }}">
                                                            <img src="{{ asset('/images/products/' . $product->feature_image) }}" alt="{{ $product->name }}">
                                                        </a>
                                                    </td>
                                                    <td class="product_name">
                                                        <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                                    </td>
                                                    <td class="product-price">
                                                        @if(isset($product->offer_price))
                                                            <del class="product-default-price-off">{{ $currency }} {{ number_format($product->price, 2) }}</del>
                                                            {{ $currency }} {{ number_format($product->offer_price, 2) }}
                                                        @elseif(isset($product->flash_sell_price))
                                                            <del class="product-default-price-off">{{ $currency }} {{ number_format($product->price, 2) }}</del>
                                                            {{ $currency }} {{ number_format($product->flash_sell_price, 2) }}
                                                        @else
                                                            {{ $currency }} {{ number_format($product->price, 2) }}
                                                        @endif
                                                    </td>
                                                    <td class="product_stock d-none">
                                                    </td>
                                                    <td class="product_addcart">
                                                        <a href="#" class="btn-product btn-cart add-to-cart" 
                                                        data-product-id="{{ $product->id }}" 
                                                        data-offer-id="{{ $product->offer_id }}" 
                                                        data-price="{{ $product->price }}" 
                                                        data-toggle="modal" data-target=".quickAddToCartModal" 
                                                        data-image="{{ asset('images/products/' . $product->feature_image) }}">
                                                            Add To Cart
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection