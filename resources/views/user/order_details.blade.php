@extends('user.dashboard')

@section('content')


<style>
    @media print {
        .no-print {
            display: none;
        }

        .body {
            margin: 0;
            padding: 0;
        }

        .print-header{
            margin-top: 60px;
        }

        .container {
            width: 100%;
            padding-left: -15px;
            padding-right: -15px;
        }

    }
</style>




<!-- ...:::: Start Breadcrumb Section:::... -->
<div class="breadcrumb-section no-print">
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between justify-content-md-between  align-items-center flex-md-row flex-column">
                    <h3 class="breadcrumb-title">Order Details</h3>
                    <div class="breadcrumb-nav">
                        <nav aria-label="breadcrumb">
                            <ul>
                                <li><a href="{{route('frontend.homepage')}}">Home</a></li>
                                <li><a href="{{route('frontend.shop')}}">Shop</a></li>
                                <li class="active" aria-current="page">Order Details</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- ...:::: End Breadcrumb Section:::... -->

<!-- ...:::: Start Account Dashboard Section:::... -->
<div class="account_dashboard">
    <div class="container">
        <div class="row">


            @include('user.inc.sidebar')

            



            <div class="col-sm-12 col-md-9 col-lg-9">
                <!-- Tab panes -->
                <div class="tab-content dashboard_content" data-aos="fade-up"  data-aos-delay="200">
                    <div class="tab-pane fade show active" id="dashboard">
                        
                        
                    

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-dashboard">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 print-header">
                                            <h4 class="mb-3 text-center">Order Information</h4>
                                            <div class="text-right mb-3 no-print">
                                                <button onclick="window.print()" >
                                                    <i class="fa fa-print"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- User Information -->
                                        <div class="col-md-6">
                                            
                                            <p>
                                                <strong>Invoice:</strong> #{{ $order->invoice }} <br>
                                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($order->purchase_date)->format('d-m-Y') }} <br>
                                                <strong>Payment Method:</strong> 
                                                @if($order->payment_method === 'paypal')
                                                    PayPal
                                                @endif  <br>

                                                <strong>Status:</strong> 
                                                @if ($order->status === 1)
                                                    Pending
                                                @elseif ($order->status === 2)
                                                    Processing
                                                @elseif ($order->status === 3)
                                                    Packed
                                                @elseif ($order->status === 4)
                                                    Shipped
                                                @elseif ($order->status === 5)
                                                    Delivered
                                                @elseif ($order->status === 6)
                                                    Returned
                                                @elseif ($order->status === 7)
                                                    Cancelled
                                                @else
                                                    Unknown
                                                @endif
                                                <br>
                                                
                                                <strong>Address:</strong> 
                                                {{ $order->user->house_number ?? $order->house_number }},
                                                {{ $order->user->street_name ?? $order->street_name }},
                                                <br>
                                                {{ $order->user->town ?? $order->town }},
                                                {{ $order->user->postcode ?? $order->postcode }}
                                            </p>


                                        </div>
                                        <!-- Order Information -->
                                        <div class="col-md-6">
                                            
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($order->orderDetails as $orderDetail)
                                                        <tr>
                                                            <td>
                                                                <img src="{{ asset('/images/products/' . $orderDetail->product->feature_image) }}" alt="{{ $orderDetail->product->name }}" style="width: 100px; height: auto;">
                                                            </td>
                                                            <td>
                                                                {{ $orderDetail->product->name }}
                                                                @if ($orderDetail->size)
                                                                    <small>Size: {{$orderDetail->size}}</small>
                                                                @endif
                                                                @if ($orderDetail->color)
                                                                    <small>Color: {{$orderDetail->color}}</small>
                                                                @endif
                                                            </td>
                                                            <td>{{ $orderDetail->quantity }} X {{ number_format($orderDetail->price_per_unit, 2) }}</td>
                                                            <td>{{ number_format($orderDetail->total_price, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>Subtotal:</strong></td>
                                                        <td style="text-align: right">{{ number_format($order->subtotal_amount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>VAT:</strong></td>
                                                        <td style="text-align: right">{{ number_format($order->vat_amount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>Shipping:</strong></td>
                                                        <td style="text-align: right">{{ number_format($order->shipping_amount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>Discount:</strong></td>
                                                        <td style="text-align: right">{{ number_format($order->discount_amount, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>Total:</strong></td>
                                                        <td style="text-align: right">{{ number_format($order->net_amount, 2) }}</td>
                                                    </tr>

                                                </tfoot>


                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-2">

                                        

                                        <div class="col-8"></div>
                                        <div class="col-4 no-print d-none">
                                            @if ($order->order_type === 0)
                                            <a href="{{ route('generate-pdf', ['encoded_order_id' => base64_encode($order->id)]) }}" class="btn btn-success btn-round btn-shadow" target="_blank">
                                                <i class="fas fa-receipt"></i> Invoice
                                            </a>
                                            @elseif ($order->order_type === 1)
                                            <a href="{{ route('in-house-sell.generate-pdf', ['encoded_order_id' => base64_encode($order->id)]) }}" class="btn btn-success btn-round btn-shadow" target="_blank">
                                                <i class="fas fa-receipt"></i> Invoice
                                            </a>
                                            @endif


                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    


                    </div>

                </div>
            </div>
        </div>
    </div>
</div> <!-- ...:::: End Account Dashboard Section:::... -->






<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cancelForm">
                    <div class="form-group mx-3">
                        <label for="cancelReason">Reason for Cancelling:</label>
                        <textarea class="form-control" id="cancelReason" name="cancelReason" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="cancelOrderId" name="orderId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning btn-rounded" id="submitCancel">Cancel Order</button>
            </div>
        </div>
    </div>
</div>




<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Return Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="returnForm">
                    <input type="hidden" name="order_id" id="returnOrderId">
                    <div id="orderInfo" class="mx-3"></div>
                    <div id="productSelection" class="mx-3"></div>
                    <button type="button" class="btn btn-primary btn-rounded mx-3 mb-3" id="submitReturn">Submit Return</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-cancel', function() {
            var orderId = $(this).data('order-id');
            $('#cancelOrderId').val(orderId);
        });

        $('#submitCancel').click(function() {
            var orderId = $('#cancelOrderId').val();
            var cancelReason = $('#cancelReason').val();
            var cancelUrl = "{{ url('/user') }}/" + orderId + "/cancel";

            $.ajax({
                url: cancelUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    reason: cancelReason
                },
                success: function(response) {
                    $('#cancelModal').modal('hide');
                    swal("Cancelled", "Order cancelled successfully!", "success").then(function() {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('.btn-return').click(function() {
            var orderId = $(this).data('order-id');
            $('#returnOrderId').val(orderId);

            $('#orderInfo').html('');
            $('#productSelection').html('');

            $.ajax({
                url: '{{ route("orders.details.modal") }}',
                method: 'GET',
                data: { order_id: orderId },
                success: function(response) {
                    var formattedDate = moment(response.order.purchase_date).format('DD-MM-YYYY');

                    $('#orderInfo').html(`
                        <p><strong>Invoice:</strong> ${response.order.invoice}</p>
                        <p><strong>Purchase Date:</strong> ${formattedDate}</p>
                    `);

                    var productSelectionHtml = '<h4>Select Products to Return</h4>';
                    response.orderDetails.forEach(function(orderDetail) {
                        productSelectionHtml += `
                            <div class="form-group" name="return_items[${orderDetail.product_id}]">
                                <label>${orderDetail.product.name} (${orderDetail.quantity} available)</label>
                                <input type="hidden" name="return_items[${orderDetail.product_id}][product_id]" value="${orderDetail.product_id}">
                                <input type="number" name="return_items[${orderDetail.product_id}][return_quantity]" min="1" max="${orderDetail.quantity}" class="form-control return-quantity" data-max="${orderDetail.quantity}" value="1">
                                <textarea name="return_items[${orderDetail.product_id}][return_reason]" class="form-control return-reason mt-2" rows="2" placeholder="Reason for return"></textarea>
                                <small class="text-danger" style="display: none;">Quantity exceeds available amount.</small>
                            </div>
                        `;
                    });
                    $('#productSelection').html(productSelectionHtml);

                    $('.return-quantity').on('input', function() {
                        var maxQuantity = $(this).data('max');
                        var currentQuantity = $(this).val();

                        if (parseInt(currentQuantity) > parseInt(maxQuantity)) {
                            $(this).next('.text-danger').show();
                            $(this).val(maxQuantity);
                        } else {
                            $(this).next('.text-danger').hide();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#submitReturn').click(function() {

            var returnItems = [];
            $('[name^="return_items["]').each(function() {
                var productId = $(this).find('[name$="[product_id]"]').val();
                var returnQuantity = $(this).find('[name$="[return_quantity]"]').val();
                var returnReason = $(this).find('[name$="[return_reason]"]').val();

                if (productId && returnQuantity && returnReason) {
                    returnItems.push({
                        product_id: productId,
                        return_quantity: returnQuantity,
                        return_reason: returnReason
                    });
                }
            });

            var finalFormData = {
                order_id: $('#returnOrderId').val(),
                return_items: returnItems
            };

            console.log(finalFormData);

            var returnUrl = "{{ url('/user/order-return') }}" ;


            $.ajax({
                url: returnUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: finalFormData,
                success: function(response) {
                    // console.log(response);
                    $('#returnModal').modal('hide');
                    swal("Cancelled", "Order returned successfully!", "success").then(function() {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                   console.error(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection