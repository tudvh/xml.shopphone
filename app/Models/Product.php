<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = ['brand_category_id', 'name', 'slug', 'price', 'quantity', 'specs', 'description', 'status'];

    public function category()
    {
        return $this->hasOneThrough(Category::class, BrandCategory::class, 'id', 'id', 'brand_category_id', 'category_id');
    }

    public function brand()
    {
        return $this->hasOneThrough(Brand::class, BrandCategory::class, 'id', 'id', 'brand_category_id', 'brand_id');
    }

    public function brandCategory()
    {
        return $this->hasOne(BrandCategory::class, 'id', 'brand_category_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'id');
    }

    public static function allToXml()
    {
        $products = Product::all();
        $xml = new \SimpleXMLElement('<products/>');

        foreach ($products as $product) {
            $xmlProduct = $xml->addChild('product');
            $xmlProduct->addChild('id', $product->id);
            $xmlProduct->addChild('name', $product->name);
            $xmlProduct->addChild('slug', $product->slug);
            $xmlProduct->addChild('category_id', $product->brandCategory->category_id);
            $xmlProduct->addChild('brand_id', $product->brandCategory->brand_id);
            $xmlProduct->addChild('price', $product->price);
            $xmlProduct->addChild('quantity', $product->quantity);
            $xmlProduct->addChild('specs', $product->specs);
            $xmlProduct->addChild('description', $product->description);
            $xmlProduct->addChild('status', $product->status);

            $xmlImages = $xmlProduct->addChild('images');
            foreach ($product->productImages as $image) {
                $xmlImages->addChild('image', $image->link);
            }
        }

        return $xml->asXML();
    }
}
