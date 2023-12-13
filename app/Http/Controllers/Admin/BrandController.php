<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\CreateBrandRequest;
use App\Http\Requests\Admin\Brand\CreateBrandXmlRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Models\Brand;
use App\Services\FirebaseStorageService;
use XMLReader;

class BrandController extends Controller
{
    protected $firebaseStorageService;

    public function __construct(FirebaseStorageService $firebaseStorageService)
    {
        $this->middleware('admin');

        $this->firebaseStorageService = $firebaseStorageService;
    }

    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(5);

        return view('admin.pages.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.pages.brand.create');
    }

    public function store(CreateBrandRequest $request)
    {
        $brand = Brand::create([
            'name' => str()->ucfirst($request->name),
            'slug' => str()->slug(trim($request->name)),
            'status' => $request->status,
        ]);

        $uploadImageResult = $this->firebaseStorageService->uploadImage($request->file('image'), $brand->id, 'brand');
        $brand->avatar = $uploadImageResult['full_url'];
        $brand->avatar_object_name = $uploadImageResult['short_url'];
        $brand->save();

        return redirect()->route('admin.brands.index')->with("success", "Thêm thương hiệu thành công!");
    }

    public function createByXml()
    {
        return view('admin.pages.brand.create-by-xml');
    }

    public function storeXml(CreateBrandXmlRequest $request)
    {
        try {
            $file = $request->file('xml_file')->getRealPath();
            $reader = new XMLReader();
            $reader->open($file);

            while ($reader->read()) {
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->localName == 'brand') {
                    $brand = new Brand();
                    $reader->read();
                    $reader->next('name');
                    $brand->name = $reader->readString();
                    $brand->slug = str()->slug(trim($brand->name));
                    $reader->next('avatar_url');
                    $brand->avatar = $reader->readString();
                    $reader->next('avatar_object_name');
                    $brand->avatar_object_name = $reader->readString();
                    $reader->next('status');
                    $brand->status = $reader->readString();
                    $brand->save();
                }
            }

            $reader->close();

            return redirect()->route('admin.brands.index')->with("success", "Thêm thương hiệu thành công!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['xml_file' => $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Brand $brand)
    {
        return view('admin.pages.brand.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $redirect = redirect()->back()->with("success", "Cập nhật thông tin thương hiệu thành công!");

        $dataUpdate = [
            'name' => str()->ucfirst($request->name),
            'slug' => str()->slug(trim($request->name)),
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($brand->avatar_object_name) {
                $uploadImageResult = $this->firebaseStorageService->deleteImage($brand->avatar_object_name);
            }

            $uploadImageResult = $this->firebaseStorageService->uploadImage($request->file('image'), $brand->id, 'brand');
            $dataUpdate['avatar'] = $uploadImageResult['full_url'];
            $brand['avatar_object_name'] = $uploadImageResult['short_url'];

            $redirect = $redirect->with('warning', 'Lưu ý: Hình ảnh sẽ mất một ít thời gian để cập nhật lên hệ thống.');
        }

        $brand->update($dataUpdate);

        return $redirect;
    }

    public function exportXml()
    {
        $brands = Brand::allToXml();
        return response($brands, 200)
            ->header('Content-Type', 'text/xml');
    }
}
