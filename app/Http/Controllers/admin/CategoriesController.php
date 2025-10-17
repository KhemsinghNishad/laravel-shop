<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();
        $table_search = $request['table_search'] ?? "";
        if ($table_search != "") {
            $categories = $categories->where('name', 'like', '%' . $table_search . '%');
        }

        // $category = Category::orderBy('created_at', 'desc')->paginate(10);
        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category['name'] = $request->name;
            $category['slug'] = $request->slug;
            $category['status'] = $request->status;
            $category['showHome'] = $request->showHome;
            $category->save();


            if (!empty($request->imageId)) {
                $tempImage = TempImage::find($request->imageId);
                $extArray = explode('.', $tempImage->name);

                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                // $sPath = public_path().'\temp'.$tempImage->name;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);


                $dpath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sPath);
                $image->cover(450, 600);
                $image->save($dpath);
                // $img = Image::make($sPath);
                // $img->resize(450, 600);
                // $img->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }


            $request->session()->flash('message', 'Category added succefully');

            return response()->json([
                'status' => true,
                'message' => 'Category added succefully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found',
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $categoryId . ',id',
        ]);

        if ($validator->passes()) {
            $category['name'] = $request->name;
            $category['slug'] = $request->slug;
            $category['status'] = $request->status;
            $category['showHome'] = $request->showHome;
            $category->save();

            $oldImage = $category->image;


            if (!empty($request->imageId)) {
                $tempImage = TempImage::find($request->imageId);
                $extArray = explode('.', $tempImage->name);

                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                // $sPath = public_path().'\temp'.$tempImage->name;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);


                $dpath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sPath);
                $image->cover(450, 600);
                $image->save($dpath);
                // $img = Image::make($sPath);
                // $img->resize(450, 600);
                // $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                //delete old image

                File::delete(public_path() . '/uploads/category/' . $oldImage);
                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
            }


            $request->session()->flash('message', 'Category updated succefully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated succefully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.list');
        }

        return view('admin.category.edit', compact('category'));
    }
    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
        }

        File::delete(public_path() . '/uploads/category/' . $category->image);
        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function demo()
    {
        $data = Category::join('brands', 'categories.id', '=', 'brands.id')->get();
        return $data;
    }
}
