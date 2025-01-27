<div class="modal fade" id="modalAddWishlist" tabindex="-1" aria-hidden="true">
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
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-4">
                                  <div class="modal-add-wishlist-product-img d-flex justify-content-center align-items-center" style="width: 150px; height: 150px;">
                                      <img id="wishlist-product-img" class="img-fluid" src="" alt="Product Image" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                  <div class="modal-add-cart-info"><i class="fa fa-check-square"></i>Added to wishlist successfully!</div>
                                    <div class="modal-add-cart-product-cart-buttons">
                                        <a href="{{ route('wishlist.index') }} " class="wishlistBtn">View Wishlist</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 modal-border">
                            <ul class="modal-add-cart-product-shipping-info">
                                <li> <strong><i class="icon-heart"></i> There are<span class="wishlistCount">0</span> items in your wishlist.</strong></li>
                                <li class="modal-continue-button"><a href="{{ route('frontend.shop') }}" data-bs-dismiss="modal">CONTINUE SHOPPING</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>