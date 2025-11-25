<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.login');
    }
    public function register()
    {
        return view('front.register');
    }
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->passes()) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);

            $request->session()->flash('success', 'User register successfully');
            return response()->json([
                'status' => true,
                'message' => ' User register successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                if (session()->has('url_intended')) {
                    return redirect(session()->get('url_intended'));
                }

                return redirect()->route('user.account')->with('success', 'User logged in successfully');
            } else {
                return redirect()->back()->with('error', 'either email or password incorrect');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function account()
    {
        $user = Auth::user();
        $userDatails = CustomerAddress::where('user_id', $user->id)->first();
        return view('front.account', compact('userDatails'));
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login')->with('success', 'user logged out successfully');
    }

    public function orders()
    {
        $orderDetails = Order::orderBy('id', 'desc')->where('user_id', Auth::id())->get();
        $data['orderDetails'] = $orderDetails;
        return view('front.orders', $data);
    }

    public function orderDetail(Request $request, $id)
    {
        $orderDetails = Order::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$orderDetails) {
            return redirect()->route('user.orders')->with('error', 'Order not found');
        }
        $orderItemDetail = OrderItem::where('order_id', $id)->get();
        if (!$orderItemDetail) {
            return redirect()->route('user.orders')->with('error', 'Order not found');
        }


        return view('front.order-detail',  [
            'orderItemDetail' => $orderItemDetail,
            'orderDetails' => $orderDetails,
        ]);
    }




    public function update(Request $request)
    {
        // Validation using Validator Facade
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Agar validation fail ho jaye
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Current logged user
        $user = Auth::user();

        // Update user data

        User::where('id', $user->id)->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
        ]);

        CustomerAddress::where('user_id', $user->id)->update([
            'address' => $request->address,
        ]);


        return back()->with('success', 'Account details updated successfully!');
    }

    public function changePassword()
    {
        return view('front.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Old password is incorrect');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('home')->with('success', 'Password updated successfully!');
    }
}


