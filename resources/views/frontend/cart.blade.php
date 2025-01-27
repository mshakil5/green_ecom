@extends('frontend.layouts.app')

@section('content')

@php
    $currency = \App\Models\CompanyDetails::value('currency');
@endphp

@if(empty($cart))
<h1 class="title text-center mb-5 mt-4">Cart is empty</h1>
@else





<div class="page-content">
    <div class="cart">
        <div class="container">
        <h1 class="title text-center mb-5 mt-4">Shopping Cart</h1>
            <div class="row">
                <div class="col-lg-8">
                    <table class="table table-cart table-mobile" id="cart-table">
                        {{-- <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead> --}}
                        <tbody>
                            
                            @foreach ($cart as $item)
                                @php
                                    $isBundle = isset($item['bundleId']);
                                    $isCampaign = isset($item['campaignId']);
                                    $isBogo = isset($item['bogoId']);
                                    $isSupplier = isset($item['supplierId']);
                                    $entity = $isBundle ? \App\Models\BundleProduct::find($item['bundleId']) : \App\Models\Product::find($item['productId']);
                                    $price = $item['price'] ?? 0;

                                    if (!$isBundle && $entity) {
                                        
                                        $price = $price ?? 0;

                                    }

                                    if ($isBundle) {
                                        $bundle = \App\Models\BundleProduct::find($item['bundleId']);
                                        $stock = $bundle->quantity ?? 0;
                                    } elseif ($isCampaign) {
                                        $campaign = \App\Models\CampaignRequestProduct::find($item['campaignId']);
                                        $stock = $campaign->quantity ?? 0;
                                    } elseif ($isBogo) {
                                        $bogo = \App\Models\BuyOneGetOne::find($item['bogoId']);
                                        $stock = $bogo->quantity ?? 0;
                                    } elseif ($isSupplier) {
                                        $supplierProduct = \App\Models\SupplierStock::where('supplier_id', $item['supplierId'])
                                                        ->where('product_id', $item['productId'])
                                                        ->first();
                                        $stock = $supplierProduct->quantity ?? 0;
                                    } else {
                                        $stock = $entity->stock->quantity ?? 0;
                                    }
                                @endphp
                                <tr data-entity-id="{{ $isBundle ? $entity->id : $entity->id }}" data-entity-type="{{ $isBundle ? 'bundle' : 'product' }}" data-stock="{{ $stock }}">
                                    <td class="product-col">
                                        <div class="product">
                                            <figure class="product-media">
                                                <a href="#">
                                                    <x-image-with-loader src="{{ asset('/images/' . ($isBundle ? 'bundle_product' : 'products') . '/' . $entity->feature_image) }}" alt="{{ $entity->name }}" style="height: 80px; width:80px"/>
                                                </a>
                                            </figure>
                                        </div>
                                    </td>
                                    <td class="product-col">
                                        <div class="product">
                                            <h3 class="product-default-link">
                                                <a>{{ $entity->name }}</a>
                                            </h3>
                                            
                                            @if (isset($item['size']))
                                            <small>Size: {{ $item['size'] ?? '' }}</small>
                                            @endif

                                            @if (isset($item['color']))
                                            <small>Color: {{ $item['color'] ?? '' }}</small>
                                            @endif
                                        </div>
                                        <div class="quantity-col">
                                            <div class="cart-product-quantity">
                                                <input type="number" class="form-control" value="{{ $item['quantity'] }}" min="1" max="{{ $stock }}" step="1" data-decimals="0" style="width: 100px;">
                                            </div>
                                            
                                        </div>
                                        
                                    </td>
                                    <td class="price-col d-none">
                                        {{ $currency }} {{ number_format($price, 2) }}
                                    </td>
                                    <td class="quantity2-col d-none">
                                        <div class="cart-product-quantity">
                                            <input type="number" class="form-control" value="{{ $item['quantity'] }}" min="1" max="{{ $stock }}" step="1" data-decimals="0">
                                        </div>
                                    </td>
                                    <td class="total-col">
                                        {{ $currency }} {{ number_format($price * $item['quantity'], 2) }}
                                    </td>
                                    <td class="remove-col">
                                        <button class="btn-remove remove-from-cart" data-entity-id="{{ $entity->id }}" data-cart-index="{{ $loop->index }}">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-none d-md-inline-block">
                        <a href="{{ route('frontend.shop') }}" class="contact-submit-btn">CONTINUE SHOPPING</a>
                        
                    </div>

                </div>
                <aside class="col-lg-4" id="cart-summary">

                    <div class="coupon_code right aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">
                        <h3>Cart Totals</h3>

                        <div class="summary summary-cart" id="order-summary">
                            <table class="table table-summary">
                                <tbody>
                        
                                    <tr class="summary-total">
                                        <td>Total:</td>
                                        <td id="total">{{$currency}} 0.00</td>
                                    </tr>
                                </tbody>
                            </table>
    
                            <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
                                <div class="text-center">
                                    <button type="submit" class="contact-submit-btn btn-order btn-block">Proceed To Checkout</button>
                                </div>
                            </form>
                        </div>




                    </div>

                
                </aside>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('script')

<script>
    let currencySymbol = '{{ isset($currency) ? $currency : '' }}';

    
    function updateCartTotal() {
        let total = 0;

        $('.table-cart tbody tr').each(function() {
            let priceText = $(this).find('td.price-col').text().trim();
            let price = parseFloat(priceText.replace(/[^0-9.-]+/g, ''));
            let quantity = parseInt($(this).find('div.quantity-col input').val());
            let rowTotal = price * quantity;
            total += rowTotal;
            $(this).find('td.total-col').text(currencySymbol + ' ' + rowTotal.toFixed(2));
        });

        if ($('.table-cart tbody tr').length === 0) {
            $('#order-summary').hide();
        } else {
            $('#order-summary').show();
        }

        $('#total').text(currencySymbol + ' ' + total.toFixed(2));
    }

    function updateLocalStorage(productId, newQuantity) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let item = cart.find(item => item.productId == productId);
        if (item) {
            item.quantity = newQuantity;
            localStorage.setItem('cart', JSON.stringify(cart));
        }
    }

    function updateHiddenInputCart() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        $('input[name="cart"]').val(JSON.stringify(cart));
    }

    $(document).ready(function() {
        updateCartTotal();

        $(document).on('change keyup', 'div.quantity-col input', function() {
            let row = $(this).closest('tr');
            let stock = parseInt(row.data('stock'));
            let quantity = parseInt($(this).val());
            if (quantity > stock) {
                $(this).val(stock);
            }
            updateCartTotal();
            updateLocalStorage(row.data('entity-id'), quantity);
            updateHiddenInputCart();
        });

        function removeFromLocalStorage(productId) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.productId != productId);
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            $('.cartCount').text(totalItems);
        }

        $(document).on('click', '.remove-from-cart', function() {
            let productId = $(this).data('entity-id');
            removeFromLocalStorage(productId);
            updateCartTotal();
            updateHiddenInputCart();
            updateCartCount();
            // remove product item from session
            $.ajax({
                url: '{{ route("cart.remove") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    productId: productId
                },
                success: function(response) {
                    toastr.success('Product removed from cart', 'Success');
                    // console.log(response);
                },
                error: function(xhr) {
                    console.error('Error removing product from session cart');
                }
            });

            $(this).closest('tr').fadeOut('fast', function () {
                $(this).remove();

                if ($('#cart-table tbody tr').length === 0) {
                    $('#cart-summary').fadeOut('fast');
                }
            });
            // console.log('cart', JSON.parse(localStorage.getItem('cart')));
        });
    });
</script>

@endsection