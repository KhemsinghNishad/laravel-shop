<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_Image;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_image');
        if ($request->table_search) {
            $products = $products->where('title', 'like', '%' . $request->table_search . '%');
        }
        $products = $products->paginate(10);
        $data['products'] = $products;
        return view('admin.product.list', $data);
    }
    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'asc')->get();
        $data['categories'] = $categories;
        $brands = Brands::orderBy('name', 'asc')->get();
        $data['brands'] = $brands;
        return view('admin.product.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->related_products);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = Product::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'shipping_returns' => $request->shipping_returns,
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'track_qty' => $request->track_qty,
                'qty' => $request->qty,
                'status' => $request->status,
                'category_id' => $request->category,
                'sub__category_id' => $request->sub_category,
                'brand_id' => $request->brand,
                'is_featured' => $request->is_featured,
                'related_products' => (!empty($request->related_products) ? implode(',', $request->related_products) : ''),
            ]);

            if ($request->image_array) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $productImages = new Product_Image();
                    $productImages->product_id = $product->id;
                    $productImages->image = 'NULL';
                    $productImages->save();
                    $imageName = $product->id . '-' . $productImages->id . '-' . time() . '.' . $ext;
                    $productImages->image = $imageName;
                    $productImages->save();
                    //generate product thumbnails


                    //large image
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/large/' . $imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->scaleDown(1400);
                    $image->save($destPath);
                    // $image = Image::make($sourcePath);
                    // $image->resize();
                    // $image->save($destPath);

                    // small image

                    $destPath = public_path() . '/uploads/product/small/' . $imageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sourcePath);
                    $image->cover(300, 300);
                    $image->save($destPath);
                    // $image = Image::make($sourcePath);
                    // $image->resize();
                    // $image->save($destPath);



                }
            }

            $request->session()->flash('success', 'Product added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $product = Product::find($id);
        $data = [];
        $relatedProducts = [];
        if(!empty($product->related_products)){
            $productArray= explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->get();
        }
        if ($product) {
            $data['product'] = $product;
        } else {
            return redirect()->route('product.list')->with('error', 'no category found');
        }
        $categories = Category::orderBy('name', 'asc')->get();
        $data['categories'] = $categories;
        $brands = Brands::orderBy('name', 'asc')->get();
        $data['brands'] = $brands;
        $productImage = Product_Image::where('product_id', $id)->get();
        $data['productImage'] = $productImage;
        $data['relatedProducts'] = $relatedProducts;

        return view('admin.product.update', $data);
    }

    public function update($id, Request $request)
    {
        $rules = [
            'title' => 'required',
            // 'slug' => 'required|unique:products',
            'slug' => 'required|unique:products,slug,' . $id . ',id',
            'price' => 'required|numeric',
            // 'sku' => 'required|unique:products',
            'sku' => 'required|unique:products,sku,' . $id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = Product::where('id', $id)->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'shipping_returns' => $request->shipping_returns,
                'related_products' => (!empty($request->related_products) ? implode(',', $request->related_products) : ''),
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'track_qty' => $request->track_qty,
                'qty' => $request->qty,
                'status' => $request->status,
                'category_id' => $request->category,
                'sub__category_id' => $request->sub_category,
                'brand_id' => $request->brand,
                'is_featured' => $request->is_featured,
            ]);

            $request->session()->flash('success', 'Product updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
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
        $product = Product::find($id);
        $productImages = Product_Image::where('product_id', $id)->get();
        if ($product) {
            foreach ($productImages as $productImage) {
                File::delete(public_path() . '/uploads/product/large/' . $productImage->image);
                // File::delete(public_path() . '/uploads/product/large/' . $productImage->image);
                File::delete(public_path() . '/uploads/product/small/' . $productImage->image);
            }

            $product->delete();

            $request->session()->flash('success', 'product deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'product deleted successfully'
            ]);
        } else {
            $request->session()->flash('error', 'product not found');
            return response()->json([
                'status' => false,
                'message' => 'product not found'
            ]);
        }
    }

    public function getProduct(Request $request)
    {
        $tempProduct = [];
        if ($request->term != '') {
            $products  = Product::where('title', 'like', '%' . $request->term . '%')->get();

            foreach ($products as $product) {
                $tempProduct[] = array(
                    'id' => $product->id,
                    'text' => $product->title
                );
            }
        }

       return response()->json([
            'tags' => $tempProduct,
            'status' => true
       ]);
    }
}
