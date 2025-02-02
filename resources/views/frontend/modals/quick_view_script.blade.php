<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js"></script>

<script>
    $(document).ready(function () {
            $(document).on('click', '.quick-view', function () {

            $('#modal-product-name').text('');
            $('#modal-product-description').text('');
            $('#modal-product-price').html('');
            $('#modal-product-quantity').val(1).attr('max', 1);
            $('#modal-product-image').attr('src', '');
            $('#modal-product-colors').html('');
            $('#modal-product-sizes').html('');
            $('.add-to-wishlist').attr('data-product-id', '').attr('data-price', '');
            $('.add-to-wishlist').attr('data-product-id1', '').attr('data-price1', '');
            $('#add-to-card-modal-button').attr('data-product-id', '').attr('data-price', '');
            $('#add-to-card-modal-button').attr('data-product-id1', '').attr('data-price1', '');


            var productId = $(this).data('product-id');
            var productName = $(this).data('product-name');
            var productDescription = $(this).data('product-description');
            var productPrice = $(this).data('price');
            var productImage = $(this).data('image');
            var productColors = JSON.parse($(this).attr('data-colors'));
            var productSizes = JSON.parse($(this).attr('data-sizes'));
            var productStock = $(this).data('stock');
            

        
                console.log('quickview product id = ' + productId);
                
            $('#modal-card-product-id').val(productId);
            $('#modal-card-product-price').val(productPrice);

            $('#modal-product-name').text(productName);
            $('#modal-product-price').html(`৳ ${productPrice}`);
            $('#modal-product-quantity').attr('max', productStock);

            $('#modal-product-image').attr('src', productImage);

            $('.add-to-wishlist').attr('data-product-id1', productId);
            $('.add-to-wishlist').attr('data-price', productPrice);

            
            $('#add-to-card-modal-button').attr('data-product-id', productId);
            $('#add-to-card-modal-button').attr('data-price', productPrice);
            $('.add-to-card').attr('data-product-id', productId);
            $('.add-to-card').attr('data-price', productPrice);

            var colorOptionsHtml = '';
            productColors.forEach(function (color) {
                var colorId = `modal-product-color-${color.toLowerCase()}`;
                colorOptionsHtml += `
                    <label for="${colorId}">
                        <input name="modal-product-color" id="${colorId}" class="color-select" type="radio">
                        <span class="product-color-${color.toLowerCase()}"></span>
                    </label>
                `;
            });
            $('#modal-product-colors').html(colorOptionsHtml);

            var sizeOptionsHtml = '';
            productSizes.forEach(function (size) {
                var sizeId = `modal-product-size-${size.toLowerCase()}`;
                sizeOptionsHtml += `
                    <label for="${sizeId}">
                        <input name="modal-product-size" id="${sizeId}" class="size-select" type="radio">
                        <span class="product-size">${size}</span>
                    </label>
                `;
            });
            $('#modal-product-sizes').html(sizeOptionsHtml);

            $('#modalQuickview').modal('show');
        });

        $('#modalQuickview').on('hidden.bs.modal', function () {
            $('#modal-product-name').text('');
            $('#modal-product-description').text('');
            $('#modal-product-price').html('');
            $('#modal-product-quantity').val(1).attr('max', 1);
            $('#modal-product-image').attr('src', '');
            $('#modal-product-colors').html('');
            $('#modal-product-sizes').html('');
            $('.add-to-wishlist').attr('data-product-id', '').attr('data-price', '');
            $('.add-to-wishlist').attr('data-product-id1', '').attr('data-price1', '');
            $('#add-to-card-modal-button').attr('data-product-id', '').attr('data-price', '');
            $('#add-to-card-modal-button').attr('data-product-id1', '').attr('data-price1', '');
        });
    });
</script>