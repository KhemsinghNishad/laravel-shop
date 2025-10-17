<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Sub_Category;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->category_id)) {
            $subCategories = Sub_Category::where('category_id', $request->category_id)
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'subcategories' => $subCategories
            ]);
        } else {
            return response()->json([
                'status' => false,
                'subcategories' => []
            ]);
        }
    }
}
