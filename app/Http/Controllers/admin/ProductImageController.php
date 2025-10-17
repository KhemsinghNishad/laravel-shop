<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product_Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $image =  $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();
        $productImages = new Product_Image();
        $productImages->product_id = $request->product_id;
        $productImages->image = 'NULL';
        $productImages->save();

        $imageName = $request->product_id . '-' . $productImages->id . '-' . time() . '.' . $ext;
        $productImages->image = $imageName;
        $productImages->save();

        //large image
        $destPath = public_path() . '/uploads/product/large/' . $imageName;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sourcePath);
        $image->scaleDown(1400);
        $image->save($destPath);

        // small image

        $destPath = public_path() . '/uploads/product/small/' . $imageName;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sourcePath);
        $image->cover(300, 300);
        $image->save($destPath);

        return response()->json([
            'status' => true,
            'image_id' => $productImages->id,
            'image_path' => asset('uploads/product/small/' . $imageName),
            'message' => 'image uploaded successfully'
        ]);
    }

    public function destroy(Request $request)
    {
        $productImage = Product_Image::find($request->id);

        if ($productImage) {
            File::delete(public_path() . '/uploads/product/large/' . $productImage->image);
            File::delete(public_path() . '/uploads/product/small/' . $productImage->image);

            $productImage->delete();

            return response()->json([
                'status' => true,
                'message' => 'image deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'image not found'
            ]);
        }
    }
}
