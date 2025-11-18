<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function cart()
    {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }
    public function addToCart(Request $request)
    {
        $product = Product::with('product_image')->find($request->id);
        if ($product == NULL) {
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }

        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            $productAlreadyExists = false;
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExists = true;
                }
            }

            if ($productAlreadyExists == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_image) ? $product->product_image->first() : '')]);
                $status = true;
                $message = $product->title . ' added in cart';
            } else {
                $status = false;
                $message = $product->title . ' already added in cart';
            }
        } else {
            //(producId, productTitle, qty, price , additional info)
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_image) ? $product->product_image->first() : '')]);

            $status = true;
            $message = $product->title . ' added in cart';
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $produtId = $itemInfo->id;
        $product = Product::find($produtId);
        if ($product->track_qty == 'Yes') {
            if ($product->qty >= $qty) {
                Cart::update($rowId, $qty);
                $request->session()->flash('success', 'Cart updated successfully');
                $status = true;
            } else {
                $request->session()->flash('error', 'Request qty (' . $qty . ') not available in stock.');
                $status = false;
            }
        } else {
            Cart::update($rowId, $qty);
            $request->session()->flash('success', 'Cart updated successfully');
            $status = true;
        }

        return response()->json([
            'status' => $status
        ]);
    }

    public function deleteCart(Request $request)
    {
        $itemInfo = Cart::get($request->rowId);
        if (empty($itemInfo)) {
            $message = 'this cart is not in the cart collection';
            $request->session()->flash('error', $message);
            $status = false;
        } else {
            Cart::remove($request->rowId);
            $message = 'cart deleted successfully';
            $request->session()->flash('success', $message);
            $status = true;
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function checkout()
    {
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false) {
            if (!session()->has('url_intended')) {
                session(['url_intended' => url()->current()]);
            }
            return redirect()->route('user.login');
        }

        session()->forget('url_intended');
        // $response = Http::get('https://restcountries.com/v3.1/all?fields=name');
        // $countries = $response->json();

        // $countries = collect($countries)
        //     ->sortBy(fn($country) => $country['name']['common'])
        //     ->values()
        //     ->all();
        return view('front.checkout');
        // return view('front.checkout', compact('countries'));
    }

    public function checkoutProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_no' => $request->mobile,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'notes' => $request->notes,

            ]
        );
        if ($request->payment_method == 'cod') {
            $shipping = 0;
            $discount = 0;
            $subtotal = Cart::subtotal(2, '.', '');
            $grandtotal = $subtotal + $shipping;

            $order = new Order();
            $order->user_id = $user->id;

            if ($request->session()->has('discountCode')) {
                $order->coupon_code = $request->session()->get('discountCode');
                $discount = $request->session()->get('newDiscount');
                $subtotal = $request->session()->get('newSubtotal');
                $grandtotal = $subtotal + $shipping;
            }
            $order->subtotal = $subtotal;
            $order->grand_total = $grandtotal;
            $order->discount = $discount;
            $order->shipping = $shipping;


            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile_no = $request->mobile;
            $order->address = $request->address;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->city = $request->city;
            $order->save();

            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }

            Cart::destroy();


            $request->session()->flash('success', 'you successfully placed your order');

            if ($request->session()->has('discountCode')) {
                $request->session()->forget('discountCode');
                $request->session()->forget('newSubtotal');
                $request->session()->forget('newDiscount');
            }
            return response()->json([
                'status' => true,
                'message' => 'order placed successfully',
                'order_id' => $order->id,
                'user_name' => $user->name
            ]);
        }
    }

    public function hello($user_name, $order_id)
    {
        return view('front.hello', compact('user_name', 'order_id'));
    }

    public function applyCoupon(Request $request)
    {
        $code = $request->coupon_code;

        // Coupon exists or not
        $coupon = DiscountCode::where('code', $code)
            ->where('status', 'Active')
            ->first();

        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid coupon code'
            ]);
        }
        if ($coupon->start_date != '') {
            $now = Carbon::now();
            $start_date = Carbon::parse($coupon->start_date);
            $end_date = Carbon::parse($coupon->end_date);

            // 1️⃣ not started yet
            if ($now->lt($start_date)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon code is not yet valid.1'
                ]);
            }

            // 2️⃣ expired
            if ($now->gt($end_date)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon code has expired.2'
                ]);
            }
        }

        if ($coupon->most_use > 0) {
            $couponUsed = Order::where('coupon_code', $code)->count();


            if ($couponUsed >= $coupon->most_use) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon is not valid, try next time.'
                ]);
            }
        }

        if ($coupon->max_user > 0) {
            $couponUsedByUser = Order::where('coupon_code', $code)
                ->where('user_id', Auth::id())
                ->count();
            if ($couponUsedByUser >= $coupon->max_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already used this coupon code maximum times allowed for a user.'
                ]);
            }
        }


        // 3️⃣ SUCCESS

        $subtotalString  = Cart::subtotal();
        $subtotal = floatval(str_replace(',', '', $subtotalString));

        if($subtotal < $coupon->minimum_amount){
            return response()->json([
                'status' => false,
                'message' => 'Your order amount is less than the minimum order amount for this coupon.'
            ]);
        }
        if ($coupon->type == 'percent') {
            $newDiscount = ($subtotal * $coupon->discount_amount) / 100;
            $newSubtotal = $subtotal - $newDiscount;
        }
        if ($coupon->type == 'fixed') {
            $newDiscount = $coupon->discount_amount;
            $newSubtotal = $subtotal - $coupon->discount_amount;
        }
        if ($newSubtotal < 0) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon code is not valid for this product'
            ]);
        }

        $request->session()->put('discountCode', $coupon->code);
        $request->session()->put('newSubtotal', $newSubtotal);
        $request->session()->put('newDiscount', $newDiscount);

        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully!',
            'newSubtotal' => $newSubtotal,
            'coupon_code' => $coupon
        ]);
    }

    public function removeCoupon(Request $request)
    {
        if ($request->session()->has('discountCode')) {     
            $request->session()->forget('discountCode');
            $request->session()->forget('newSubtotal');
            $request->session()->forget('newDiscount');
        }

        $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
        return response()->json([
            'status' => true,
            'subtotal' => $subtotal,
            'message' => 'Coupon removed successfully!'
        ]);
    }
}
