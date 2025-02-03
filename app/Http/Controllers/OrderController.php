<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Stock;
use PDF;
use App\Models\CompanyDetails;
use App\Models\SpecialOfferDetails;
use App\Models\FlashSellDetails;
use App\Models\DeliveryMan;
use DataTables;
use App\Models\CancelledOrder;
use App\Models\OrderReturn;
use Illuminate\Support\Facades\Validator;
use Omnipay\Omnipay;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Log;
use Exception;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;
use App\Models\CouponUsage;
use App\Models\Coupon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Models\ContactEmail;
use App\Mail\OrderStatusChangedMail;
use App\Models\ProductWarranty;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'house_number' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'town' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20',
            'billing_name' => 'nullable|string|max:255',
            'billing_surname' => 'nullable|string|max:255',
            'billing_email' => 'nullable|email|max:255',
            'billing_phone' => 'nullable|string|max:20',
            'billing_house_number' => 'nullable|string|max:255',
            'billing_street_name' => 'nullable|string|max:255',
            'billing_town' => 'nullable|string|max:255',
            'billing_postcode' => 'nullable|string|max:20',
            'note' => 'nullable|string|max:255',
            'payment_method' => 'required',
            'order_summary.*.quantity' => 'required|numeric|min:1',
            'order_summary.*.size' => 'nullable|string|max:255',
            'order_summary.*.color' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Please enter your first name.',
            'surname.required' => 'Please enter your last name.',
            'email.required' => 'Please enter your email.',
            'phone.required' => 'Please enter your phone number.',
            'house_number.required' => 'Please enter your house number.',
            'street_name.required' => 'Please enter your street name.',
            'town.required' => 'Please enter your town.',
            'postcode.required' => 'Please enter your postcode.',
            'billing_name.required' => 'Please enter your billing first  name.',
            'billing_surname.required' => 'Please enter your billing last name.',
            'billing_email.required' => 'Please enter your billing email.',
            'billing_phone.required' => 'Please enter your phone billing number.',
            'billing_house_number.required' => 'Please enter your billing house number.',            
            'billing_street_name.required' => 'Please enter your billing street name.',
            'billing_town.required' => 'Please enter your billing town.',            
            'billing_postcode.required' => 'Please enter your billing postcode.',
        ]);

        if ($request->input('is_ship') == 1 && $request->input('way') !== "express") {
            $validator->sometimes(
                ['name', 'surname', 'email', 'phone', 'house_number', 'street_name', 'town', 'postcode'], 
                'required|string|max:255', 
                function ($input) {
                    return true;
                }
            );
        }

        if ($request->input('is_billing_same') == 0 && $request->input('way') !== "express") {
            $validator->sometimes(['billing_name', 'billing_surname', 'billing_email', 'billing_phone', 'billing_house_number', 'billing_street_name', 'billing_town', 'billing_postcode'], 'required|string|max:255', function ($input) {
                return true;
            });
        }

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $formData = $request->all();
        $pdfUrl = null;
        $subtotal = 0.00;
        $subtotalWithWarranty = 0.00;
        $discountAmount = 0.00;

        foreach ($formData['order_summary'] as $item) {
            $product = Product::findOrFail($item['productId']);
            $totalPrice = (float) $item['quantity'] * (float) $product->price;
            $totalPriceWithWarranty = $totalPrice;

            if (isset($item['offerId'])) {
                if ($item['offerId'] == 1) {
                    $specialOfferDetail = SpecialOfferDetails::where('product_id', $item['productId'])
                        ->where('status', 1)
                        ->first();
                    if ($specialOfferDetail) {
                        $totalPrice = (float) $item['quantity'] * (float) $specialOfferDetail->offer_price;
                    }
                } elseif ($item['offerId'] == 2) {
                    $flashSellDetail = FlashSellDetails::where('product_id', $item['productId'])
                        ->where('status', 1)
                        ->first();
                    if ($flashSellDetail) {
                        $totalPrice = (float) $item['quantity'] * (float) $flashSellDetail->flash_sell_price;
                    }
                }
            }

            if (isset($item['warrantyId'])) {
                $warrantyDetail = ProductWarranty::find($item['warrantyId']);
        
                if ($warrantyDetail) {
                    $warrantyPriceIncrease = ($product->price * $warrantyDetail->price_increase_percent) / 100;
                    $totalPriceWithWarranty += $warrantyPriceIncrease * $item['quantity'];
                }
            }

            $subtotal += $totalPrice;
            $subtotalWithWarranty += $totalPriceWithWarranty; 
        }

        $discountAmount = $formData['discount_amount'] ?? 0.00;

        $vat_percent = CompanyDetails::value('vat_percent');
        $vat_amount = ($subtotal * $vat_percent) / 100;

        $shippingAmount = $formData['delivery_charge'] ?? 0;
        $netAmount = $subtotalWithWarranty - $discountAmount + $vat_amount + $shippingAmount;

        if ($formData['payment_method'] === 'paypal') {
            return $this->initiatePayPalPayment($netAmount, $formData);
        }elseif ($formData['payment_method'] === 'stripe') {
            return $this->initiateStripePayment($netAmount, $formData, $subtotalWithWarranty, $discountAmount, $vat_amount, $shippingAmount, $subtotal);
        }else {
            DB::transaction(function () use ($formData, &$pdfUrl, $subtotal, $subtotalWithWarranty, $discountAmount) {
                $order = new Order();
                if (auth()->check()) {
                    $order->user_id = auth()->user()->id;
                }
                $order->invoice = random_int(100000, 999999);
                $order->purchase_date = now()->format('Y-m-d');
                $order->name = $formData['name'] ?? '';
                $order->surname = $formData['surname'] ?? '';
                $order->email = $formData['email'] ?? '';
                $order->phone = $formData['phone'] ?? '';
                $order->house_number = $formData['house_number'] ?? '';
                $order->street_name = $formData['street_name']?? '';
                $order->town = $formData['town'] ?? '';
                $order->postcode = $formData['postcode'] ?? '';
                $order->note = $formData['note'] ?? '';
                $order->payment_method = $formData['payment_method'];
                $order->shipping_amount = $formData['delivery_charge'] ?? 0;
                $order->is_ship = $formData['is_ship'];
                $order->status = 1;
                $order->admin_notify = 1;
                $order->order_type = 0;
                $order->subtotal_amount = $subtotal;
                $order->discount_amount = $discountAmount;
                $vat_percent = CompanyDetails::value('vat_percent');
                $vat_amount = ($subtotal * $vat_percent) / 100;
                $order->vat_percent = $vat_percent;
                $order->vat_amount = $vat_amount;
                $order->net_amount = $subtotalWithWarranty + $vat_amount + $formData['delivery_charge'] - $discountAmount;
    
                if (auth()->check()) {
                    $order->created_by = auth()->user()->id;
                }
    
                $order->save();

                $encoded_order_id = base64_encode($order->id);
                $pdfUrl = route('generate-pdf', ['encoded_order_id' => $encoded_order_id]);

                // $this->sendOrderEmail($order, $pdfUrl);

                if ($order->discount_amount > 0 && isset($formData['coupon_id'])) {
                    $couponUsage = new CouponUsage();
                    $couponUsage->coupon_id = $formData['coupon_id'];
                    $couponUsage->order_id = $order->id;
                
                    if (auth()->check()) {
                        $couponUsage->user_id = auth()->user()->id;
                    } else {
                        $couponUsage->guest_name = $formData['name'] ?? null;
                        $couponUsage->guest_email = $formData['email'] ?? null;
                        $couponUsage->guest_phone = $formData['phone'] ?? null;
                    }
                
                    $couponUsage->save();
    
                    Coupon::where('id', $formData['coupon_id'])->increment('times_used', 1);
                }
    
                $encoded_order_id = base64_encode($order->id);
                $pdfUrl = route('generate-pdf', ['encoded_order_id' => $encoded_order_id]);
    
                if (isset($formData['order_summary']) && is_array($formData['order_summary'])) {
                    foreach ($formData['order_summary'] as $item) {
                        $product = Product::findOrFail($item['productId']);
                    
                        $totalPrice = (float) $item['quantity'] * (float) $product->price;
                        $pricePerUnit = (float) $product->price;
    
                        if (isset($item['offerId'])) {
                            if ($item['offerId'] == 1) {
                                $specialOfferDetail = SpecialOfferDetails::where('product_id', $item['productId'])
                                    ->where('status', 1)
                                    ->first();
                                if ($specialOfferDetail) {
                                    $totalPrice = (float) $item['quantity'] * (float) $specialOfferDetail->offer_price;
                                    $pricePerUnit = (float) $specialOfferDetail->offer_price;
                                }
                            } elseif ($item['offerId'] == 2) {
                                $flashSellDetail = FlashSellDetails::where('product_id', $item['productId'])
                                    ->where('status', 1)
                                    ->first();
                                if ($flashSellDetail) {
                                    $totalPrice = (float) $item['quantity'] * (float) $flashSellDetail->flash_sell_price;
                                    $pricePerUnit = (float) $flashSellDetail->flash_sell_price;
                                }
                            }
                        }
                    
                        if (isset($item['warrantyId'])) {
                            $warrantyDetail = ProductWarranty::find($item['warrantyId']);
                            if ($warrantyDetail) {
                                $priceIncrease = (float) $warrantyDetail->price_increase;
                                $totalPrice += $priceIncrease * (float) $item['quantity'];
                                $pricePerUnit += $priceIncrease;
                            }
                        }
                    
                        $orderDetails = new OrderDetails();
                        $orderDetails->order_id = $order->id;
                        $orderDetails->product_id = $item['productId'];
                        $orderDetails->quantity = $item['quantity'];
                        $orderDetails->size = $item['size'] ?? null;
                        $orderDetails->color = $item['color'] ?? null;
                        $orderDetails->warranty_id = $item['warrantyId'] ?? null;
                        $orderDetails->price_per_unit = $pricePerUnit;
                        $orderDetails->total_price = $totalPrice;
                        if (auth()->check()) {
                            $orderDetails->created_by = auth()->user()->id;
                        }
                        $orderDetails->save();

                        $stock = Stock::where('product_id', $item['productId'])
                            // ->where('size', $item['size'])
                            // ->where('color', $item['color'])
                            ->first();

                        if ($stock) {
                            $stock->quantity -= $item['quantity'];
                            $stock->save();
                        }
                    }
                }
            });
        }

        return response()->json([
            'success' => true,
            'redirectUrl' => route('order.success', ['pdfUrl' => $pdfUrl])
        ]);
    }

    private function initiateStripePayment($netAmount, $formData)
    {
        $totalamt = $netAmount;
        // $stripecommission = $totalamt * 1.5 / 100;
        // $fixedFee = 0.20;
        // $amt = $netAmount;

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalamt * 100,
                'currency' => 'GBP',
                'payment_method' =>  $formData['payment_method_id'],
                'description' => 'Order payment',
                'confirm' => false,
                'confirmation_method' => 'automatic',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $pdfUrl = null;
        $subtotal = 0.00;

        foreach ($formData['order_summary'] as $item) {
            $product = Product::findOrFail($item['productId']);
            $totalPrice = (float) $item['quantity'] * (float) $product->price;

            if (isset($item['offerId'])) {
                if ($item['offerId'] == 1) {
                    $specialOfferDetail = SpecialOfferDetails::where('product_id', $item['productId'])
                        ->where('status', 1)
                        ->first();
                    if ($specialOfferDetail) {
                        $totalPrice = (float) $item['quantity'] * (float) $specialOfferDetail->offer_price;
                    }
                } elseif ($item['offerId'] == 2) {
                    $flashSellDetail = FlashSellDetails::where('product_id', $item['productId'])
                        ->where('status', 1)
                        ->first();
                    if ($flashSellDetail) {
                        $totalPrice = (float) $item['quantity'] * (float) $flashSellDetail->flash_sell_price;
                    }
                }
            }

            $subtotal += $totalPrice;
        }

        $discountAmount = (float) ($formData['discount_amount'] ?? 0.00);

        $netAmount = $subtotal - $discountAmount;

        DB::transaction(function () use ($formData, &$pdfUrl, $subtotal, $discountAmount) {
            $order = new Order();
            if (auth()->check()) {
                $order->user_id = auth()->user()->id;
            }
            $order->invoice = random_int(100000, 999999);
            $order->purchase_date = now()->format('Y-m-d');
            $order->name = $formData['name'];
            $order->surname = $formData['surname'];
            $order->email = $formData['email'];
            $order->phone = $formData['phone'];
            $order->house_number = $formData['house_number'];
            $order->street_name = $formData['street_name'];
            $order->town = $formData['town'];
            $order->postcode = $formData['postcode'];
            $order->note = $formData['note'];
            $order->payment_method = $formData['payment_method'];
            $order->shipping_amount = $formData['delivery_charge'] ?? 0;
            $order->status = 1;
            $order->admin_notify = 1;
            $order->order_type = 0;
            $order->subtotal_amount = $subtotal;
            $order->discount_amount = $discountAmount;
            $vat_percent = CompanyDetails::value('vat_percent');
            $vat_amount = ($subtotal * $vat_percent) / 100;
            $order->vat_percent = $vat_percent;
            $order->vat_amount = $vat_amount;
            $order->net_amount = $subtotal + $order->vat_amount + $order->shipping_amount - $discountAmount;

            if (auth()->check()) {
                $order->created_by = auth()->user()->id;
            }

            $order->save();

            $encoded_order_id = base64_encode($order->id);
            $pdfUrl = route('generate-pdf', ['encoded_order_id' => $encoded_order_id]);

            // $this->sendOrderEmail($order, $pdfUrl);

            if ($order->discount_amount > 0 && isset($formData['coupon_id'])) {
                $couponUsage = new CouponUsage();
                $couponUsage->coupon_id = $formData['coupon_id'];
                $couponUsage->order_id = $order->id;
            
                if (auth()->check()) {
                    $couponUsage->user_id = auth()->user()->id;
                } else {
                    $couponUsage->guest_name = $formData['name'] ?? null;
                    $couponUsage->guest_email = $formData['email'] ?? null;
                    $couponUsage->guest_phone = $formData['phone'] ?? null;
                }
            
                $couponUsage->save();

                Coupon::where('id', $formData['coupon_id'])->increment('times_used', 1);
            }

            if (isset($formData['order_summary']) && is_array($formData['order_summary'])) {
                foreach ($formData['order_summary'] as $item) {
                    $product = Product::findOrFail($item['productId']);

                    $totalPrice = (float) $item['quantity'] * (float) $product->price;
                    if (isset($item['offerId'])) {
                        if ($item['offerId'] == 1) {
                            $specialOfferDetail = SpecialOfferDetails::where('product_id', $item['productId'])
                                ->where('status', 1)
                                ->first();
                            if ($specialOfferDetail) {
                                $totalPrice = (float) $item['quantity'] * (float) $specialOfferDetail->offer_price;
                            }
                        } elseif ($item['offerId'] == 2) {
                            $flashSellDetail = FlashSellDetails::where('product_id', $item['productId'])
                                ->where('status', 1)
                                ->first();
                            if ($flashSellDetail) {
                                $totalPrice = (float) $item['quantity'] * (float) $flashSellDetail->flash_sell_price;
                            }
                        }
                    }

                    $orderDetails = new OrderDetails();
                    $orderDetails->order_id = $order->id;
                    $orderDetails->product_id = $item['productId'];
                    $orderDetails->quantity = $item['quantity'];
                    $orderDetails->size = $item['size'] ?? null;
                    $orderDetails->color = $item['color'] ?? null;
                    $orderDetails->price_per_unit = (float) $item['price'] ?? null;
                    $orderDetails->total_price = $totalPrice;

                    if (auth()->check()) {
                        $orderDetails->created_by = auth()->user()->id;
                    }
                    $orderDetails->save();

                    $stock = Stock::where('product_id', $item['productId'])
                        ->where('size', $item['size'])
                        ->where('color', $item['color'])
                        ->first();

                    if ($stock) {
                        $stock->quantity -= $item['quantity'];
                        $stock->save();
                    }
                }
            }
        });

        return response()->json([
            'success' => true,
            'client_secret' => $paymentIntent->client_secret,
            'redirectUrl' => route('order.success', ['pdfUrl' => $pdfUrl])
        ]);
    }

    protected function getPayPalCredentials()
    {
        return PaymentGateway::where('name', 'paypal')
            ->where('status', 1)
            ->first();
    }

    protected function initiatePayPalPayment($netAmount, $formData)
    {

        $payPalCredentials = $this->getPayPalCredentials();

        if (!$payPalCredentials) {
            return response()->json(['error' => 'PayPal credentials not found'], 404);
        }

        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId($payPalCredentials->clientid);
        $gateway->setSecret($payPalCredentials->secretid);
        $gateway->setTestMode($payPalCredentials->mode);

        try {
            $response = $gateway->purchase([
                'amount' => number_format($netAmount, 2, '.', ''),
                'currency' => 'GBP',
                'returnUrl' => route('payment.success'),
                'cancelUrl' => route('payment.cancel')
            ])->send();

            if ($response->isRedirect()) {
                session()->put('order_data', $formData);
                session()->put('order_net_amount', $netAmount);
                return response()->json(['redirectUrl' => $response->getRedirectUrl()]);
            } else {
                return response()->json(['error' => $response->getMessage()], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $orderData = session('order_data');
        $netAmount = session('order_net_amount');

        $subtotal = 0.00;
        $neworderid = null;
        $neworder = null;
        
        if (isset($orderData['order_summary']) && is_array($orderData['order_summary'])) {
            foreach ($orderData['order_summary'] as $item) {
                $product = Product::findOrFail($item['productId']);
                $totalPrice = (float) $item['quantity'] * (float) $product->price;
                if (isset($item['offerId'])) {
                    if ($item['offerId'] == 1) {
                        $specialOfferDetail = SpecialOfferDetails::where('product_id', $item['productId'])
                            ->where('status', 1)
                            ->first();
                        if ($specialOfferDetail) {
                            $totalPrice = (float) $item['quantity'] * (float) $specialOfferDetail->offer_price;
                        }
                    } elseif ($item['offerId'] == 2) {
                        $flashSellDetail = FlashSellDetails::where('product_id', $item['productId'])
                            ->where('status', 1)
                            ->first();
                        if ($flashSellDetail) {
                            $totalPrice = (float) $item['quantity'] * (float) $flashSellDetail->flash_sell_price;
                        }
                    }
                }
                $subtotal += $totalPrice;
            }
        }

        $pdfUrl = null;
        $order = new Order();

        DB::transaction(function () use ($orderData, $netAmount,$order, $subtotal, &$pdfUrl) {
            
            if (auth()->check()) {
                $order->user_id = auth()->user()->id;
            }
            $order->invoice = random_int(100000, 999999);
            $order->purchase_date = now()->format('Y-m-d');
            $order->name = $orderData['name'];
            $order->surname = $orderData['surname'];
            $order->email = $orderData['email'];
            $order->phone = $orderData['phone'];
            $order->house_number = $orderData['house_number'];
            $order->street_name = $orderData['street_name'];
            $order->town = $orderData['town'];
            $order->postcode = $orderData['postcode'];
            $order->note = $orderData['note'];
            $order->payment_method = 'paypal';
            $order->shipping_amount = $orderData['delivery_charge'] ?? 0;
            $order->status = 1;
            $order->admin_notify = 1;
            $order->order_type = 0;
            $order->subtotal_amount = $subtotal;
            $order->discount_amount = $orderData['discount_amount'];
            $vat_percent = CompanyDetails::value('vat_percent');
            $vat_amount = ($subtotal * $vat_percent) / 100;
            $order->vat_percent = $vat_percent;
            $order->vat_amount = $vat_amount;
            $order->net_amount = $subtotal + $order->vat_amount + $order->shipping_amount - $orderData['discount_amount'];;

            if (auth()->check()) {
                $order->created_by = auth()->user()->id;
            }

            $order->save();

            $encoded_order_id = base64_encode($order->id);
            $pdfUrl = route('generate-pdf', ['encoded_order_id' => $encoded_order_id]);

            // $this->sendOrderEmail($order, $pdfUrl);

            if ($order->discount_amount > 0 && isset($formData['coupon_id'])) {
                $couponUsage = new CouponUsage();
                $couponUsage->coupon_id = $formData['coupon_id'];
                $couponUsage->order_id = $order->id;
            
                if (auth()->check()) {
                    $couponUsage->user_id = auth()->user()->id;
                } else {
                    $couponUsage->guest_name = $formData['name'] ?? null;
                    $couponUsage->guest_email = $formData['email'] ?? null;
                    $couponUsage->guest_phone = $formData['phone'] ?? null;
                }
            
                $couponUsage->save();

                Coupon::where('id', $formData['coupon_id'])->increment('times_used', 1);
            }

            $encoded_order_id = base64_encode($order->id);
            $pdfUrl = route('generate-pdf', ['encoded_order_id' => $encoded_order_id]);

            if (isset($orderData['order_summary']) && is_array($orderData['order_summary'])) {
                foreach ($orderData['order_summary'] as $item) {
                    $product = Product::findOrFail($item['productId']);
                    $totalPrice = (float) $item['quantity'] * (float) $product->price;
                    if (isset($item['offerId'])) {
                        if ($item['offerId'] == 1) {
                            $specialOfferDetail = SpecialOfferDetails::where('product_id', $item['productId'])
                                ->where('status', 1)
                                ->first();
                            if ($specialOfferDetail) {
                                $totalPrice = (float) $item['quantity'] * (float) $specialOfferDetail->offer_price;
                            }
                        } elseif ($item['offerId'] == 2) {
                            $flashSellDetail = FlashSellDetails::where('product_id', $item['productId'])
                                ->where('status', 1)
                                ->first();
                            if ($flashSellDetail) {
                                $totalPrice = (float) $item['quantity'] * (float) $flashSellDetail->flash_sell_price;
                            }
                        }
                    }

                    $orderDetails = new OrderDetails();
                    $orderDetails->order_id = $order->id;
                    $orderDetails->product_id = $item['productId'];
                    $orderDetails->quantity = $item['quantity'];
                    $orderDetails->size = $item['size']?? null;
                    $orderDetails->color = $item['color']?? null;
                    $orderDetails->price_per_unit = (float) $item['price']?? null;
                    $orderDetails->total_price = $totalPrice;
                    if (auth()->check()) {
                        $orderDetails->created_by = auth()->user()->id;
                    }
                    $orderDetails->save();

                    $stock = Stock::where('product_id', $item['productId'])
                        ->where('size', $item['size'])
                        ->where('color', $item['color'])
                        ->first();

                    if ($stock) {
                        $stock->quantity -= $item['quantity'];
                        $stock->save();
                    }
                }
            }

            session()->forget('order_data');
            session()->forget('order_net_amount');

            $neworderid = $order->id;
            $neworder = $order;
            return view('frontend.order.success', compact('pdfUrl','neworderid','neworder'));


        });
        $order->save();
        
        $orderDetails = Order::where('id', $order->id)->first();


        session()->forget('order_data');
        session()->forget('order_net_amount');

        return view('frontend.order.success', compact('pdfUrl','neworderid','orderDetails'));
    }

    public function paymentCancel()
    {
        return view('frontend.order.cancel');
    }

    protected function sendOrderEmail($order, $pdfUrl)
    {
        Mail::to($order->email)->send(new OrderConfirmation($order, $pdfUrl));

        $contactEmails = ContactEmail::where('status', 1)->pluck('email');
        foreach ($contactEmails as $email) {
            Mail::to($email)->send(new OrderConfirmation($order, $pdfUrl));
        }
    }

    public function orderSuccess(Request $request)
    {
        $decodedId = base64_decode($request->order_id);
        $orderDetails = Order::findOrFail($decodedId);
    
        $pdfUrl = route('generate-pdf', ['encoded_order_id' => base64_encode($orderDetails->id)]);
    
        return view('frontend.order.success', compact('pdfUrl', 'orderDetails'));
    }

    public function generatePDF($encoded_order_id)
    {
        if (!$encoded_order_id) {
            return redirect('/');
        }

        $order_id = base64_decode($encoded_order_id);
        $order = Order::with('orderDetails')->findOrFail($order_id);

        $data = [
            'order' => $order,
            'currency' => CompanyDetails::value('currency'),
        ];

        $pdf = PDF::loadView('frontend.order_pdf', $data);

        return $pdf->stream('order_' . $order->id . '.pdf');
    }

    public function getOrders()
    {
        $orders = Order::where('user_id', auth()->user()->id)
                ->orderBy('id', 'desc')
                ->get();
        return view('user.orders', compact('orders'));
    }

    public function allOrder(Request $request)
    {
        if ($request->ajax()) {
            
        return DataTables::of(Order::with('user')
            ->where('order_type',0)
            ->orderBy('id', 'desc'))
            ->addColumn('action', function($order) {
                return '
                    <a href="'.route('admin.orders.details', ['orderId' => $order->id]).'" class="btn btn-primary">
                        <i class="fas fa-info-circle"></i> Details
                    </a>
                    <a href="'.route('admin.orders.edit', ['orderId' => $order->id]).'" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                ';
            })
            ->editColumn('subtotal_amount', function ($order) {
                return number_format($order->subtotal_amount, 2);
            })
            ->editColumn('shipping_amount', function ($order) {
                return number_format($order->shipping_amount, 2);
            })
            ->editColumn('discount_amount', function ($order) {
                return number_format($order->discount_amount, 2);
            })
            ->editColumn('net_amount', function ($order) {
                return number_format($order->net_amount, 2);
            })
            ->editColumn('status', function ($order) {
                $statusLabels = [
                    1 => 'Pending',
                    2 => 'Processing',
                    3 => 'Packed',
                    4 => 'Shipped',
                    5 => 'Delivered',
                    6 => 'Returned',
                    7 => 'Cancelled'
                ];
                return isset($statusLabels[$order->status]) ? $statusLabels[$order->status] : 'Unknown';
            })
            ->editColumn('payment_method', function ($order) {
                $paymentMethods = [
                    'cashOnDelivery' => 'Cash On Delivery',
                    'stripe' => 'Stripe',
                    'paypal' => 'PayPal',
                ];
                return isset($paymentMethods[$order->payment_method]) ? $paymentMethods[$order->payment_method] : $order->payment_method;
            })
            ->editColumn('purchase_date', function ($order) {
                return Carbon::parse($order->purchase_date)->format('d-m-Y');
            })
            ->addColumn('contact_info', function ($order) {
                return $order->name . '<br>' . $order->email . '<br>' . $order->phone;
            })
            ->rawColumns(['action', 'contact_info'])
            ->make(true);
        }

        return view('admin.orders.all');
    }

    public function getallorderbycoupon(Request $request, $couponId)
    {
        if ($request->ajax()) {
          
            $couponUsages = CouponUsage::where('coupon_id', $couponId)
            ->pluck('order_id'); 

            $orders = Order::with('user')
            ->where('order_type',0)
            ->whereIn('id', $couponUsages)
            ->orderBy('id', 'desc')
            ->get();

        return DataTables::of($orders)
            ->addColumn('action', function($order){
                return '<a href="'.route('admin.orders.details', ['orderId' => $order->id]).'" class="btn btn-primary">Details</a>';
            })
            ->editColumn('subtotal_amount', function ($order) {
                return number_format($order->subtotal_amount, 2);
            })
            ->editColumn('shipping_amount', function ($order) {
                return number_format($order->shipping_amount, 2);
            })
            ->editColumn('discount_amount', function ($order) {
                return number_format($order->discount_amount, 2);
            })
            ->editColumn('net_amount', function ($order) {
                return number_format($order->net_amount, 2);
            })
            ->editColumn('status', function ($order) {
                $statusLabels = [
                    1 => 'Pending',
                    2 => 'Processing',
                    3 => 'Packed',
                    4 => 'Shipped',
                    5 => 'Delivered',
                    6 => 'Returned',
                    7 => 'Cancelled'
                ];
                return isset($statusLabels[$order->status]) ? $statusLabels[$order->status] : 'Unknown';
            })
            ->editColumn('payment_method', function ($order) {
                $paymentMethods = [
                    'cashOnDelivery' => 'Cash On Delivery',
                    'stripe' => 'Stripe',
                    'paypal' => 'PayPal',
                ];
                return isset($paymentMethods[$order->payment_method]) ? $paymentMethods[$order->payment_method] : $order->payment_method;
            })
            ->editColumn('purchase_date', function ($order) {
                return Carbon::parse($order->purchase_date)->format('d-m-Y');
            })
            ->addColumn('contact_info', function ($order) {
                return $order->name . '<br>' . $order->email . '<br>' . $order->phone;
            })
            ->rawColumns(['action', 'contact_info'])
            ->make(true);
        }

        return view('admin.orders.coupon', compact('couponId'));
    }

    public function pendingOrders()
    {
        $orders = Order::with('user')
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function processingOrders()
    {
        $orders = Order::with('user')
                ->where('status', 2)
                ->orderBy('id', 'desc')
                ->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function packedOrders()
    {
        $orders = Order::with('user')
                ->where('status', 3)
                ->orderBy('id', 'desc')
                ->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function shippedOrders()
    {
        $orders = Order::with('user')
                ->where('status', 4)
                ->orderBy('id', 'desc')
                ->get();
         $deliveryMen = DeliveryMan::orderBy('id', 'desc')
                ->get(); 
        return view('admin.orders.index', compact('orders', 'deliveryMen'));
    }
    public function deliveredOrders()
    {
        $orders = Order::with('user')
                ->where('status', 5)
                ->orderBy('id', 'desc')
                ->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function returnedOrders()
    {
        $orders = Order::with(['user', 'orderReturns.product'])
                    ->where('status', 6)
                    ->orderBy('id', 'desc')
                    ->get();

        return view('admin.orders.returned', compact('orders'));
    }
    public function cancelledOrders()
    {
        $orders = Order::with('user', 'cancelledOrder')
                ->where('status', 7)
                ->orderBy('id', 'desc')
                ->get();
        return view('admin.orders.cancelled', compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->status = $request->status;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Order status updated successfully!']);
        }

        $emailToSend = $order->email ?? $order->user->email;

        if ($emailToSend) {
            Mail::to($emailToSend)->send(new OrderStatusChangedMail($order));
        }

        $contactEmails = ContactEmail::where('status', 1)->pluck('email');

        foreach ($contactEmails as $email) {
            Mail::to($email)->send(new OrderStatusChangedMail($order));
        }

        return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
    }

    public function updateDeliveryMan(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_man_id' => 'required|exists:delivery_men,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $deliveryMan = DeliveryMan::findOrFail($request->delivery_man_id);
        $order->delivery_man_id = $deliveryMan->id;
        $order->save();
        return response()->json(['success' => true], 200);
    }

    public function showOrder($orderId)
    {
        $order = Order::with(['user', 'orderDetails.product'])
            ->where('id', $orderId)
            ->firstOrFail();
        return view('admin.orders.details', compact('order'));
    }

    public function markAsNotified(Request $request)
    {
        $order = Order::find($request->order_id);

        if ($order) {
            $order->admin_notify = 0;
            $order->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function showOrderUser($orderId)
    {
        $order = Order::with(['user', 'orderDetails.product'])
            ->where('id', $orderId)
            ->firstOrFail();

            if (Auth::user()->id == $order->user_id) {
                return view('user.order_details', compact('order'));
            } else {
                return redirect()->route('orders.index');
            }
            
    }

    public function cancel(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (in_array($order->status, [4, 5, 6, 7])) {
            return response()->json(['error' => 'Order cannot be cancelled.'], 400);
        }

        $order->status = 7;
        $order->save();

        $orderDetails = OrderDetails::where('order_id', $order->id)->get();

        foreach ($orderDetails as $detail) {
            $stock = Stock::where('product_id', $detail->product_id)
                        ->where('color', $detail->color)
                        ->first();

            if ($stock) {
                $stock->quantity += $detail->quantity;
                $stock->save();
            }
        }

        CancelledOrder::create([
            'order_id' => $order->id,
            'reason' => $request->input('reason'),
            'cancelled_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function getOrderDetailsModal(Request $request)
    {
        $orderId = $request->get('order_id');
        $order = Order::with('orderDetails.product')->findOrFail($orderId);
        
        return response()->json([
            'order' => $order,
            'orderDetails' => $order->orderDetails
        ]);
    }

    public function returnStore(Request $request)
    {
        $data = $request->all();

        $order_id = $data['order_id'];

        $order = Order::find($order_id);
        $order->status = 6;
        $order->save();

        $return_items = $data['return_items'];

        foreach ($return_items as $item) {
            $orderReturn = new OrderReturn();
            $orderReturn->product_id = $item['product_id'];
            $orderReturn->order_id = $order_id;
            $orderReturn->quantity = $item['return_quantity'];
            $orderReturn->new_quantity = $item['return_quantity'];
            $orderReturn->reason = $item['return_reason'] ?? '';
            $orderReturn->returned_by = auth()->user()->id;
            $orderReturn->save();
        }

        return response()->json(['message' => 'Order return submitted successfully'], 200);
    }

}
