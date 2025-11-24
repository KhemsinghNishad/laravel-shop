<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sub_Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = NULL, $subcategorySlug = NULL)
{
    $categorySelected = '';
    $subcategorySelected = '';
    $brandsArray = [];
    $minPrice = $maxPrice = null;
    $priceRange = [];
    $sortOrder = '';

    $categories = Category::orderBy('name', 'asc')->with('sub_categories')->where('status', 1)->get();
    $brands = Brands::orderBy('name', 'asc')->where('status', 1)->get();
    $products = Product::where('status', 1);

    //Apply filter here

    if (!empty($categorySlug)) {
        $category = Category::where('slug', $categorySlug)->first();
        $products = Product::where('category_id', $category->id);
        $categorySelected = $category->id;
    }

    if (!empty($subcategorySlug)) {
        $subCategory = Sub_Category::where('slug', $subcategorySlug)->first();
        $products = Product::where('sub__category_id', $subCategory->id);
        $subcategorySelected =  $subCategory->id;
    }

    if (!empty($request->get('brand'))) {
        $brandsArray = explode(',', $request->get('brand'));
        $products = $products->whereIn('brand_id', $brandsArray);
    }

    if ($request->get('price')) {
        $priceRange = $request->get('price');
    }

    if (!empty($priceRange)) {
        [$minPrice, $maxPrice] = explode(',', $priceRange);
        $products = $products->whereBetween('price', [(int)$minPrice, (int)$maxPrice]);
    }

    // ⭐⭐⭐ SEARCH FILTER FIX ⭐⭐⭐
    if ($request->product_search) {
        $products = $products->where('title', 'like', '%' . $request->product_search . '%');
    }
    // ⭐⭐⭐ SEARCH FIX END ⭐⭐⭐


    if ($request->get('sort')) {
        $sortOrder = $request->get('sort');
    }

    if (!empty($sortOrder)) {
        if ($sortOrder == 'low') {
            $products = $products->whereBetween('price', [1, 1499])
                ->orderBy('price', 'asc')
                ->with('product_image')
                ->paginate(10);
        } elseif ($sortOrder == 'high') {
            $products = $products->whereBetween('price', [1500, 1000000])
                ->orderBy('id', 'desc')
                ->with('product_image')
                ->paginate(10);
        }
    } else {
        $products = $products->orderBy('id', 'asc')->with('product_image')->paginate(10);
    }

    $data['categories'] = $categories;
    $data['brands'] = $brands;
    $data['products'] = $products;
    $data['categorySelected'] = $categorySelected;
    $data['subcategorySelected'] = $subcategorySelected;
    $data['brandsArray'] = $brandsArray;
    $data['selectedMin'] = $minPrice;
    $data['selectedMax'] = $maxPrice;

    return view('front.shop', $data);
}


    public function product($slug)
    {

        $relatedProducts = [];
        $product = Product::where('slug', $slug)->with('product_image')->first();
        if ($product == NULL) {
            abort(404);
        }
        if (!empty($product->related_products)) {
            $relatedProductsArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $relatedProductsArray)->with('product_image')->get();
        }

        $data['relatedProducts'] = $relatedProducts;
        $data['product'] = $product;
        return view('front.product', $data);
    }
}
