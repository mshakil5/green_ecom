<div class="modal fade" id="modalQuickview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col text-end">
                            <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="fa fa-times"></i></span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="product-details-gallery-area">
                                <div class="product-large-image modal-product-image-large">
                                    <div class="product-image-large-single">
                                        <img id="modal-product-image" class="img-fluid" src="" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="product-details-content-area">
                                <div class="">
                                    <h4 class="title" id="modal-product-name"></h4>
                                    <div class="price" id="modal-product-price"></div>
                                    {{-- <p id="modal-product-description"></p> --}}
                                </div>
                                <div class="product-details-variable">
                                    <div class="variable-single-item d-none">
                                        <span>Color</span>
                                        <div id="modal-product-colors" class="product-variable-color"></div>
                                    </div>
                                    <div class="variable-single-item d-none">
                                        <span>Size</span>
                                        <div id="modal-product-sizes" class="product-variable-color"></div>
                                    </div>
                                    <div class="variable-single-item">
                                        <span>Quantity</span>
                                        <div class="product-variable-quantity">
                                            <input id="modal-product-quantity" min="1" max="100" value="1" type="number">
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details-meta mb-20 ">
                                    <ul>
                                        {{-- <li class="d-inline-block me-2"><a href="#" class="form-submit-btn add-to-wishlist" data-product-id1="" data-offer-id="0" data-price=""><i class="icon-heart"></i>Add to wishlist</a></li> --}}
                                        <input type="hidden" id="modal-card-product-id" value="">
                                        <input type="hidden" id="modal-card-product-price" value="">
                                        <li class="d-inline-block mb-4"><a id="add-to-card-modal-button" href="#" class="contact-submit-btn add-to-cart"  data-product-id="" data-offer-id="0" data-price="" data-image="">
                                            <i class="icon-shopping-cart"></i>Add To Cart</a>
                                        </li>

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

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>