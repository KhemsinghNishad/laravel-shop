<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            Brands::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,

            ]);

            $request->session()->flash('success', 'Brand created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function index(Request $request)
    {
        $brands = Brands::latest();
        $table_search = $request->table_search ?? '';
        if ($table_search != '') {
            $brands = $brands->where('name', 'like', '%' . $table_search . '%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }

    public function edit($id, Request $request)
    {
        $brands = Brands::find($id);
        if (empty($brands)) {
            $request->session()->flash('error', 'Brand not found');
            return redirect()->route('brands.list');
        }
        return view('admin.brands.edit', compact('brands'));
    }
    public function update($id, Request $request)
    {
        $brands = Brands::find($id);
        if (empty($brands)) {
            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $id . ',id',
            // 'slug' => 'required|unique:brands',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $brands->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ]);
            $request->session()->flash('success', 'Brand Updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        $brand = Brands::find($id);
        if (empty($brand)) {
            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => true,
                'message' => 'Brand not found'
            ]);
        }

        $brand->delete();
        $request->session()->flash('success', 'Brand deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }
}
