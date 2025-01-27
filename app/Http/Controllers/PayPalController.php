<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayPalController extends Controller
{
    public function paypalSuccess(Request $request)
    {
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $gateway->setClientSecret(env('PAYPAL_CLIENT_SECRET'));
        $gateway->setTestMode(true);

        $response = $gateway->completePurchase([
            'payer_id'             => $request->get('PayerID'),
            'transactionReference' => $request->get('paymentId'),
        ])->send();

        if ($response->isSuccessful()) {
            $paymentDetails = $response->getData();

            DB::transaction(function () use ($paymentDetails) {
                // Fetch the order using paymentDetails or session data
                // Update the order status
                // Save payment details to the database

                // Example:
                // $order = Order::find(session('order_id'));
                // $order->status = 'paid';
                // $order->payment_details = json_encode($paymentDetails);
                // $order->save();
            });

            return redirect()->route('order.success')->with('success', 'Payment successful');
        } else {
            return redirect()->route('order.failed')->with('error', 'Payment failed');
        }
    }

    public function paypalCancel()
    {
        // Handle payment cancellation
        return redirect()->route('checkout')->with('error', 'Payment was cancelled');
    }

}
