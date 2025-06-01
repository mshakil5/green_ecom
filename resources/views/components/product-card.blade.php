<div class="product-default-single border-around" data-aos="fade-up" data-aos-delay="0">
    <div class="product-img-warp">
        <a href="{{ route('product.show', $product->slug) }}" class="product-default-img-link">
            <img src="{{ asset('images/products/' . $product->feature_image) }}" alt="{{ $product->name }}" class="product-default-img img-fluid d-block mx-auto" style="height: 200px; object-fit: cover;">
        </a>
        <div class="product-action-icon-link">
            <ul>
                @if ($product->stock && $product->stock->quantity > 0)
                    @php
                        $colors = $product->stock()->where('quantity', '>', 0)->distinct('color')->whereNotNull('color')->pluck('color');
                        $sizes = $product->stock()->where('quantity', '>', 0)->distinct('size')->whereNotNull('size')->pluck('size');
                    @endphp
                    <li>
                        <a href="#" class="add-to-wishlist" data-product-id="{{ $product->id }}"
                           data-offer-id="0" data-image="{{ asset('images/products/' . $product->feature_image) }}"
                           data-price="{{ $product->price }}">
                            <i class="icon-heart"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="quick-view" title="Quick View" data-bs-toggle="modal" data-bs-target="#modalQuickview"
                           data-product-id="{{ $product->id }}" data-offer-id="0" data-price="{{ $product->price }}"
                           data-product-name="{{ $product->name }}" data-product-description="{{ htmlspecialchars($product->short_description) }}"
                           data-image="{{ asset('images/products/' . $product->feature_image) }}"
                           data-stock="{{ $product->stock->quantity }}" data-colors="{{ $colors->toJson() }}"
                           data-sizes="{{ $sizes->toJson() }}">
                            <i class="icon-eye"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="quick-view" title="Add to Cart" data-bs-toggle="modal" data-bs-target="#modalQuickview"
                           data-product-id="{{ $product->id }}" data-offer-id="0" data-price="{{ $product->price }}"
                           data-product-name="{{ $product->name }}" data-product-description="{{ htmlspecialchars($product->short_description) }}"
                           data-image="{{ asset('images/products/' . $product->feature_image) }}"
                           data-stock="{{ $product->stock->quantity }}" data-colors="{{ $colors->toJson() }}"
                           data-sizes="{{ $sizes->toJson() }}">
                            <i class="icon-shopping-cart"></i>
                        </a>
                    </li>
                @else
                    <li><span class="text-muted">Out of Stock</span></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="product-default-content">
        <h6 class="product-default-link"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h6>
        @php
            $delprice = $product->price * 1.10;
        @endphp
        <span class="product-default-price">
            <del class="product-default-price-off">{{ $company->currency }}{{ number_format($delprice, 2) }}</del>
            {{ $company->currency }}{{ number_format($product->price, 2) }}
        </span>
    </div>
</div>