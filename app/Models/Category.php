<?php

namespace App\Models;

use App\Models\Traits\Method\CategoryMethod;
use App\Models\Traits\Scope\CategoryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, CategoryScope, CategoryMethod;

    protected $fillable = ['name', 'slug', 'image', 'parent_id', 'level', 'is_active'];

    public function scopeSearchByName($query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->active(1); //scopeActive -> where is_active is 1
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    // This will recursively get parent of category
    public function allParentCategories()
    {
        return $this->parentCategory()->with('allParentCategories');
    }

    // This will recursively get sub categories of category
    public function allSubCategories()
    {
        return $this->subCategories()->with('allSubCategories');
    }

    // This will return all products that belong directly/indirectly to this category
    public function allProducts()
    {
        $ids = $this->allSubCategories()->get()->pluck('id');
        $ids->push($this->id);
        return Product::whereIn('category_id', $ids);
    }

    public function categoryAndChildrenIds()
    {
        $ids = collect($this->id);
        $this->getCategoryIdsRecursicely($ids, $this->allSubCategories()->get());
        return $ids;
    }

    public function getCategoryIdsRecursicely(&$collection, $subCategories)
    {
        foreach($subCategories as $category)
        {
            $collection->push($category->id);
            $this->getCategoryIdsRecursicely($collection, $category->allSubCategories);
        }

    }
}
