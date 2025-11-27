<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'price', 'compare_price', 'sku', 'barcode', 'track_qty', 'qty', 'status', 'category_id', 'sub__category_id', 'brand_id', 'is_featured', 'short_description', 'shipping_returns', 'related_products'];

    public function product_image()
    {
        return $this->hasMany(Product_Image::class);
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }
}
