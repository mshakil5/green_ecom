@extends('admin.layouts.admin')

@section('content')

<section class="content pt-3">

                            
    <div class="container-fluid">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm mb-2">Back</a>
        <div class="row justify-content-md-center">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Order</h3>
                    </div>
                    <div class="card-body">
                        <form id="createThisForm">

                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="purchase_date">Selling Date<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ $order->purchase_date }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="supplier_id">Select Customer<span style="color: red;">*</span></label>
                                        <select class="form-control" id="user_id" name="user_id">
                                            <option value="">Select...</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ $customer->id == $order->user_id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="purchase_type">Transaction Type<span style="color: red;">*</span></label>
                                        <select class="form-control" id="payment_method" name="payment_method">
                                            <option value="cash" {{ $order->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank" {{ $order->payment_method == 'bank' ? 'selected' : '' }}>Bank</option>
                                            <option value="credit" {{ $order->payment_method == 'credit' ? 'selected' : '' }}>Credit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="ref">Ref</label>
                                        <input type="text" class="form-control" id="ref" name="ref" value="{{ $order->ref }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="1">{{ $order->remarks }}</textarea>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="product_id">Choose Product</label>
                                        <select class="form-control" id="product_id" name="product_id">
                                            <option value="">Select...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="price_per_unit">Unit Price</label>
                                        <input type="number" step="0.01" class="form-control" id="price_per_unit" name="price_per_unit" placeholder="Enter unit price">
                                    </div>
                                </div>
                                <div class="col-sm-2 d-none">
                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <select class="form-control" id="size" name="size">
                                            <option value="">Select...</option>
                                            <option value="XS">XS</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-2 d-none">
                                    <div class="form-group">
                                        <label for="color">Color</label>
                                        <select class="form-control" id="color" name="color">
                                            <option value="">Select...</option>
                                            <option value="Black">Black</option>
                                            <option value="White">White</option>
                                            <option value="Red">Red</option>
                                            <option value="Blue">Blue</option>
                                            <option value="Green">Green</option>                                     
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <label for="addProductBtn">Action</label>
                                    <div class="col-auto d-flex align-items-end">
                                        <button type="button" id="addProductBtn" class="btn btn-secondary">Add</button>
                                     </div>
                                </div>

                                <div class="col-sm-12 mt-3">
                                    <h2>Product List:</h2>
                                    <table class="table table-bordered" id="productTable">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderDetails as $detail)
                                            <tr data-id="{{ $detail->id }}">
                                                <td>
                                                    {{ $detail->product->name }}
                                                    <input type="hidden" name="product_id[]" value="{{ $detail->product_id }}">
                                                    <input type="hidden" name="order_detail_id[]" value="{{ $detail->id }}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control quantity" name="quantity[]" value="{{ $detail->quantity }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control price_per_unit" name="price_per_unit[]" value="{{ $detail->price_per_unit }}">
                                                </td>
                                                <td>
                                                    <span class="total_price">{{ $detail->total_price }}</span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-product">Remove</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="container mt-4 mb-5">
                                    <div class="row">
                                        <!-- Left side -->
                                        <div class="col-sm-6">
                                        </div>

                                        <!-- Right side -->
                                        <div class="col-sm-6">
                                            <div class="row mb-3">
                                                <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                                    <span>Item Total Amount:</span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="item_total_amount" readonly value="{{ $order->subtotal_amount }}">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                                    <span>Vat Percent(%):</span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="number" class="form-control" id="vat_percent" name="vat_percent" value="{{ $order->vat_percent }}" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                                    <span>Vat Amount:</span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="number" step="0.01" class="form-control" id="vat" name="vat" value="{{ $order->vat_amount }}" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                                    <span>Discount Amount:</span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="{{ $order->discount_amount }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                                    <span>Net Amount:</span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="net_amount" readonly value="{{ $order->net_amount }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <input type="hidden" name="id" id="id" value="{{ $order->id }}">
                                <button type="button" id="updateOrderBtn" class="btn btn-secondary"><i class="fas fa-save"></i> 
                                  @if ($order->order_type == 2)
                                    Make Order
                                  @else
                                      Update Order  
                                  @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

<script>
    $(document).ready(function() {
        function updateSummary() {
            var itemTotalAmount = 0;
            var vatPercent = parseFloat($('#vat_percent').val()) || 0;

            $('#productTable tbody tr').each(function () {
                var quantity = parseFloat($(this).find('input.quantity').val()) || 0;
                var unitPrice = parseFloat($(this).find('input.price_per_unit').val()) || 0;
                var totalPrice = (quantity * unitPrice).toFixed(2);

                $(this).find('input.price_per_unit').val(unitPrice.toFixed(2));
                $(this).find('.total_price').text(totalPrice);
                itemTotalAmount += parseFloat(totalPrice) || 0;
            });

            $('#item_total_amount').val(itemTotalAmount.toFixed(2) || '0.00');
            $('#vat').val((itemTotalAmount * (vatPercent / 100)).toFixed(2) || '0.00');

            var discount = parseFloat($('#discount').val()) || 0;
            var vat = parseFloat($('#vat').val()) || 0;
            var netAmount = itemTotalAmount - discount + vat;
            $('#net_amount').val(netAmount.toFixed(2) || '0.00');
        }

        $('#addProductBtn').click(function () {
            var selectedProduct = $('#product_id option:selected');
            var productId = selectedProduct.val();
            var productName = selectedProduct.data('name');
            var unitPrice = parseFloat($('#price_per_unit').val()) || 0;
            var quantity = parseFloat($('#quantity').val()) || 1;
            var selectedSize = $('#size').val() || '';
            var selectedColor = $('#color').val() || '';

            if (isNaN(quantity) || quantity <= 0) {
                alert('Quantity must be a positive number.');
                return;
            }

            var productExists = false;
            $('#productTable tbody tr').each(function () {
                var existingProductId = $(this).find('input[name="product_id[]"]').val();
                if (existingProductId == productId) {
                    productExists = true;
                    return false;
                }
            });

            if (productExists) {
                swal("Product Exists", "Product already exists in the list.", "error");
                return;
            }

            var totalPrice = (quantity * unitPrice).toFixed(2);

            var productRow = `<tr>
                                <td>
                                    ${productName}
                                    <input type="hidden" name="product_id[]" value="${productId}">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="quantity[]" value="${quantity}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control price_per_unit" name="price_per_unit[]" value="${unitPrice.toFixed(2)}">
                                </td>
                                <td class="total_price">${totalPrice}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-product">Remove</button>
                                </td>
                            </tr>`;

            $('#productTable tbody').append(productRow);
            $('#quantity').val('');
            $('#price_per_unit').val('');

            updateSummary();
        });

        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
            updateSummary();
        });

        $(document).on('input', '#productTable input.quantity, #productTable input.price_per_unit, #vat, #discount', function() {
            updateSummary();
        });

        $('#updateOrderBtn').click(function(e) {
            e.preventDefault();

            var formData = $('#createThisForm').serializeArray(); 
            var products = [];

            $('#productTable tbody tr').each(function() {
                var productId = $(this).find('input[name="product_id[]"]').val();
                var orderDetailId = $(this).find('input[name="order_detail_id[]"]').val();
                var quantity = $(this).find('input.quantity').val();
                var unitPrice = parseFloat($(this).find('input.price_per_unit').val());
                var totalPrice = $(this).find('.total_price').text();

                products.push({
                    product_id: productId,
                    order_detail_id: orderDetailId,
                    quantity: quantity,
                    unit_price: unitPrice,
                    total_price: totalPrice
                });
            });

            formData.push({ name: 'vat', value: $('#vat').val() });
            formData.push({ name: 'id', value: $('#id').val() });

            formData = formData.filter(function(item) {
                return item.name !== 'product_id' && item.name !== 'quantity' && item.name !== 'price_per_unit';
            });

            formData.push({ name: 'products', value: JSON.stringify(products) });

            console.log(formData);

            $.ajax({
                url: '/admin/order-update',
                method: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    swal({
                        text: "Updated Successfully",
                        icon: "success",
                        button: {
                            text: "OK",
                            className: "swal-button--confirm"
                        }
                    }).then(() => {

                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {

        $('#quantity').on('input', function() {
            if ($(this).val() < 0) {
                $(this).val(1);
            }
        });

        $('#product_id').change(function() {
            var selectedProduct = $(this).find(':selected');
            var pricePerUnit = selectedProduct.data('price');
            $('#quantity').val(1);
            
            if(pricePerUnit) {
                $('#price_per_unit').val(pricePerUnit);
            } else {
                $('#price_per_unit').val('');
            }
        });

        $('#product_id').select2({
            placeholder: "Select product...",
            allowClear: true,
            width: '100%'
        });
    });
</script>

@endsection