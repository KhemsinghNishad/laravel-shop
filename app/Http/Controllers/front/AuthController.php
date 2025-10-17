<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
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
        return view('front.account');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login')->with('success', 'user logged out successfully');
    }
}
