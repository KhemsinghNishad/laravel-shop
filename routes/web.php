<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\CategoriesController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ImageUploadController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\front\AuthController;
use App\Http\Controllers\front\CartController;
use App\Http\Controllers\front\HomeController as FrontHomeController;
use App\Http\Controllers\front\ShopController;
use App\Http\Controllers\GetContriesController;
use App\Http\Controllers\ShippingChargeController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\AdminRedirect;
use App\Http\Middleware\UserAuthenticate;
use App\Http\Middleware\UserRedirect;
use App\Models\Brands;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Route::get('/', function () {
//     return view('welcome');
// });

// frontend routes

Route::get('/', [FrontHomeController::class, 'index'])->name('home');

Route::get('/shop/{categorySlug?}/{subcategorySlug?}', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('product.shop');
// Route::get('/product/cart', [CartController::class, 'cart'])->name('product.cart');

Route::get('/front/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/front/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/front/update-cart', [CartController::class, 'updateCart'])->name('update.cart');
Route::post('/front/delete-cart', [CartController::class, 'deleteCart'])->name('delete.cart');
Route::get('/front/checkout-cart', [CartController::class, 'checkout'])->name('checkout.cart');
Route::post('/front/checkout-process', [CartController::class, 'checkoutProcess'])->name('checkout.process');
Route::get('/front/say-hello/{user_name}/{orderId}', [CartController::class, 'hello'])->name('hello');



Route::prefix('/account')->group(function () {
    // Route::middleware('guest')->group(function () {
    Route::middleware(UserRedirect::class)->group(function () {
        Route::get('/user/register', [AuthController::class, 'register'])->name('user.register');
        Route::post('/user/process-register', [AuthController::class, 'processRegister'])->name('user.process-register');
        Route::get('/user/login', [AuthController::class, 'login'])->name('user.login');
        Route::post('/user/authenticate', [AuthController::class, 'authenticate'])->name('user.authenticate');
    });

    // });
    // Route::middleware('auth')->group(function () {
    Route::middleware(UserAuthenticate::class)->group(function () {
        Route::get('/user/account', [AuthController::class, 'account'])->name('user.account');
        Route::get('/user/logout', [AuthController::class, 'logout'])->name('user.logout');
    });

    // });
});



Route::get('demo', [CategoriesController::class, 'demo']);




// Admin routes

Route::prefix('/admin')->middleware('guest:admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('authenticate', [AdminLoginController::class, 'admin_authenticate'])->name('admin.authenticate');
});

Route::prefix('/admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');


    //  Categories Route
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.list');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

    Route::get('/getSlug', function (Request $request) {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug(($request->title));
        }

        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    })->name('getSlug');




    Route::post('temp-image-upload', [ImageUploadController::class, 'create'])->name('temp.image.create');


    //sub category routes

    Route::get('/sub-category/create', [SubCategoryController::class, 'create'])->name('sub-category.create');
    Route::post('/sub-category/store', [SubCategoryController::class, 'store'])->name('sub-category.store');
    Route::get('/sub-category', [SubCategoryController::class, 'index'])->name('sub-categories.list');
    Route::get('/sub-category/{sub_category}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
    Route::put('/sub-category/{sub_category}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
    Route::delete('/sub-category/{sub_category}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');


    //Brands routes

    Route::get('/brand/create', [BrandsController::class, 'create'])->name('brands.create');
    Route::post('/brand/store', [BrandsController::class, 'store'])->name('brands.store');
    Route::get('/brand', [BrandsController::class, 'index'])->name('brands.list');
    Route::get('/brand/{brand}/edit', [BrandsController::class, 'edit'])->name('brands.edit');
    Route::put('/brand/{brand}', [BrandsController::class, 'update'])->name('brands.update');
    Route::delete('/brand/{brand}', [BrandsController::class, 'destroy'])->name('brands.destroy');

    //product routes

    Route::get('/product', [ProductController::class, 'index'])->name('product.list');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/subCategory', [ProductSubCategoryController::class, 'index'])->name('product.subcategory');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::get('/get-product', [ProductController::class, 'getProduct'])->name('product.get-product');

    Route::post('/product-image/update', [ProductImageController::class, 'update'])->name('product.image.update');
    Route::delete('/product-image', [ProductImageController::class, 'destroy'])->name('product.image.delete');


    Route::get('/get-countries', [GetContriesController::class, 'index'])->name('getcountries');
    Route::get('/shipping-charge/create', [ShippingChargeController::class, 'create'])->name('shipping.charge.create');
    Route::post('/shipping-charge/store', [ShippingChargeController::class, 'store'])->name('shipping.store');

    Route::prefix('/discount-codes')->group(function () {
        Route::get('/', [App\Http\Controllers\admin\DiscountCodeController::class, 'index'])->name('discount-codes.index');
        Route::get('/create', [App\Http\Controllers\admin\DiscountCodeController::class, 'create'])->name('discount-codes.create');
        Route::post('/store', [App\Http\Controllers\admin\DiscountCodeController::class, 'store'])->name('discount-codes.store');
        Route::get('/edit/{id}', [App\Http\Controllers\admin\DiscountCodeController::class, 'edit'])->name('discount-codes.edit');
        Route::put('/{id}', [App\Http\Controllers\admin\DiscountCodeController::class, 'update'])->name('discount-codes.update');
        Route::delete('/destroy/{id}', [App\Http\Controllers\admin\DiscountCodeController::class, 'destroy'])->name('discount-codes.destroy');
    });
});
