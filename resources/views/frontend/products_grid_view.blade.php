@extends('frontend.layouts.app')

@section('content')

@php
    $currency = \App\Models\CompanyDetails::value('currency');
@endphp

<div class="breadcrumb-section">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">{{ ucwords(str_replace('-', ' ', $ptype)) }}</h3>
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="{{ route('frontend.homepage') }}">Home</a></li>
                                <li class="active" aria-current="page">{{ ucwords(str_replace('-', ' ', $ptype)) }}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-section">
    <div class="container">
        <div class="row flex-column-reverse flex-lg-row">
            <div class="col-lg-3">
                <div class="siderbar-section" data-aos="fade-up"  data-aos-delay="0">
                    <input type="text" id="ptype" value="{{ $ptype }}" hidden>
                    <input type="hidden" id="currency" value="{{$currency}}">
                    <div class="sidebar-single-widget">
                        <h6 class="sidebar-title">FILTER BY PRICE</h6>
                        <div class="sidebar-content">
                            <div id="slider-range"></div>
                            <div class="filter-type-price">
                                <label for="amount">Price range:</label>
                                <input type="text" id="amount">
                                <input type="hidden" id="price-min">
                                <input type="hidden" id="price-max">
                            </div>
                        </div>
                    </div>

                    @if($categories->count() > 0)
                    <div class="sidebar-single-widget" >
                        <h6 class="sidebar-title">CATEGORIES</h6>
                        <div class="sidebar-content">
                            <div class="filter-type-select">
                                <ul>
                                    @foreach($categories as $category)
                                        <li>
                                            <label class="checkbox-default" for="category_{{ $category->id }}">
                                                <input type="checkbox" id="category_{{ $category->id }}" name="category" value="{{ $category->id }}" checked>
                                                <span>{{ $category->name }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($brands->count() > 0)
                    <div class="sidebar-single-widget">
                        <h6 class="sidebar-title">BRANDS</h6>
                        <div class="sidebar-content">
                            <div class="filter-type-select">
                                <ul>
                                    @foreach($brands as $brand)
                                        <li>
                                            <label class="checkbox-default" for="brand_{{ $brand->id }}">
                                                <input type="checkbox" id="brand_{{ $brand->id }}" name="brand" value="{{ $brand->id }}">
                                                <span>{{ $brand->name }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($colors->count() > 0)
                    <div class="sidebar-single-widget d-none">
                        <h6 class="sidebar-title">COLORS</h6>
                        <div class="sidebar-content">
                            <div class="filter-type-select">
                                <ul>
                                    @foreach($colors as $color)
                                        <li>
                                            <label class="checkbox-default" for="color_{{ $color->color }}">
                                                <input type="checkbox" id="color_{{ $color->color }}" name="color" value="{{ $color->color }}">
                                                <span>{{ $color->color }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($sizes->count() > 0)
                    <div class="sidebar-single-widget d-none">
                        <h6 class="sidebar-title">SIZES</h6>
                        <div class="sidebar-content">
                            <div class="filter-type-select">
                                <ul>
                                    @foreach($sizes as $size)
                                        <li>
                                            <label class="checkbox-default" for="size_{{ $size->size }}">
                                                <input type="checkbox" id="size_{{ $size->size }}" name="size" value="{{ $size->size }}">
                                                <span>{{ $size->size }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            <div class="col-lg-9">
                <div class="shop-sort-section d-none" data-aos="fade-up"  data-aos-delay="0">
                    <div class="container">
                        <div class="row">
                            <div class="sort-box d-flex justify-content-between align-items-center flex-wrap">
                                <div class="sort-tablist">
                                    <ul class="tablist nav sort-tab-btn">
                                        <li><a class="nav-link active" data-bs-toggle="tab" href="#layout-3-grid"><img src="assets/images/icon/bkg_grid.png" alt=""></a></li>
                                        <li><a class="nav-link" data-bs-toggle="tab" href="#layout-list"><img src="assets/images/icon/bkg_list.png" alt=""></a></li>
                                    </ul>
                                </div>

                                <div class="sort-select-list">
                                    <form action="#">
                                        <fieldset>
                                            <select name="speed" id="speed">
                                                <option>Sort by average rating</option>
                                                <option>Sort by popularity</option>
                                                <option selected="selected">Sort by newness</option>
                                                <option>Sort by price: low to high</option>
                                                <option>Sort by price: high to low</option>
                                                <option>Product Name: Z</option>
                                            </select>
                                        </fieldset>
                                    </form>
                                </div>

                                <div class="page-amount">
                                    <span>Showing 1-9 of 21 results</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="sort-product-tab-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="tab-content tab-animate-zoom">
                                    <div class="tab-pane active show sort-layout-single" id="layout-3-grid">
                                        <div class="row" id="product-list">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-pagination text-center d-none" data-aos="fade-up"  data-aos-delay="0">
                    <ul>
                        <li><a href="#">Previous</a></li>
                        <li><a class="active" href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    $(document).ready(function () {
        var currency = $('#currency').val();
        var minPrice = {{ $minPrice }};
        var maxPrice = {{ $maxPrice }};
        var selectedCategories = [];
        var selectedColors = [];
        var selectedSizes = [];
        var selectedBrands = [];

        $("#slider-range").slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [minPrice, maxPrice],
            slide: function (event, ui) {
                $("#amount").val(currency + ui.values[0] + " - " + currency + ui.values[1]);
            },
            change: function (event, ui) {
                $("#price-min").val(ui.values[0]);
                $("#price-max").val(ui.values[1]);
                prepareFilterData();
            },
        });

        $("#amount").val(currency + $("#slider-range").slider("values", 0) +
            " - " + currency + $("#slider-range").slider("values", 1));

        function prepareFilterData() {
            selectedCategories = $("input[name='category']:checked").map(function () {
                return $(this).val();
            }).get();

            selectedBrands = $("input[name='brand']:checked").map(function () {
                return $(this).val();
            }).get();

            selectedColors = $("input[name='color']:checked").map(function () {
                return $(this).val();
            }).get();

            selectedSizes = $("input[name='size']:checked").map(function () {
                return $(this).val();
            }).get();

            var min = $("#price-min").val() || minPrice;
            var max = $("#price-max").val() || maxPrice;
            var ptype = $('#ptype').val();
            var filterData = {
                start_price: min,
                end_price: max,
                categories: selectedCategories,
                brands: selectedBrands,
                colors: selectedColors,
                sizes: selectedSizes,
                ptype: ptype,
            };

            sendFilterRequest(filterData);
        }

        $("input[name='category'], input[name='brand'], input[name='color'], input[name='size']")
            .off('change')
            .on('change', prepareFilterData);

        function sendFilterRequest(filterData) {




            $.ajax({
                url: '/products/type-filter',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: filterData,
                success: function (response) {
                    // console.log(response);
                $('#product-list').empty();

                $.each(response.products, function (index, product) {
                    var price = parseFloat(product.price);
                    var delPrice = (price * 1.1).toFixed(2);
                    var formattedPrice = price.toFixed(2);
                    var stockQuantity = (product.stock && product.stock.quantity) ? product.stock.quantity : 0;

                    var productHTML = `
                        <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                            <div class="product-default-single border-around" data-aos="fade-up" data-aos-delay="0">
                                <div class="product-img-warp">
                                    <a href="{{ route('product.show', '') }}/${product.slug}" class="product-default-img-link">
                                        <img src="{{ asset('images/products/') }}/${product.feature_image}" alt="${product.name}" class="product-default-img img-fluid d-block mx-auto" style="height: 200px; object-fit: cover;">
                                    </a>
                                    <div class="product-action-icon-link">
                                        <ul>
                                            ${stockQuantity > 0 ? `
                                                <li>
                                                    <a href="#" class="add-to-wishlist" 
                                                    data-product-id="${product.id}" 
                                                    data-offer-id="0" 
                                                    data-image="{{ asset('images/products/') }}/${product.feature_image}" data-price="${price}">
                                                        <i class="icon-heart"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="quick-view" title="Quick View" data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                    data-product-id="${product.id}" 
                                                    data-offer-id="0" data-price="${price}" 
                                                    data-product-name="${product.name}"
                                                    data-image="{{ asset('images/products/') }}/${product.feature_image}"
                                                    data-stock="${stockQuantity}" 
                                                    data-colors='${JSON.stringify(product.colors)}' 
                                                    data-sizes='${JSON.stringify(product.sizes)}'>
                                                    <i class="icon-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="quick-view" title="Quick View" data-bs-toggle="modal" data-bs-target="#modalQuickview"
                                                    data-product-id="${product.id}" 
                                                    data-offer-id="0" data-price="${price}" 
                                                    data-product-name="${product.name}"
                                                    data-product-description="${product.description}"
                                                    data-image="{{ asset('images/products/') }}/${product.feature_image}"
                                                    data-stock="${stockQuantity}"
                                                    data-colors='${JSON.stringify(product.colors)}' 
                                                    data-sizes='${JSON.stringify(product.sizes)}'>
                                                    <i class="icon-shopping-cart"></i>
                                                    </a>
                                                </li>
                                            ` : `
                                                <li>
                                                    <span class="text-muted">Out of Stock</span>
                                                </li>
                                            `}
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-default-content">
                                    <h6 class="product-default-link"><a href="{{ route('product.show', '') }}/${product.slug}">${product.name}</a></h6>
                                    <span class="product-default-price"><del class="product-default-price-off">${response.currency}${delPrice}</del> ${response.currency}${formattedPrice}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#product-list').append(productHTML);
                });
            },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                },
            });
        }

        prepareFilterData();
    });
</script>

@endsection