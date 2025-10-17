<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index(){
        return view('admin.login');
    }

    public function admin_authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->route('admin.dashboard')->with('success', 'Admin logged in successlly');
            }

            else{
                return redirect()->route('admin.login')->with('error', 'Either email or password is incorrect');
            }
        }

        else{
             return redirect()->route('admin.login')->withErrors($validator)->withInput();
        }
    }
}
