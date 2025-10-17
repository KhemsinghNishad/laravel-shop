<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Sub_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.sub_category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub__categories',
            'status' => 'required',
            'category' => 'required'
        ]);

        if ($validator->passes()) {
            Sub_Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
                // 'showHome' => $request->showHome,showHome
                'showHome' => $request->showHome,
                'category_id' => $request->category
            ]);
            $request->session()->flash('success', 'Sub category created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sub category created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors'  => $validator->errors()
            ]);
        }
    }
    public function index(Request $request)
    {
        $sub_categories = Sub_Category::select('sub__categories.*', 'categories.name as categoryName')->latest('sub__categories.id')->leftJoin('categories', 'categories.id', 'sub__categories.category_id');

        $table_searchabe_data = $request->table_search ?? '';

        if ($table_searchabe_data != '') {
            $sub_categories = $sub_categories->where('sub__categories.name', 'like', '%' . $table_searchabe_data . '%');
            $sub_categories = $sub_categories->orWhere('categories.name', 'like', '%' . $table_searchabe_data . '%');
        }

        $sub_categories = $sub_categories->paginate(10);
        return view('admin.sub_category.list', compact('sub_categories'));
    }

    public function edit($id, Request $request)
    {
        $sub_categories = Sub_Category::find($id);
        $categories = Category::orderBy('name', 'asc')->get();
        if (empty($sub_categories)) {
            $request->session()->flash('error', 'Record not found');
            return redirect()->route('sub-categories.list');
        }
        return view('admin.sub_category.edit', compact('sub_categories', 'categories'));
    }

    public function update($id, Request $request)
    {
        $sub_category = Sub_Category::find($id);

        if (empty($sub_category)) {
            $request->session()->flash('error', 'Sub category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Sub category not found',
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub__categories,slug,' . $id . ',id',
            // 'slug' => 'required|unique:sub__categories',
            'status' => 'required',
            'category' => 'required'
        ]);

        if ($validator->passes()) {
            Sub_Category::where('id', $id)->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
                'showHome' => $request->showHome,
                'category_id' => $request->category
            ]);
            $request->session()->flash('success', 'Sub category updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sub category updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors'  => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $sub_category = Sub_Category::find($id);

        if (empty($sub_category)) {
            $request->session()->flash('error', 'Sub category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Sub category not found',
            ]);
        }

        $sub_category->delete();
        $request->session()->flash('success', 'Sub category deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Sub category deleted successfully'
        ]);
    }
}
