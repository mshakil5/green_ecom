<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Stock;
use App\Models\CompanyDetails;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Models\ContactEmail;

class InHouseSellController extends Controller
{
    public function inHouseSell()
    {
        $customers = User::where('is_type', '0')->orderby('id','DESC')->get();
        $products = Product::orderby('id','DESC')->get();
        return view('admin.in_house_sell.create', compact('customers', 'products'));
    }

    public function inHouseSellStore(Request $request)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'ref' => 'nullable|string',
            'remarks' => 'nullable|string',
            'discount' => 'nullable',
            'products' => 'required|json',
        ]);

        $products = json_decode($validated['products'], true);

        $itemTotalAmount = array_reduce($products, function ($carry, $product) {
            return $carry + $product['total_price'];
        }, 0);

        $netAmount = $itemTotalAmount - $validated['discount'];

        $order = new Order();
        $order->invoice = random_int(100000, 999999);
        $order->purchase_date = $validated['purchase_date'];
        $order->user_id = $validated['user_id'];
        $order->payment_method = $validated['payment_method'];
        $order->ref = $validated['ref'];
        $order->remarks = $validated['remarks'];
        $order->discount_amount = $validated['discount'];
        $order->net_amount = $netAmount;
        $order->vat_amount = $request->vat;
        $order->vat_percent = $request->vat_percent;
        $order->subtotal_amount = $itemTotalAmount;
        $order->order_type = 1;
        $order->status = 1;
        $order->save();

        foreach ($products as $product) {
            $orderDetail = new OrderDetails();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $product['product_id'];
            $orderDetail->quantity = $product['quantity'];
            // $orderDetail->size = $product['product_size'];
            // $orderDetail->color = $product['product_color'];
            $orderDetail->price_per_unit = $product['unit_price'];
            $orderDetail->total_price = $product['total_price'];
            $orderDetail->status = 1;
            $orderDetail->save();

            $stock = Stock::where('product_id', $product['product_id'])
                // ->where('size', $product['product_size'])
                // ->where('color', $product['product_color'])
                ->first();

            if ($stock) {
                $stock->quantity -= $product['quantity'];
                $stock->save();
            }
        }

        $encoded_order_id = base64_encode($order->id);
        $pdfUrl = route('in-house-sell.generate-pdf', ['encoded_order_id' => $encoded_order_id]);

        // Mail::to($order->user->email)->send(new OrderConfirmation($order, $pdfUrl));

        $contactEmails = ContactEmail::where('status', 1)->pluck('email');

        foreach ($contactEmails as $email) {
            // Mail::to($email)->send(new OrderConfirmation($order, $pdfUrl));
        }

        return response()->json([
            'pdf_url' => $pdfUrl,
            'message' => 'Order placed successfully'
        ], 200);

        return response()->json(['message' => 'Order created successfully', 'order_id' => $order->id], 201);
    }

    public function editOrder($orderId)
    {
        $order = Order::with('orderDetails.product')->findOrFail($orderId);
        $customers = User::where('is_type', '0')->orderby('id','DESC')->get();
        $products = Product::orderby('id','DESC')->get();
        
        return view('admin.in_house_sell.edit', compact('order', 'customers', 'products'));
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'ref' => 'nullable|string',
            'remarks' => 'nullable|string',
            'discount' => 'nullable',
            'products' => 'required|json',
            'id' => 'required|exists:orders,id',
        ]);

        $orderId = $validated['id'];
        $order = Order::find($orderId);
        $previousOrderType = $order->order_type;

        $products = json_decode($validated['products'], true);

        $itemTotalAmount = array_reduce($products, function ($carry, $product) {
            return $carry + $product['total_price'];
        }, 0);

        $netAmount = $itemTotalAmount - $validated['discount'];

        $order->purchase_date = $validated['purchase_date'];
        $order->user_id = $validated['user_id'];
        $order->payment_method = $validated['payment_method'];
        $order->ref = $validated['ref'];
        $order->remarks = $validated['remarks'];
        $order->discount_amount = $validated['discount'];
        $order->net_amount = $netAmount;
        $order->vat_amount = $request->vat;
        $order->vat_percent = $request->vat_percent;
        $order->subtotal_amount = $itemTotalAmount;
        if ($order->order_type != 0) {
            $order->order_type = 1;
            $order->save();
        }
        $order->save();

        $existingOrderDetails = $order->orderDetails()->get();

        $existingOrderDetailIds = $existingOrderDetails->pluck('id')->toArray();

        $updatedProductIds = [];

        foreach ($products as $product) {
            $orderDetailId = $product['order_detail_id'] ?? null;
            $updatedProductIds[] = $product['product_id']; 

            if ($orderDetailId) {
                $orderDetail = OrderDetails::find($orderDetailId);
                if ($orderDetail) {
                    if ($previousOrderType == 2) {
                        $stock = Stock::where('product_id', $product['product_id'])->first();
                        if ($stock) {
                            $stock->quantity -= $product['quantity'];
                            $stock->save();
                        }
                    } else {
                        $quantityDifference = $product['quantity'] - $orderDetail->quantity;
                        $stock = Stock::where('product_id', $product['product_id'])->first();
                        if ($stock) {
                            $stock->quantity -= $quantityDifference;
                            $stock->save();
                        }
                    }

                    $orderDetail->quantity = $product['quantity'];
                    $orderDetail->price_per_unit = $product['unit_price'];
                    $orderDetail->total_price = $product['total_price'];
                    $orderDetail->save();
                }
            } else {
                $orderDetail = new OrderDetails();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product['product_id'];
                $orderDetail->quantity = $product['quantity'];
                $orderDetail->price_per_unit = $product['unit_price'];
                $orderDetail->total_price = $product['total_price'];
                $orderDetail->status = 1;
                $orderDetail->save();

                $stock = Stock::where('product_id', $product['product_id'])->first();
                if ($stock) {
                    $stock->quantity -= $product['quantity'];
                    $stock->save();
                }
            }
        }

        foreach ($existingOrderDetails as $existingDetail) {
            if (!in_array($existingDetail->id, array_column($products, 'order_detail_id'))) {
                $stock = Stock::where('product_id', $existingDetail->product_id)->first();
                if ($stock) {
                    $stock->quantity += $existingDetail->quantity;
                    $stock->save();
                }

                $existingDetail->delete();
            }
        }

        return response()->json(['message' => 'Order updated successfully', 'order_id' => $order->id], 200);
    }

    public function quotationStore(Request $request)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'ref' => 'nullable|string',
            'remarks' => 'nullable|string',
            'discount' => 'nullable',
            'products' => 'required|json',
        ]);

        $products = json_decode($validated['products'], true);

        $itemTotalAmount = array_reduce($products, function ($carry, $product) {
            return $carry + $product['total_price'];
        }, 0);

        $netAmount = $itemTotalAmount - $validated['discount'];

        $order = new Order();
        $order->invoice = random_int(100000, 999999);
        $order->purchase_date = $validated['purchase_date'];
        $order->user_id = $validated['user_id'];
        $order->payment_method = $validated['payment_method'];
        $order->ref = $validated['ref'];
        $order->remarks = $validated['remarks'];
        $order->discount_amount = $validated['discount'];
        $order->net_amount = $netAmount;
        $order->vat_amount = $request->vat;
        $order->subtotal_amount = $itemTotalAmount;
        $order->order_type = 2;
        $order->status = 1;
        $order->save();

        foreach ($products as $product) {
            $orderDetail = new OrderDetails();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $product['product_id'];
            $orderDetail->quantity = $product['quantity'];
            // $orderDetail->size = $product['product_size'];
            // $orderDetail->color = $product['product_color'];
            $orderDetail->price_per_unit = $product['unit_price'];
            $orderDetail->total_price = $product['total_price'];
            $orderDetail->status = 1;
            $orderDetail->save();
        }

        return response()->json([
            'message' => 'Quotation created successfully'
        ], 200);
    }

    public function generatePDF($encoded_order_id)
    {
        $order_id = base64_decode($encoded_order_id);
        $order = Order::with(['orderDetails', 'user'])->findOrFail($order_id);

        $data = [
            'order' => $order,
            'currency' => CompanyDetails::value('currency'),
        ];

        $pdf = PDF::loadView('admin.in_house_sell.in_house_sell_order_pdf', $data);

        return $pdf->stream('order_' . $order->id . '.pdf');
    }

    public function index()
    {
        $inHouseOrders = Order::with('user')
        ->where('order_type', 1) 
        ->orderBy('id', 'desc') 
        ->get();

        return view('admin.in_house_sell.index', compact('inHouseOrders'));
    }

    public function quotations()
    {
        $quotations = Order::with('user')
        ->where('order_type', 2) 
        ->orderBy('id', 'desc') 
        ->get();

        return view('admin.in_house_sell.quotations', compact('quotations'));
    }

    public function generateDownloadPDF($encoded_order_id)
    {
        $order_id = base64_decode($encoded_order_id);
        $order = Order::with(['orderDetails', 'user'])->findOrFail($order_id);

        $data = [
            'order' => $order,
            'currency' => CompanyDetails::value('currency'),
        ];

        $pdf = PDF::loadView('admin.in_house_sell.quotation_pdf', $data);

        return $pdf->stream('order_' . $order->id . '.pdf');
    }

}
