<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'logged out successfully');
    }

    public function changePassword(){
        return view('admin.change-password');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $admin = Auth::guard('admin')->user();

        if (!password_verify($request->old_password, $admin->password)) {
            return back()->with('error', 'Old password is incorrect');
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.dashboard')->with('success', 'Password updated successfully');
    }
}
