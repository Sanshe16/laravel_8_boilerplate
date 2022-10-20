<?php

namespace App\Repositories\InventoryRepositories;

use App\Models\Product;
use App\Models\Category;
use App\Traits\UploadTrait;
use App\Models\ProductMedia;
use App\Models\ShippingType;
use App\Models\CategoryProduct;


class ProductRepository
{
    use UploadTrait;

    public function products()
    {
        return Product::query();
    }

    public function store($request, $imagesPaths)
    {
        if($request->is_promotion == 0)
        {
            $promotion_price = 0;
        }
        else{
            $promotion_price = intval($request->promotion_price);
        }

        $product = Product::create([
            'user_id' => auth()->id(),
            'name' => $request->product_name,
            'purchase_price' => $request->purchase_price,
            'price' => $request->product_price,
            'category_id' => $request->selected_categories,
            'vendor_id' => $request->stock_vendor_id,
            'details' => $request->details,
            'quantity' => intval($request->quantity),
            'product_box' => $request->product_box,
            'sku' => $request->sku,
            'is_promotion' => $request->is_promotion ?? 0,
            'promotion_price' => $promotion_price,
            'run_continuously' => $request->run_continue ?? 0,
            'promotion_start_date' => $request->start_date,
            'promotion_end_date' => $request->run_continue == 1 ? null : $request->end_date,
            'product_stock_owner' => $request->product_stock_owner,
            'stock_limit' => $request->stock_limit,
            'shipping_type_id' => $request->shipping_type_id,

            'shipping_cost' => intval($request->shipping_cost),
            'is_active' => $request->is_active ?? 0,
            'created_at' => now()
        ]);


        collect($imagesPaths)->each(function($imagePath) use($product)
        {
            ProductMedia::create([
                'product_id' => $product->id,
                'product_image' => $imagePath,
                'media_type' => 'photo' ,
                'media_text' => '',
                'created_at' => now()
            ]);
        });

        return $product;
    }

    public function show($productId)
    {
        return Product::where('id', $productId)->with(['media', 'categories'])->first();
    }

    public function update($request, $id, $imagesPaths)
    {
        if($request->is_promotion == 0)
        {
            $promotion_price = 0;
        }
        else{
            $promotion_price = intval($request->promotion_price);
        }
        $product = Product::find($id);
        $product->name = $request->product_name;
        $product->purchase_price = $request->purchase_price;
        $product->price = $request->product_price;
        $product->details = $request->details;
        // $product->category_id = $request->category_id;
        $product->vendor_id = $request->stock_vendor_id;
        $product->quantity = intval($request->quantity);
        $product->product_box = $request->product_box;
        $product->sku = $request->sku;
        $product->is_promotion = $request->is_promotion ?? 0;
        $product->promotion_price = $promotion_price;
        $product->run_continuously = $request->run_continue ?? 0;
        $product->promotion_start_date = $request->start_date;
        $product->promotion_end_date = $request->run_continue == 1 ? null : $request->end_date;
        $product->product_stock_owner = $request->product_stock_owner;
        $product->stock_limit = $request->stock_limit;
        $product->shipping_type_id = $request->shipping_type_id;

        $product->shipping_cost = intval($request->shipping_cost);
        $product->is_active = $request->is_active ?? 0;
        $product->updated_at = now();
        $product->save();

        // delete images that were removed in frontend
        $prevImgsIds = array_map(function($v){ return (int) trim($v, "'"); }, explode(",", $request->prev_img_ids));
        $mediaToDelete = ProductMedia::where('product_id', $id)->whereNotIn('id', $prevImgsIds)->get();
        $mediaToDelete->each(function($media) {
            $this->deleteFile($media->name);
            $media->delete();
        });

        // create any new media
        // This step must be after deleting images
        collect($imagesPaths)->each(function($imagePath) use($product) {
            ProductMedia::create([
                'product_id' => $product->id,
                'product_image' => $imagePath,
                'media_type' => 'photo' ,
                'media_text' => '',
                'created_at' => now()
            ]);
        });

        return $product;
    }

    public function storeImage($request)
    {
        $imagesPaths = [];
        if ($request->has('image') && !is_null($request->image)) {

            if(is_array($request->image))
            {
                $images = $request->image;
            }
            else
            {
                $images_arr = [];
                $images_arr[] = $request->image;
                $images = $images_arr;
            }

            $day = date('d');
            $time = md5(time());

            foreach ($images as $key => $image) {
                //check mime type is video or image
                $type = $this->whatIsMyMimeType($image);
                // create random file names
                $keyGenerate = generateKey();
                // Define folder path
                $folder = 'uploads/product/' . date('Y') . '/' . date('m');
                //Define file name
                $fullFileName = $keyGenerate . '_' . $day . '_' . $time . '_' . $type;
                $path = $folder . '/' . $fullFileName . '.' . $image->getClientOriginalExtension();

                // Make a file path where image will be stored [ folder path + file name + file extension]
                $this->uploadFile($image, $folder, 'public_uploads', $fullFileName);
                $this->optimizeFile($path, $type);
                $imagesPaths[] = $path;
            }

            return $imagesPaths;
        }

        return null;
    }

    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);
        $imagePaths = $product->media()->pluck('product_image');

        foreach($imagePaths as $imagePath)
        {
            $this->deleteFile($imagePath);
            $this->deleteFile($this->findOriginalFile($imagePath));
            $this->deleteFile($this->findThumbFile($imagePath));
        }

        return $product->delete();
    }

    public function attachCategoriesWithProduct($product, $categories)
    {
        //attach categories with product
        return $product->categories()->sync($categories);
    }

    public function fetchShippingType($shipping_type_id)
    {
        return ShippingType::where('id', $shipping_type_id);
    }

    public function fetchAllShippingTypes()
    {
        return ShippingType::query()->status(1)->get();
    }

    public function getCategoryAndChildrenIds($categoryId)
    {
        $category = Category::whereId($categoryId)->with('allSubCategories')->firstOrFail();
        return $category->categoryAndChildrenIds();
    }

    public function getProductidsWithCategories($categoryAndChildrenIds)
    {
        return Product::query()->select('products.*')
         ->join('category_product', function ($join) use ($categoryAndChildrenIds) {
            $join->on('category_product.product_id', '=', 'products.id');
            $join->whereIn('category_product.category_id', $categoryAndChildrenIds);
        })
        ->active(1);
    }
}
