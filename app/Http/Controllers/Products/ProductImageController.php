<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function destroy($product_image)
    {
        $product_image = ProductImage::where('uuid', $product_image)->firstOrFail();

        $image_path = 'products/images/' . $product_image->image;

        if (Storage::disk('public')->exists($image_path)) {
            Storage::disk('public')->delete($image_path);
        }

        $product_image->delete();

        return redirect()->back()->with('success', 'Product image deleted successfully');
    }

    public function sort(Request $request)
    {
        if(!empty($request->photo_id)) {
            $i = 1;
            foreach($request->photo_id as $photo_id) {
                $image = ProductImage::find($photo_id);
                $image->sort_order = $i;
                $image->save();

                $i++;
            }
        }

        $json['success'] = true;
        echo json_encode($json);
    }
}
