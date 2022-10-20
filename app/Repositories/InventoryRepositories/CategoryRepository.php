<?php

namespace App\Repositories\InventoryRepositories;

use App\Exceptions\GeneralException;
use App\Models\BusinessType;
use App\Models\Category;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;


class CategoryRepository
{
    use UploadTrait;

    public function allCategories()
    {
        return Category::query();
    }

    public function categoriesWithLevel($level)
    {
        return Category::query()->level($level);
    }
    
    public function activeCategoriesWithLevel($level)
    {
        return Category::query()->level($level)->active(1);
    }

    public function store($request, $imagePath)
    {
        $parentCategoryId = $request->parent_id;

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'image' => isset($imagePath[0]) ? $imagePath[0] : null,
            'parent_id' => $parentCategoryId > 0 ? $parentCategoryId : null,
            'level' => $parentCategoryId > 0 ? Category::find($parentCategoryId)->level + 1 : 0,
            'is_active' => $request->is_active ?? 0,
        ]);
    }

    public function show($categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    public function update($category, $request, $imagePath)
    {
        $parentCategoryId = $request->parent_id;

        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');
        $category->image = isset($imagePath[0]) ? $imagePath[0] : $category->image;
        $category->parent_id = $parentCategoryId;
        $category->level  = $parentCategoryId > 0 ? Category::find($parentCategoryId)->level + 1 : 0;
        $category->is_active = $request->is_active ?? 0;
        $category->save();
    }


    public function destroy($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $this->deleteFile($category->image);
        $this->deleteFile($this->findOriginalFile($category->image));
        $this->deleteFile($this->findThumbFile($category->image));

        return $category->delete();
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
                $folder = 'uploads/category/' . date('Y') . '/' . date('m');
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

    public function subCategories($category)
    {
        return $category->subCategories();
    }
}
