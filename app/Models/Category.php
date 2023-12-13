<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = ['name', 'slug', 'status'];

    public function brands()
    {
        return $this->hasManyThrough(Brand::class, BrandCategory::class, 'category_id', 'id', 'id', 'brand_id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, BrandCategory::class, 'category_id', 'brand_category_id', 'id', 'id');
    }

    public static function allToXml()
    {
        $categories = Category::all();
        $xml = new \SimpleXMLElement('<categories/>');

        foreach ($categories as $category) {
            $xmlCategory = $xml->addChild('category');
            $xmlCategory->addChild('id', $category->id);
            $xmlCategory->addChild('name', $category->name);
            $xmlCategory->addChild('slug', $category->slug);
            $xmlCategory->addChild('status', $category->status);
        }

        return $xml->asXML();
    }
}
