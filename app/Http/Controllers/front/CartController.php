<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
            $order->subtotal = $subtotal;
            $order->grand_total = $grandtotal;
            $order->shipping = $shipping;
            $order->discount = $discount;
            $order->first_name = $request->first_name;
            $order->last_name = $request->first_name;
            $order->email = $request->email;
            $order->mobile_no = $request->mobile;
            $order->address = $request->address;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
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
            return response()->json([
                'status' => true,
                'message' => 'order placed successfully',
                'order_id' => $order->id,
                'user_name' => $user->name
            ]);
        } else {
        }
    }

    public function hello($user_name, $order_id){
           return view('front.hello', compact('user_name', 'order_id'));
    }
}
