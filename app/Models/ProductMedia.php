<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_media';

    protected $fillable = ['name', 'product_id', 'media_type', 'media_text', 'product_image'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
