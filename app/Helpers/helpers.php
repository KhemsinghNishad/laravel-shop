<?php

use App\Models\Category;
use App\Models\Product_Image;
use Illuminate\Support\Facades\Http;

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
