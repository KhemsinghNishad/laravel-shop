<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::with('products')
            ->where('user_id', Auth::id())
            ->get();

        return view('front.wishlist', compact('wishlist'));
    }

    public function add($productId)
    {
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        return redirect()->route('wishlist.index')->with('success', 'Added to wishlist');
    }

    public function remove($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return back()->with('success', 'Removed from wishlist');
    }
}
