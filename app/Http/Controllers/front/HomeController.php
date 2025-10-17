<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('product_image')
            ->where('is_featured', 'Yes')
            ->where('status', 1)->get();
        $latestProducts = Product::where('status', 1)
            ->orderBy('id', 'asc')
            ->take(10)->get();
        $data['features'] = $featuredProducts;
        $data['latestProducts'] = $latestProducts;
        return view('front.home', $data);
    }
}
