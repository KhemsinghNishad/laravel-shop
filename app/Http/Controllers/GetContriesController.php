<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class GetContriesController extends Controller
{
    public function index()
    {
        // $response = Http::get('https://restcountries.com/v3.1/all?fields=name,cca3');
        // $countries = collect($response->json())->map(function ($country, $index) {
        //     return [
        //         'id' => $index + 1,              // Auto-generated integer ID
        //         'code' => $country['cca3'],       // Original 3-letter code
        //         'name' => $country['name']['common']
        //     ];
        // });

        // return response()->json($countries);

        $response = Http::get('https://restcountries.com/v3.1/all?fields=name,cca3');

        $countries = collect($response->json())
            ->map(function ($country, $index) {
                return [
                    'id' => $index + 1,               // Auto-generated integer ID
                    'code' => $country['cca3'],       // 3-letter country code
                    'name' => $country['name']['common'] ?? 'Unknown',
                ];
            })
            ->sortBy('name')                         // Sort ascending by name (A → Z)
            ->values();                              // Reset indexes after sorting

        return response()->json($countries);
    }
}
