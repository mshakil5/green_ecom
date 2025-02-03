@extends('frontend.layouts.app')

@section('content')
<style>
    .checkout-container {
        text-align: center;
        margin-top: 20px;
    }
    .buttons-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 10px;
    }
    .checkout-btn {
        padding: 12px 12px;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        width: 150px;
    }

    .checkout-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }
    .checkout-buttons button {
        border: 1px solid #80808040;
        padding: 10px 12px;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 150px;
    }
    .paylater { background-color: #ffffff; color: #003087; border: 2px solid #010000; }
    .paypal { background-color: #ffffff; color: #003087; border: 2px solid #1a48aa; }
    .gpay { background-color: rgb(255, 255, 255); color: white; border: 2px solid #1a48aa;}
    .divider {
        text-align: center;
        margin-top: 15px;
        color: gray;
    }

    .customRadioButton{
        height: 25px;
        width: 25px;
    }

    @media (max-width: 768px) {
        .buttons-container {
            flex-direction: column;
            align-items: center;
        }
        .checkout-btn {
            width: 100%;
            max-width: 300px;
        }
    }

    @media (max-width: 767px) {
        .customRadioButton {
            height: 20px !important;
            width: 20px !important;
        }

        .option{
            padding: 5px !important;
        }
    }

    .option-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }
        .option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            cursor: pointer;
            width: 100%;
        }
        .option div {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }
        .option i {
            font-size: 24px;
            color: red;
        }
        .option.selected {
            background-color: #e9f2ff;
            border-color: #007bff;
        }

        .accordion-button:not(.collapsed),
        .accordion-button:focus {
            outline: none;
            border-color: transparent;
            box-shadow: none;
            background-color: transparent;
        }
        .accordion-button::after {
            width: 11px;
            height: 11px;
            border-radius: 100%;
            background-color: var(--bs-danger);
            background-image: none !important;
        }
        .accordion-button.collapsed::after {
            background-color: var(--bs-gray-300);
        }

        .card-element-container{
            border: 1px solid #80808040;
            border-radius:7px;
        }

</style>
<div class="page-content">
    <div class="checkout">
        <div class="p-2">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-6">
                    <div id="alertContainer"></div>

                    <div class="row my-3">
                        <div class="col-md-12 form-group">
                            <h2 class="checkout-title">Contact</h2>
                            <div class="contact-details-single-item">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email *" value="{{Auth::user()->email ?? ''}}">
                            </div>
                            <div class="contact-details-single-item">
                                <input type="checkbox" id="offermail" name="offermail" class="customRadioButton" style="width: 7%"> Email me with news and offers
                            </div>
                        </div>
                    </div>

                    <div class="option-container">
                        <h2 class="checkout-title">Delivery</h2>
                        <label class="option selected" onclick="showSection('pickup', this)">
                            <div>
                                <input type="radio" name="shipping" class="customRadioButton" style="width: 7%" checked> 
                                    <span>Pickup In Store</span>
                            </div>
                            <i class="fa fa-home px-4" style="font-size: 24px; color: red;  margin-left: auto;"></i>
                        </label>
                        <label class="option"  onclick="showSection('ship', this)">
                            <div>
                                <input type="radio" name="shipping" class="customRadioButton" style="width: 7%" > <span>Ship</span>
                            </div>
                            <i class="fa fa-truck  px-4" style="font-size: 24px; color: red; margin-left: auto;"></i>
                        </label>
                    </div>

                    
                    <input type="hidden" id="shippingMethod" name="shippingMethod" value="0">

                    <div id="billingDetails" class="mt-2">
                        <h2 class="checkout-title">Shipping Details</h2>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>First Name <span style="color: red;">*</span></label>
                                <input class="form-control" id="first_name" type="text" placeholder="" value="{{ Auth::user()->name ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Last Name <span style="color: red;">*</span></label>
                                <input class="form-control" id="last_name" type="text" placeholder="" value="{{ Auth::user()->surname ?? '' }}">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Phone <span style="color: red;">*</span></label>
                                <input class="form-control" id="phone" type="text" placeholder="" value="{{ Auth::user()->phone ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>House Number <span style="color: red;">*</span></label>
                                <input class="form-control" type="text" placeholder="" id="house_number" value="{{ Auth::user()->house_number ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Street Name <span style="color: red;">*</span></label>
                                <input class="form-control" type="text" placeholder="" id="street_name" value="{{ Auth::user()->street_name ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Town <span style="color: red;">*</span></label>
                                <input class="form-control" type="text" placeholder="" id="town" value="{{ Auth::user()->town ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Postcode <span style="color: red;">*</span></label>
                                <input class="form-control" type="text" placeholder="" id="postcode" value="{{ Auth::user()->postcode ?? '' }}">
                            </div>
                            <div class="col-md-12 d-none">
                                <div class="form-group">
                                    <label for="address">Note</label>
                                    <textarea class="form-control" id="note" name="note" rows="3" placeholder=""></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                @guest
                                    <a href="{{ route('register') }}" class="custom-control custom-checkbox">
                                        Create an account ?
                                    </a>
                                @endguest
                            </div>
                            {{--
                            @if(auth()->check())
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="shipto">
                                    <label class="custom-control-label" for="shipto" data-toggle="collapse" data-target="#shipping-address">Ship to different address</label>
                                </div>
                            </div>
                            @endif
                            --}}
                        </div>
                    </div>

                    <div class="row my-3" id="pickupDetails" style="display: none;">
                        <div class="col-md-12 form-group">
                            <label class="mb-2">Pickup Location</label>
                            <div class="contact-details-single-item">
                                <div class="contact-details-icon">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="contact-details-content contact-phone">
                                    <span style="font-weight: bold;">{{ $companyAddress1 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="option-container mb-3">
                        <h2 class="checkout-title">Billing address</h2>
                        <label class="option selected" onclick="toggleDiffAddress('sameasshipping')">
                            <div>
                                <input type="radio" name="differentAddress" class="customRadioButton" value="sameasshipping" style="width: 7%" checked> 
                                <span>Same as shipping address</span>
                            </div>
                            <i class="fa fa-home px-4" style="font-size: 24px; color: red; margin-left: auto;"></i>
                        </label>
                        <label class="option" onclick="toggleDiffAddress('differentaddress')">
                            <div>
                                <input type="radio" name="differentAddress" class="customRadioButton" value="differentaddress" style="width: 7%"> 
                                <input type="hidden" id="is_diff_address" name="is_diff_address" value="1">
                                <span>Use a different billing address</span>
                            </div>
                            <i class="fa fa-home px-4" style="font-size: 24px; color: red; margin-left: auto;"></i>
                        </label>
                        <script>
                            function toggleDiffAddress(value) {
                                const diffAddress = document.getElementById('diffAddress');
                                const isDiffAddressInput = document.getElementById('is_diff_address');

                                if (value === 'differentaddress') {
                                    diffAddress.style.display = 'block';
                                    isDiffAddressInput.value = '0';
                                } else {
                                    diffAddress.style.display = 'none';
                                    isDiffAddressInput.value = '1';
                                }
                            }
                        </script>

                        
                        <div id="diffAddress">
                            <div class="row">
                                
                                <h2 class="checkout-title">Different Address</h2>

                                <div class="col-md-6 form-group">
                                    <label>First Name <span style="color: red;">*</span></label>
                                    <input class="form-control" type="text" placeholder="" id="billing_first_name">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Last Name <span style="color: red;">*</span></label>
                                    <input class="form-control" type="text" placeholder="" id="billing_last_name">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>Phone <span style="color: red;">*</span></label>
                                    <input class="form-control" id="billing_phone" type="text" placeholder="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>House Number <span style="color: red;">*</span></label>
                                    <input class="form-control" id="billing_house_number" type="text" placeholder="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Street Name <span style="color: red;">*</span></label>
                                    <input class="form-control" id="billing_street_name" type="text" placeholder="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Town <span style="color: red;">*</span></label>
                                    <input class="form-control" id="billing_town" type="text" placeholder="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Postcode <span style="color: red;">*</span></label>
                                    <input class="form-control" id="billing_postcode" type="text" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <aside class="col-lg-4 p-3 rounded" style="background-color: #F5F5F5">
                    <div class="summary">
                        <h3 class="summary-title">Your Order</h3>
                            <table class="table table-summary">
                                <tbody>
                                    @php
                                        $currency = \App\Models\CompanyDetails::value('currency');
                                        $total = 0;
                                        $totalWithWarranty = 0;
                                        $shippingCharge = 0;
                                    @endphp

                                    @foreach ($cart as $item)
                                        @php
                                            $isBundle = isset($item['bundleId']);
                                            $entity = $isBundle ? \App\Models\BundleProduct::find($item['bundleId']) : \App\Models\Product::find($item['productId']);

                                            $itemTotal = 0;
                                            $itemTotalWithWarranty = 0;
                                            $price = $item['price'];

                                            if (!$isBundle && $entity) {
                                                $itemTotal = $price * $item['quantity'];
                                                $itemTotalWithWarranty = $itemTotal;
                                            } else {
                                                $bundlePrice = $entity->price ?? $entity->total_price;
                                                $itemTotal = $bundlePrice * $item['quantity'];
                                                $itemTotalWithWarranty = $itemTotal;
                                            }

                                            if (isset($item['warrantyId'])) {
                                                $warranty = \App\Models\ProductWarranty::find($item['warrantyId']);
                                                if ($warranty) {
                                                    $warrantyDuration = $warranty->warranty_duration;
                                                    $priceIncrease = ($price * $warranty->price_increase_percent) / 100;
                                                    $itemTotalWithWarranty += $priceIncrease * $item['quantity'];
                                                }
                                            }

                                            $total += $itemTotal;
                                            $totalWithWarranty += $itemTotalWithWarranty;

                                            // Calculate shipping charges
                                            $deliveryCharges = \App\Models\DeliveryCharge::get();
                                            foreach ($deliveryCharges as $charge) {
                                                if ($total >= $charge->min_price && $total <= $charge->max_price) {
                                                    $shippingCharge = $charge->delivery_charge;
                                                    break;
                                                }
                                            }
                                        @endphp

                                        <tr data-entity-id="{{ $entity->id }}" data-entity-type="{{ $isBundle ? 'bundle' : 'product' }}">
                                            <td >
                                                <div class="d-flex align-items-center bg-muted">
                                                    <div style="position: relative;">
                                                        <x-image-with-loader src="{{ asset('/images/' . ($isBundle ? 'bundle_product' : 'products') . '/' . $entity->feature_image) }}" class="p-1 " alt="{{ $entity->name }}" style="width: 77px; height: 77px; object-fit: contain;" />
                                                        <span style="position: absolute;font-size: 8px; top: 0; right: 4; background-color: red; color: white; padding: 2px 5px; border-radius: 50%;">{{ $item['quantity'] }}</span>
                                                    </div>
                                                    <span class="ml-2">{{ Str::limit($entity->name, 20) }}
                                                        <br>
                                                        @if (isset($item['warrantyId']) && $warranty)
                                                        {{ $currency }}{{$priceIncrease ?? ''}}
                                                       ({{ $warrantyDuration ?? '' }})
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td  style="text-align: right" class="d-none">{{ $currency }}{{ number_format($price, 2) }}</td>
                                            <td class="text-center d-none">{{ $item['quantity'] }}</td>
                                            <td class="d-none">{{ $currency }}{{ number_format($itemTotal, 2) }}</td>
                                            <td style="text-align: right">{{ $currency }}{{ number_format($itemTotalWithWarranty, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <input type="hidden" id="shipping-charge-input" value="{{ $shippingCharge }}">
                                    <tr class="summary-subtotal">
                                        <td>Subtotal:</td>
                                        <td style="text-align: right">{{ $currency }}{{ number_format($totalWithWarranty, 2) }}</td>
                                    </tr>
                                    <tr class="summary-subtotal d-none">
                                        <td>Sub Total with Warranty:</td>
                                        <td style="text-align: right">{{ $currency }}{{ number_format($totalWithWarranty, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Charge:</td>
                                        <td style="text-align: right" id="shipping-charge">{{ $currency }}{{ number_format($shippingCharge, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Vat ({{ $vatPercent }}%):</td>
                                        <td style="text-align: right" id="vat-charge">{{ $currency }}00.00</td>
                                    </tr>
                                    <tr class="d-none" id="discount-row">
                                        <td>Discount:</td>
                                        <td style="text-align: right" id="discount">{{ $currency }}00.00</td>
                                        <input type="hidden" id="discount-amount" value="0">
                                    </tr>
                                </tbody>
                            </table>

    

                            

                            <div class="mb-2">
                                <div class="d-flex justify-content-between mt-2">
                                    <h5>Total</h5>
                                    <h5 id="total-amount" class="summary-total">{{ $currency }}{{ number_format($totalWithWarranty, 2) }}</h5>
                                </div>
                            </div>

                            <div id="loader" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                            <div class="coupon_inner">
                                <p>Enter your coupon code if you have one.</p>
    
                                <div class="form-group checkout-discount">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white" id="couponName" required placeholder="Coupon Code">
                                        <div class="input-group-append">
                                            <button type="submit" id="applyCoupon" class="btn btn-primary"><span>Apply Coupon</span></button>
                                        </div>
                                    </div>
                                    <div id="couponDetails" class="mt-2 alert alert-success" style="display: none;">
                                        <strong>Coupon Applied!</strong>
                                    </div>
                                    <input type="hidden" id="couponId" name="coupon_id" value="">
                                    <div style="display: none;">
                                        <span id="couponValue"></span> <span id="couponType"></span>
                                    </div>
                                </div>
    
                            </div>

                            <div>
                                <input type="checkbox" name="terms" class="" style="width: 7%" > 
                                        Please accept <a href="{{ route('frontend.privacy-policy') }}">privacy and policy</a>, <a href="{{ route('frontend.terms-and-conditions') }}">terms and conditions</a>.
                                {{-- <div class="order_button pt-15">
                                    <button class="btn-order btn-block" type="submit" id="placeOrderBtn">Proceed to Order</button>
                                </div> --}}
                            </div>

                            <div class="text-center mt-2 d-none">
                                <p class="fw-bold">Express checkout</p>
                                <div class="checkout-buttons">
                                    <button class="paypal" id="paypalOrderBtn">
                                        <svg width="101px" height="32" viewBox="0 0 101 32" preserveAspectRatio="xMinYMin meet" xmlns="http:&#x2F;&#x2F;www.w3.org&#x2F;2000&#x2F;svg"><path fill="#003087" d="M 12.237 2.8 L 4.437 2.8 C 3.937 2.8 3.437 3.2 3.337 3.7 L 0.237 23.7 C 0.137 24.1 0.437 24.4 0.837 24.4 L 4.537 24.4 C 5.037 24.4 5.537 24 5.637 23.5 L 6.437 18.1 C 6.537 17.6 6.937 17.2 7.537 17.2 L 10.037 17.2 C 15.137 17.2 18.137 14.7 18.937 9.8 C 19.237 7.7 18.937 6 17.937 4.8 C 16.837 3.5 14.837 2.8 12.237 2.8 Z M 13.137 10.1 C 12.737 12.9 10.537 12.9 8.537 12.9 L 7.337 12.9 L 8.137 7.7 C 8.137 7.4 8.437 7.2 8.737 7.2 L 9.237 7.2 C 10.637 7.2 11.937 7.2 12.637 8 C 13.137 8.4 13.337 9.1 13.137 10.1 Z"></path><path fill="#003087" d="M 35.437 10 L 31.737 10 C 31.437 10 31.137 10.2 31.137 10.5 L 30.937 11.5 L 30.637 11.1 C 29.837 9.9 28.037 9.5 26.237 9.5 C 22.137 9.5 18.637 12.6 17.937 17 C 17.537 19.2 18.037 21.3 19.337 22.7 C 20.437 24 22.137 24.6 24.037 24.6 C 27.337 24.6 29.237 22.5 29.237 22.5 L 29.037 23.5 C 28.937 23.9 29.237 24.3 29.637 24.3 L 33.037 24.3 C 33.537 24.3 34.037 23.9 34.137 23.4 L 36.137 10.6 C 36.237 10.4 35.837 10 35.437 10 Z M 30.337 17.2 C 29.937 19.3 28.337 20.8 26.137 20.8 C 25.037 20.8 24.237 20.5 23.637 19.8 C 23.037 19.1 22.837 18.2 23.037 17.2 C 23.337 15.1 25.137 13.6 27.237 13.6 C 28.337 13.6 29.137 14 29.737 14.6 C 30.237 15.3 30.437 16.2 30.337 17.2 Z"></path><path fill="#003087" d="M 55.337 10 L 51.637 10 C 51.237 10 50.937 10.2 50.737 10.5 L 45.537 18.1 L 43.337 10.8 C 43.237 10.3 42.737 10 42.337 10 L 38.637 10 C 38.237 10 37.837 10.4 38.037 10.9 L 42.137 23 L 38.237 28.4 C 37.937 28.8 38.237 29.4 38.737 29.4 L 42.437 29.4 C 42.837 29.4 43.137 29.2 43.337 28.9 L 55.837 10.9 C 56.137 10.6 55.837 10 55.337 10 Z"></path><path fill="#009cde" d="M 67.737 2.8 L 59.937 2.8 C 59.437 2.8 58.937 3.2 58.837 3.7 L 55.737 23.6 C 55.637 24 55.937 24.3 56.337 24.3 L 60.337 24.3 C 60.737 24.3 61.037 24 61.037 23.7 L 61.937 18 C 62.037 17.5 62.437 17.1 63.037 17.1 L 65.537 17.1 C 70.637 17.1 73.637 14.6 74.437 9.7 C 74.737 7.6 74.437 5.9 73.437 4.7 C 72.237 3.5 70.337 2.8 67.737 2.8 Z M 68.637 10.1 C 68.237 12.9 66.037 12.9 64.037 12.9 L 62.837 12.9 L 63.637 7.7 C 63.637 7.4 63.937 7.2 64.237 7.2 L 64.737 7.2 C 66.137 7.2 67.437 7.2 68.137 8 C 68.637 8.4 68.737 9.1 68.637 10.1 Z"></path><path fill="#009cde" d="M 90.937 10 L 87.237 10 C 86.937 10 86.637 10.2 86.637 10.5 L 86.437 11.5 L 86.137 11.1 C 85.337 9.9 83.537 9.5 81.737 9.5 C 77.637 9.5 74.137 12.6 73.437 17 C 73.037 19.2 73.537 21.3 74.837 22.7 C 75.937 24 77.637 24.6 79.537 24.6 C 82.837 24.6 84.737 22.5 84.737 22.5 L 84.537 23.5 C 84.437 23.9 84.737 24.3 85.137 24.3 L 88.537 24.3 C 89.037 24.3 89.537 23.9 89.637 23.4 L 91.637 10.6 C 91.637 10.4 91.337 10 90.937 10 Z M 85.737 17.2 C 85.337 19.3 83.737 20.8 81.537 20.8 C 80.437 20.8 79.637 20.5 79.037 19.8 C 78.437 19.1 78.237 18.2 78.437 17.2 C 78.737 15.1 80.537 13.6 82.637 13.6 C 83.737 13.6 84.537 14 85.137 14.6 C 85.737 15.3 85.937 16.2 85.737 17.2 Z"></path><path fill="#009cde" d="M 95.337 3.3 L 92.137 23.6 C 92.037 24 92.337 24.3 92.737 24.3 L 95.937 24.3 C 96.437 24.3 96.937 23.9 97.037 23.4 L 100.237 3.5 C 100.337 3.1 100.037 2.8 99.637 2.8 L 96.037 2.8 C 95.637 2.8 95.437 3 95.337 3.3 Z"></path></svg>
                                    </button>
                                </div>
                                <style>
                                    @media (max-width: 768px) {
                                        .checkout-buttons button {
                                            width: 100%;
                                        }
                                    }
                                </style>
                                <p class="divider">OR</p>

                            </div>

                            <div class="checkout-container">

                                <div class="bg-light">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">


                                        {{-- <div class="accordion-item rounded-3 border-0 shadow mb-2">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button border-bottom collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                <img src="{{asset('paypal.png')}}" alt="Paypal" class="img-fluid" style="height: 30px">
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <div class="">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-252.3 356.1 163 80.9" class="zjrzY" style="height: 80px;">
                                                        <path fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"
                                                            d="M-108.9 404.1v30c0 1.1-.9 2-2 2H-231c-1.1 0-2-.9-2-2v-75c0-1.1.9-2 2-2h120.1c1.1 0 2 .9 2 2v37m-124.1-29h124.1">
                                                        </path>
                                                        <circle cx="-227.8" cy="361.9" r="1.8" fill="currentColor"></circle>
                                                        <circle cx="-222.2" cy="361.9" r="1.8" fill="currentColor"></circle>
                                                        <circle cx="-216.6" cy="361.9" r="1.8" fill="currentColor"></circle>
                                                        <path fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"
                                                            d="M-128.7 400.1H-92m-3.6-4.1 4 4.1-4 4.1"></path>
                                                        </svg>
                                                        <div class="">
                                                        <p class="">After
                                                            clicking "Pay with PayPal", you will be redirected to PayPal to complete your purchase securely.</p>
                                                        </div>
                                                    </div>
                                                    <div class="coupon_inner input-group-append mt-3">
                                                        <button type="submit" id="payPalBtn" class="btn btn-primary"><span>Make Order</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item rounded-3 border-0 shadow mb-2">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button border-bottom collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                                <img src="{{asset('paylater.png')}}" alt="Pay Later" class="img-fluid" style="height: 20px">
                                                    <b> <i>Pay Later</i> </b>
                                                </button>
                                            </h2>
                                            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">

                                                    <div class="">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-252.3 356.1 163 80.9" class="zjrzY" style="height: 80px;">
                                                        <path fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"
                                                            d="M-108.9 404.1v30c0 1.1-.9 2-2 2H-231c-1.1 0-2-.9-2-2v-75c0-1.1.9-2 2-2h120.1c1.1 0 2 .9 2 2v37m-124.1-29h124.1">
                                                        </path>
                                                        <circle cx="-227.8" cy="361.9" r="1.8" fill="currentColor"></circle>
                                                        <circle cx="-222.2" cy="361.9" r="1.8" fill="currentColor"></circle>
                                                        <circle cx="-216.6" cy="361.9" r="1.8" fill="currentColor"></circle>
                                                        <path fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"
                                                            d="M-128.7 400.1H-92m-3.6-4.1 4 4.1-4 4.1"></path>
                                                        </svg>
                                                        <div class="">
                                                        <p class="">After
                                                            clicking "Pay with PayPal", you will be redirected to PayPal to complete your purchase securely.</p>
                                                        </div>
                                                    </div>
                                                    <div class="coupon_inner input-group-append mt-3">
                                                        <button type="submit" id="payLaterBtn" class="btn btn-primary"><span>Make Order</span></button>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item rounded-3 border-0 mb-2 shadow">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button border-bottom collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                                    <img src="{{asset('stripe.png')}}" alt="GPay" class="img-fluid" style="height: 30px">
                                                </button>
                                            </h2>
                                            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <input type="text" id="card-holder-name" name="card-holder-name" class="form-control mb-2" placeholder=" Card Holder Name">
                                                    <div id="card-element-container" class="p-3 card-element-container">
                                                        <div id="card-element"></div>
                                                    </div>
                                                    <div class="coupon_inner input-group-append mt-3">
                                                        <button type="submit" id="stripePayBtn" class="btn btn-primary"><span>Make Order</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="accordion-item rounded-3 border-0 mb-2 shadow">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button border-bottom collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                                    <img src="{{asset('cashon.png')}}" alt="Cash on delivery" class="img-fluid" style="height: 30px">
                                                </button>
                                            </h2>
                                            <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    <div class="">
                                                        <img src="{{asset('cashon.png')}}" alt="Cash on delivery" class="img-fluid" style="height: 80px">
                                                    </div>
                                                    <div class="coupon_inner input-group-append mt-3">
                                                        <button type="submit" id="codBtn" class="btn btn-primary"><span>Make Order</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div id="stripePaypentDiv">
                                <div class="mb-3">
                                    <script src="https://js.stripe.com/v3/"></script> 
                                   <div id="card-element-container" style="display: none;">
                                       <div id="card-element"></div>
                                   </div>
                               </div>
                            </div>

                            

                            
                    </div>
                </aside>
                
                <div class="col-lg-1"></div>
            </div>
        </div>
    </div>
</div>

<style>
    #loader {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 99;
        display: none;
    }

    #loader .spinner-border {
        width: 5rem;
        height: 5rem;
        border-width: 0.5rem;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const options = document.querySelectorAll(".option");
        options.forEach(option => {
            option.addEventListener("click", function() {
                options.forEach(opt => opt.classList.remove("selected"));
                this.classList.add("selected");
                this.querySelector("input").checked = true;
            });
        });
    });
</script>
<script>
    function showSection(type, button) {
        
        const billingDetails = document.getElementById('billingDetails');
        const pickupDetails = document.getElementById('pickupDetails');
        const shippingMethodInput = document.getElementById('shippingMethod');

        if (type === 'ship') {
            billingDetails.style.display = 'block';
            pickupDetails.style.display = 'none';
            shippingMethodInput.value = '1';
        } else if (type === 'pickup') {
            billingDetails.style.display = 'none';
            pickupDetails.style.display = 'block';
            shippingMethodInput.value = '0';
        }

        const shipButton = document.getElementById('shipButton');
        const pickupButton = document.getElementById('pickupButton');
    }

    document.addEventListener('DOMContentLoaded', function() {
        showSection('pickup', document.getElementById('shipButton'));
    });
</script>

@endsection

@section('script')
<script>
      $("#diffAddress").hide();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

         const stripe = Stripe('pk_test_51N5D0QHyRsekXzKiScNvPKU4rCAVKTJOQm8VoSLk7Mm4AqPPsXwd6NDhbdZGyY4tkqWYBoDJyD0eHLFBqQBfLUBA00tj1hNg3q');
         const elements = stripe.elements();
         const cardElement = elements.create('card');
         cardElement.mount('#card-element');

        $('#placeOrderBtn, #paypalOrderBtn, #stripePayBtn, #payLaterBtn, #codBtn, #payPalBtn').click(async function() {

            if ($('#email').val().trim() === '') {
                var errorHtml = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                errorHtml += '<b>Email field is required.</b><br>';
                errorHtml += '</div>';
                $('#alertContainer').html(errorHtml);
                pagetop();
                return false;
            }
            if (!$('input[name="terms"]').is(':checked')) {
                var errorHtml = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                errorHtml += '<b>Please accept the Privacy Policy and Terms & Conditions.</b><br>';
                errorHtml += '</div>';
                $('#alertContainer').html(errorHtml);
                pagetop();
                return false;
            }

            let paymentMethod = null;
            let way = '';

            if ($(this).attr('id') === 'paypalOrderBtn') {
                paymentMethod = 'paypal';
                way = 'express';
            } else if ($(this).attr('id') === 'stripePayBtn') {
                paymentMethod = 'stripe';
            } else if ($(this).attr('id') === 'payLaterBtn') {
                paymentMethod = 'paypal';
            } else if ($(this).attr('id') === 'payPalBtn') {
                paymentMethod = 'paypal';
            } else if ($(this).attr('id') === 'codBtn') {
                paymentMethod = 'cashondelivery';
            }

            $('#loader').show();
            $('#placeOrderBtn').prop('disabled', true);
            $('#paypalOrderBtn').prop('disabled', true);
            $('#stripePayBtn').prop('disabled', true);
            $('#payLaterBtn').prop('disabled', true);
            $('#codBtn').prop('disabled', true);
            $('#payPalBtn').prop('disabled', true);

            var formData = {
                'name': $('#first_name').val(),
                'surname': $('#last_name').val(),
                'email': $('#email').val(),
                'phone': $('#phone').val(),
                'house_number': $('#house_number').val(),
                'street_name':  $('#street_name').val(),
                'town': $('#town').val(),
                'postcode': $('#postcode').val(),
                'billing_name': $('#billing_first_name').val(),
                'billing_surname': $('#billing_last_name').val(),
                'billing_email': $('#email').val(),
                'billing_phone': $('#billing_phone').val(),
                'billing_house_number': $('#billing_house_number').val(),
                'billing_street_name':  $('#billing_street_name').val(),
                'billing_town': $('#billing_town').val(),
                'billing_postcode': $('#billing_postcode').val(),
                'note': $('#note').val(),
                'delivery_charge': $('#shipping-charge-input').val(),
                'payment_method': paymentMethod,
                'way': way,
                'discount_amount': $('#discount-amount').val(),
                'order_summary': {!! json_encode($cart) !!},
                'coupon_id': $('#couponId').val(),
                'is_ship': $('#shippingMethod').val(),
                'offermail': $('#offermail').is(':checked') ? 1 : 0,
                'is_billing_same': $('#is_diff_address').val(),
                '_token': '{{ csrf_token() }}'
            };


            if (formData.payment_method === 'stripe') {
                try {
                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                        billing_details: {
                            name: formData.name,
                            email: formData.email
                        }
                    });

                    if (error) {
                        $('#loader').hide();

                        var errorHtml = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                        errorHtml += '<b>' + error.message + '</b><br>';
                        errorHtml += '</div>';
                        $('#alertContainer').html(errorHtml);
                        $('html, body').animate({ scrollTop: 100 }, 'smooth');
                        return;
                    }

                    formData.payment_method_id = paymentMethod.id;
                } catch (error) {
                    console.error(error);
                    $('#loader').hide();
                    return;
                }
            }

            console.log(formData);

            $.ajax({
                url: '{{ route('place.order') }}',
                type: 'POST',
                data: formData,
                success: function(response) {

                    if (response.pdf_url) {
                        window.location.href = response.pdf_url;
                    }

                    if (formData.payment_method === 'stripe') {
                        stripe.confirmCardPayment(response.client_secret, {
                            payment_method: formData.payment_method_id
                        }).then(function(result) {
                            if (result.error) {
                                var errorHtml = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                                errorHtml += '<b>' + result.error.message + '</b><br>';
                                errorHtml += '</div>';
                                $('#alertContainer').html(errorHtml);
                                $('html, body').animate({ scrollTop: 100 }, 'smooth');
                            } else {
                                if (result.paymentIntent.status === 'succeeded') {
                                    localStorage.removeItem('cart');
                                    localStorage.removeItem('wishlist');
                                    updateCartCount();
                                    window.location.href = response.redirectUrl;
                                }
                            }
                        }).finally(function() {
                            $('#loader').hide();
                        });
                    } else if (formData.payment_method === 'paypal') {
                        window.location.href = response.redirectUrl;
                    } else if(formData.payment_method === 'cashOnDelivery') {
                        if (response.success) {
                            localStorage.removeItem('cart');
                            localStorage.removeItem('wishlist');
                            updateCartCount();
                            window.location.href = response.redirectUrl;
                        }
                    } else {
                        localStorage.removeItem('cart');
                        localStorage.removeItem('wishlist');
                        updateCartCount();
                        window.location.href = response.redirectUrl;
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var firstError = Object.values(errors)[0][0];
                        var errorHtml = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                        errorHtml += '<b>' + firstError + '</b><br>';
                        errorHtml += '</div>';
                        $('#alertContainer').html(errorHtml);
                        $('html, body').animate({ scrollTop: 100 }, 'smooth');
                    } else {
                        console.error(xhr.responseText);
                    }
                },
                complete: function() {
                    $('#loader').hide();
                    $('#placeOrderBtn').prop('disabled', false);
                    $('#paypalOrderBtn').prop('disabled', false);
                    $('#stripePayBtn').prop('disabled', false);
                    $('#payLaterBtn').prop('disabled', false);
                    $('#payPalBtn').prop('disabled', false);
                    $('#codBtn').prop('disabled', false);
                }
            });

            function updateCartCount() {
                var cart = JSON.parse(localStorage.getItem('cart')) || [];
                var cartCount = cart.length;
                $('#cartCount').text(cartCount);
            }
        });
</script>


<script>
    $(document).ready(function() {
        function updateDiscount() {
            var discountType = $('#couponType').text().includes('Percentage') ? 'percentage' : 'fixed';
            var discountValue = parseFloat($('#couponValue').text()) || 0;
            var subtotal = parseFloat('{{ $total }}');
            var discount = 0;

            if (discountType === 'percentage') {
                discount = (discountValue / 100) * subtotal;
            } else {
                discount = discountValue;
            }

            $('#discount').text(`{{ $currency }}${discount.toFixed(2)}`);
            $('#discount-amount').val(discount.toFixed(2));

            if (discount > 0) {
                $('#discount-row').removeClass('d-none');
            } else {
                $('#discount-row').addClass('d-none');
            }
            return discount;
        }

        function updateVat() {
            var subtotal = parseFloat('{{ $total }}');
            var vatPercentage = parseFloat('{{ $vatPercent }}');
            var vatAmount = (vatPercentage / 100) * subtotal;

            $('#vat-charge').text(`{{ $currency }}${vatAmount.toFixed(2)}`);
            return vatAmount;
        }

        function updateTotal() {
            var subtotal = parseFloat('{{ $totalWithWarranty }}');
            var shippingCharge = parseFloat($('#shipping-charge-input').val());
            var discount = updateDiscount();
            var vatAmount = updateVat();
            var totalAmount = subtotal + shippingCharge - discount + vatAmount;

            $('#total-amount').text(`{{ $currency }}${totalAmount.toFixed(2)}`);
        }

        $('#coupon, input[name="discountType"]').change(function() {
            updateTotal();
        });

        // function updateShippingCharge() {
        //     var shippingCharge = parseFloat($('#shipping-charge-input').val());

        //     var currencySymbol = '{{ $currency }}';
        //     $('#shipping-charge').text(`${currencySymbol} ${shippingCharge.toFixed(2)}`);

        //     var subtotal = parseFloat('{{ $total }}');
        //     var totalAmount = subtotal + shippingCharge;
        //     $('#total-amount').text(`${currencySymbol} ${totalAmount.toFixed(2)}`);
        //     updateTotal();
        // }

        function updateCartCount() {
            var cart = JSON.parse(localStorage.getItem('cart')) || [];
            var cartCount = cart.length;
            $('#cartCount').text(cartCount);
        }

        // updateShippingCharge();
        updateTotal();

        $('#applyCoupon').click(function(e) {
            e.preventDefault();
            var couponName = $('#couponName').val();
            var guest_email = $('#email').val();
            var guest_phone = $('#phone').val();

            if (!guest_email) {
                toastr.error("Please enter your email before applying the coupon.", "Email Required");
                return;
            }

            if (!guest_phone) {
                toastr.error("Please enter your phone before applying the coupon.", "Phone Required");
                return;
            }

            $.ajax({
                url: '/check-coupon',
                type: 'GET',
                data: { guest_email: guest_email, guest_phone: guest_phone, coupon_name: couponName },
                success: function(response) {
                    if (response.success) {
                        $('#couponDetails').show();
                        $('#couponType').text(response.coupon_type === 1 ? 'Fixed Amount' : 'Percentage');
                        $('#couponValue').text(response.coupon_value);
                        $('#couponId').val(response.coupon_id);
                        updateTotal();
                        toastr.success("Valid Coupon", "Coupon applied successfully!", "success");
                    } else {
                        toastr.error(response.message, "Coupon Error");
                    }
                },
                error: function() {
                    toastr.error("Error", "Error applying coupon.", "error");
                }
            });
        });
    });
</script>
@endsection