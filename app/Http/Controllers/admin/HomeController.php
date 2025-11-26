<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $ordersCount = Order::where('status', '!=', 'cancelled')->count();
        $usersCount = User::where('role', 1)->count();
        $productsCount = Product::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('grand_total');

        $currentMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total');

        $lastMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('grand_total');

        $last30DaysRevenue = Order::where('status', 'delivered')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('grand_total');

        $monthWiseRevenue = Order::where('status', 'delivered')
        ->whereYear('created_at', now()->year)
        ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as revenue')
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->get();

        return view('admin.dashboard', [
            'ordersCount' => $ordersCount,
            'usersCount' => $usersCount,
            'productsCount' => $productsCount,
            'totalRevenue' => $totalRevenue,
            'currentMonthRevenue' => $currentMonthRevenue,
            'lastMonthRevenue' => $lastMonthRevenue,
            'last30DaysRevenue' => $last30DaysRevenue,
            'monthWiseRevenue' => $monthWiseRevenue,
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'logged out successfully');
    }

    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
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
