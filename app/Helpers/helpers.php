<?php

use App\Mail\sendInvoice;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product_Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
    // return Category::orderBy('name', 'asc')
    // ->with('sub_categories')
    // ->where('status', 1)
    // ->where('showHome', 'Yes')
    // ->get();

    return Category::orderBy('name', 'asc')
        ->with('sub_categories')
        ->where('status', 1)
        ->where('showHome', 'Yes')
        ->get();
}

function getProductImage($id){
    return Product_Image::where('product_id', $id)->first();
}

function sendEmail($id){
    $orders = Order::where('id', $id)->with('items')->first();
    
    if(!$orders){
        dd("fail");
        return;
    }
    $mailData = [
        'subject' => 'Thanks for your, keep shopping from our store and get cashbacks',
        'orders' => $orders
    ];
    Mail::to($orders->email)->send(new sendInvoice($mailData));
}

// function getCountries()
// {
//     $response = Http::get('https://restcountries.com/v3.1/all?fields=name,cca3');
//     $countries = collect($response->json())->map(function ($country, $index) {
//         return [
//             'id' => $index + 1,              // Auto-generated integer ID
//             'code' => $country['cca3'],       // Original 3-letter code
//             'name' => $country['name']['common']
//         ];
//     });

//     return response()->json($countries);
// }
