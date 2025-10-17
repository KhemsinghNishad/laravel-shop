<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index()
    {
        $product = Product::orderBy('title', 'ASC')->with('product_image')->get();
        if (empty($product)) {
            return response()->json([
                'status' => false,
                'products' => 'Product not found'
            ]);
        }
        return response()->json([
            'status' => true,
            'products' => $product
        ]);
    }
}
