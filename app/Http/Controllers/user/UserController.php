<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(10);

        return view('user.list', compact('users'));
    }


    public function create()
    {
        return view('user.create');
    }
    public function store(Request $request)
    {
        // Step 1: Validator class use
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'   => 'required',
            'password' => 'required|min:6',
        ]);

        // Step 2: If validation fails, return back with errors
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)   // errors pass to view
                ->withInput();             // old() values
        }

        // Step 3: Save user
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.list')->with('success', 'User added successfully!');
    }
}
