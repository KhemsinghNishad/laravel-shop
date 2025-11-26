<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordEmail;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function forgotPassword()
    {
        return view('front.forgot-password');
    }

    public function forgotPasswordEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $token = Str::random(60);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);
        $user = User::where('email', $request->email)->first();
        $mailData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have requested to change your password',
        ];
        Mail::to($request->email)->send(new ForgotPasswordEmail($mailData));

        // Here you can implement the logic to send a password reset email to the user.

        return back()->with('success', 'Password reset link has been sent to your email address.');
    }

    public function forgotPasswordForm(Request $request, $token)
    {
        $token = $token;

        $tokenExists = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$tokenExists) {
            return redirect()->route('user.forgot-password')->with('error', 'Invalid or expired password reset token.');
        }
        return view('front.forgot-password-form', compact('token'));
    }

    public function resetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|exists:password_reset_tokens,token',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator);                
        }

        
        $tokenData = DB::table('password_reset_tokens')->where('token', $request->token)->first();
        if (!$tokenData) {
            return redirect()->route('user.forgot-password')->with('error', 'Invalid or expired password reset token.');
        }

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) {
            return redirect()->route('user.forgot-password')->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        return redirect()->route('user.login')->with('success', 'Password has been reset successfully. You can now log in with your new password.');
    }
}


