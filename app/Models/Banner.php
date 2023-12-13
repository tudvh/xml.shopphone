<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = "banners";

    protected $fillable = ['image', 'image_object_name', 'status'];

    public static function allToXml()
    {
        $banners = Banner::all();
        $xml = new \SimpleXMLElement('<banners/>');

        foreach ($banners as $banner) {
            $xmlBanner = $xml->addChild('banner');
            $xmlBanner->addChild('id', $banner->id);
            $xmlBanner->addChild('image_url', $banner->image);
            $xmlBanner->addChild('image_object_name', $banner->image_object_name);
            $xmlBanner->addChild('status', $banner->status);
        }

        return $xml->asXML();
    }
}
