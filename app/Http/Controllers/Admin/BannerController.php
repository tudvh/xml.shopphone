<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\CreateBannerRequest;
use App\Http\Requests\Admin\Banner\CreateBannerXmlRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Models\Banner;
use App\Services\FirebaseStorageService;
use XMLReader;

class BannerController extends Controller
{
    protected $firebaseStorageService;

    public function __construct(FirebaseStorageService $firebaseStorageService)
    {
        $this->middleware('admin');

        $this->firebaseStorageService = $firebaseStorageService;
    }

    public function index()
    {
        $banners = Banner::orderBy('id', 'desc')->paginate(5);

        return view('admin.pages.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.pages.banner.create');
    }

    public function store(CreateBannerRequest $request)
    {
        $banner = Banner::create([
            'status' => $request->status,
        ]);

        $uploadImageResult = $this->firebaseStorageService->uploadImage($request->file('image'), $banner->id, 'banner');
        $banner->image = $uploadImageResult['full_url'];
        $banner->image_object_name = $uploadImageResult['short_url'];
        $banner->save();

        return redirect()->route('admin.banners.index')->with("success", "Thêm banner thành công!");
    }

    public function createByXml()
    {
        return view('admin.pages.banner.create-by-xml');
    }

    public function storeXml(CreateBannerXmlRequest $request)
    {
        try {
            $file = $request->file('xml_file')->getRealPath();
            $reader = new XMLReader();
            $reader->open($file);

            while ($reader->read()) {
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->localName == 'banner') {
                    $banner = new Banner();
                    $reader->read();
                    $reader->next('image_url');
                    $banner->image = $reader->readString();
                    $reader->next('status');
                    $banner->status = $reader->readString();
                    $banner->save();
                }
            }

            $reader->close();

            return redirect()->route('admin.banners.index')->with("success", "Thêm banner thành công!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['xml_file' => $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Banner $banner)
    {
        return view('admin.pages.banner.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $redirect = redirect()->back()->with("success", "Cập nhật banner thành công!");

        $dataUpdate = [
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($banner->image_object_name) {
                $uploadImageResult = $this->firebaseStorageService->deleteImage($banner->image_object_name);
            }

            $uploadImageResult = $this->firebaseStorageService->uploadImage($request->file('image'), $banner->id, 'banner');
            $dataUpdate['image'] = $uploadImageResult['full_url'];
            $dataUpdate['image_object_name'] = $uploadImageResult['short_url'];

            $redirect = $redirect->with('warning', 'Lưu ý: Hình ảnh sẽ mất một ít thời gian để cập nhật lên hệ thống.');
        }

        $banner->update($dataUpdate);

        return $redirect;
    }

    public function exportXml()
    {
        $banners = Banner::allToXml();
        return response($banners, 200)
            ->header('Content-Type', 'text/xml');
    }
}
