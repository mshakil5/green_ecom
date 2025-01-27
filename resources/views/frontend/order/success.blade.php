@extends('frontend.layouts.app')

@section('content')

<div class="d-flex flex-column justify-content-center align-items-center vh-100 text-center">
    <h1>Order Placed Successfully!</h1>
    <p>Thank you for shopping with us. Your order has been placed successfully...!</p>
    <p>Inv: {{$orderDetails->invoice}}</p>
    <div class="d-none">
    <p>ID: {{$orderDetails->id}}</p>
    <p>encode: {{base64_encode($orderDetails->id)}} </p>
    <p>decode: {{base64_decode(base64_encode($orderDetails->id))}} </p>
    @php
        $encode_id = base64_encode($orderDetails->id);
    @endphp
    
    <p>phpencode: {{$encode_id}} </p>
    </div>

    <a href="{{ route('generate-pdf', ['encoded_order_id' => base64_encode($orderDetails->id)]) }}" class="btn btn-primary mt-4" target="_blank">
        <i class="fas fa-file-download"></i> Download Invoice (PDF)
    </a>
</div>

@endsection

@section('script')
<script>
    window.onload = function() {
        localStorage.removeItem('cart');
        localStorage.removeItem('wishlist');
        $('.cartCount').text(0);
    };
</script>
@endsection