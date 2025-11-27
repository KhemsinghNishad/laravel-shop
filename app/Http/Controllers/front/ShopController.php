<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\Sub_Category;
use App\Models\TempImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $product = Product::where('slug', $slug)
            ->withCount('ratings')
            ->withSum('ratings as rating_sum', 'rating')
            ->with([
                'product_image',
                'ratings' => function ($q) {
                    $q->where('status', 1);
                }
            ])
            ->first();

        if ($product == NULL) {
            abort(404);
        }


        // delete temp images

        $date = Carbon::now()->subDays(1);

        $images = TempImage::where('created_at', '<', $date)->get();        

        foreach ($images as $image) {

            $filename = $image->name;

            $mainPath  = public_path('temp/' . $filename);
            $thumbPath = public_path('temp/thumb/' . $filename);            

            if (file_exists($mainPath)) {
                unlink($mainPath);
            }

            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }

            $image->delete();
        }


        if (!empty($product->related_products)) {
            $relatedProductsArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $relatedProductsArray)->with('product_image')->get();
        }

        $data['relatedProducts'] = $relatedProducts;
        $data['product'] = $product;
        return view('front.product', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $existing = ProductRating::where('product_id', $request->product_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You already rated this product.');
        }


        // Prevent guest users from spoofing user_id (extra safety)
        $userId = auth()->id();

        ProductRating::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'user_id' => $userId,
            ],
            [
                'name' => $request->name,
                'email' => $request->email,
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return redirect()->back()->with('success', 'Your review has been submitted. Thank you!');
    }
}
