<?php

namespace App\Models;

use App\Models\Traits\Method\ProductMethod;
use App\Models\Traits\Scope\ProductScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, ProductScope, ProductMethod;

    protected $fillable = [
        'user_id',
        'name', 'price', 'purchase_price',
        'quantity', 'details','product_box', 'sku',
        'is_promotion', 'promotion_price', 'run_continuously',
        'promotion_start_date', 'promotion_end_date', 'product_stock_owner',
        'vendor_id', 'stock_limit',
        'shipping_type_id',
        'shipping_cost',
        'is_active'];

    public function scopeSearchByName($query, $name)
    {
        if (!is_null($name)) {
            return $query->where('name', 'like', '%'.$name.'%');
        }
        return $query;
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function shippingType()
    {
        return $this->belongsTo(ShippingType::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id')->active(1)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
