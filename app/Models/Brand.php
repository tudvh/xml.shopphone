<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = "brands";

    protected $fillable = ['name', 'slug', 'avatar', 'avatar_object_name', 'status'];

    public function brandCategories()
    {
        $this->hasMany(BrandCategory::class, 'brand_id', 'id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, BrandCategory::class, 'brand_id', 'brand_category_id', 'id', 'id');
    }

    public static function allToXml()
    {
        $brands = Brand::all();
        $xml = new \SimpleXMLElement('<brands/>');

        foreach ($brands as $brand) {
            $xmlBrand = $xml->addChild('brand');
            $xmlBrand->addChild('id', $brand->id);
            $xmlBrand->addChild('name', $brand->name);
            $xmlBrand->addChild('slug', $brand->slug);
            $xmlBrand->addChild('avatar_url', $brand->avatar);
            $xmlBrand->addChild('avatar_object_name', $brand->avatar_object_name);
            $xmlBrand->addChild('status', $brand->status);
        }

        return $xml->asXML();
    }
}
