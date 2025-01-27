<script>
    $(document).ready(function() {
        function updateCartCount() {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];
            var cartCount = cart.length;
            $('.cartCount').text(cartCount);
        }

        $(document).on('click', '.add-to-cart', function(e) {
            e.preventDefault();

            var productId = $(this).data('product-id') || null;
            var offerId = $(this).data('offer-id');
            var price = $(this).data('price');

            var thisProductId = $('#add-to-card-modal-button').data('product-id') || null;

            console.log('cart-p-id = ' + thisProductId);
            var selectedSize = $('input[name="size"]:checked').val() || null;
            var selectedColor = $('input[name="color"]:checked').val() || null; 

            
            
            // if (!selectedColor) {
            //     toastr.error("Please select a color.", "Error", {
            //         closeButton: true,
            //         progressBar: true,
            //         timeOut: 3000,
            //         positionClass: "toast-top-right",
            //     });
            //     return;
            // }

            // if (!selectedSize) {
            //     toastr.error("Please select a size.", "Error", {
            //         closeButton: true,
            //         progressBar: true,
            //         timeOut: 3000,
            //         positionClass: "toast-top-right",
            //     });
            //     return;
            // }

            var quantity = parseInt($('.quantity-input').val()) || 1;

            var cart = JSON.parse(localStorage.getItem('cart')) || [];

            var existingItem = cart.find(function(item) {
                return item.productId === productId && 
                       item.size === selectedSize && 
                       item.color === selectedColor && 
                       item.offerId === offerId;
            });

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                var cartItem = {
                    productId: productId,
                    offerId: offerId,
                    price: price,
                    size: selectedSize,
                    color: selectedColor,
                    quantity: quantity
                };
                cart.push(cartItem);
            }

            // console.log(cart);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();

            // $(this).removeAttr('data-product-id');
            // $(this).removeAttr('data-offer-id');
            // $(this).removeAttr('data-price');


            toastr.success("Added to cart", "Success", {
                closeButton: true, 
                progressBar: true,
                timeOut: 3000,
                positionClass: "toast-top-right",
            });
            // $('#modalQuickview').modal('hide');
        });

        // $(document).on('click', '.remove-from-cart', function() {
        //     var cart = JSON.parse(localStorage.getItem('cart')) || [];
        //     var index = $(this).data('cart-index');

        //     if (index !== undefined) {
        //         cart.splice(index, 1);
        //         localStorage.setItem('cart', JSON.stringify(cart));

        //         $.ajax({
        //             url: "{{ route('cart.store') }}",
        //             method: "PUT",
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 cart: JSON.stringify(cart)
        //             },
        //             success: function() {
        //                 toastr.success("Removed from cart", "Success", {
        //                     closeButton: true, 
        //                     progressBar: true,
        //                     timeOut: 3000,
        //                     positionClass: "toast-top-right",
        //                 });
        //                 updateCartCount();
        //             }
        //         });
        //     }
        // });

        $(document).on('click', '.cartBtn', function(e){
            e.preventDefault();
            var cartlist = JSON.parse(localStorage.getItem('cart')) || [];
            console.log(JSON.parse(localStorage.getItem('cart')));
            
            $.ajax({
                url: "{{ route('cart.store') }}",
                method: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    cart: JSON.stringify(cartlist)
                },
                success: function() {
                    window.location.href = "{{ route('cart.index') }}";
                }
            });
        });

        updateCartCount();
    });
</script>