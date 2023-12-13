<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\CreateProductXmlRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\BrandCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\FirebaseStorageService;
use SimpleXMLElement;

class ProductController extends Controller
{
    protected $firebaseStorageService;

    public function __construct(FirebaseStorageService $firebaseStorageService)
    {
        $this->middleware('admin');

        $this->firebaseStorageService = $firebaseStorageService;
    }

    public function index(Request $request)
    {
        $searchKey = $request->input('key');
        $category = $request->input('category');
        $brand = $request->input('brand');
        $status = $request->input('status');

        $products = Product::query();

        if ($searchKey != null) {
            $products = $products->where('name', 'like', '%' . $searchKey . '%');
        }
        if ($category != null) {
            $products = $products->whereHas('brandCategory', function ($query) use ($category) {
                return $query->where('category_id', $category);
            });
        }
        if ($brand != null) {
            $products = $products->whereHas('brandCategory', function ($query) use ($brand) {
                return $query->where('brand_id', $brand);
            });
        }
        if ($status != null) {
            $products = $products->where('status', $status);
        }

        $products = $products->orderBy('id', 'desc')->paginate(20);

        return view('admin.pages.product.index', compact('products'));
    }

    public function create()
    {
        return view('admin.pages.product.create');
    }

    public function store(CreateProductRequest $request)
    {
        $product = Product::create([
            'brand_category_id' => BrandCategory::firstOrCreate(['category_id' => $request->category, 'brand_id' => $request->brand])->id,
            'name' => str()->ucfirst($request->name),
            'slug' => str()->slug(trim($request->name)),
            'price' => $request->price,
            'quantity' => $request->quantity,
            'specs' => $request->input('specs'),
            'description' => $request->input('description'),
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            $uploadImagesResult = $this->firebaseStorageService->uploadManyImages($request->file('images'), "product/{$product->id}");

            $productImages = collect($uploadImagesResult)->map(function ($image) use ($product) {
                return new ProductImage([
                    'product_id' => $product->id,
                    'link' => $image['full_url'],
                    'type' => 'firebase'
                ]);
            });

            $product->productImages()->saveMany($productImages);
        }

        return redirect()->route('admin.products.index')->with("success", "Thêm sản phẩm thành công!");
    }

    public function createByXml()
    {
        return view('admin.pages.product.create-by-xml');
    }

    public function storeXml(CreateProductXmlRequest $request)
    {
        try {
            $xmlContent = File::get($request->file('xml_file')->getRealPath());
            $xmlObject = new SimpleXMLElement($xmlContent);

            foreach ($xmlObject->product as $product) {
                $productModel = Product::create([
                    'brand_category_id' => BrandCategory::firstOrCreate(['category_id' => $product->category_id, 'brand_id' => $product->brand_id])->id,
                    'name' => str()->ucfirst($product->name),
                    'slug' => str()->slug(trim($product->name)),
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'specs' => $product->specs,
                    'description' => $product->description,
                    'status' => $product->status,
                ]);

                $productImagesModel = collect($product->images->image)->map(function ($image) use ($productModel) {
                    return new ProductImage([
                        'product_id' => $productModel->id,
                        'link' => $image,
                    ]);
                });

                $productModel->productImages()->saveMany($productImagesModel);
            }

            return redirect()->route('admin.products.index')->with("success", "Thêm sản phẩm thành công!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['xml_file' => $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        return view('admin.pages.product.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $redirect = redirect()->back()->with("success", "Cập nhật thông tin sản phẩm thành công!");

        $product->update([
            'brand_category_id' => BrandCategory::firstOrCreate(['category_id' => $request->category, 'brand_id' => $request->brand])->id,
            'name' => str()->ucfirst($request->name),
            'slug' => str()->slug(trim($request->name)),
            'price' => $request->price,
            'quantity' => $request->quantity,
            'specs' => $request->input('specs'),
            'description' => $request->input('description'),
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            $this->firebaseStorageService->deleteAllImagesInFolder("product/{$product->id}");
            $product->productImages()->delete();

            $uploadImagesResult = $this->firebaseStorageService->uploadManyImages($request->file('images'), "product/{$product->id}");
            $productImages = collect($uploadImagesResult)->map(function ($image) use ($product) {
                return new ProductImage([
                    'product_id' => $product->id,
                    'link' => $image['full_url'],
                    'type' => 'firebase'
                ]);
            });

            $product->productImages()->saveMany($productImages);
            $redirect = $redirect->with('warning', 'Lưu ý: Hình ảnh sẽ mất một ít thời gian để cập nhật lên hệ thống.');
        }

        return $redirect;
    }

    public function exportXml()
    {
        $products = Product::allToXml();
        return response($products, 200)
            ->header('Content-Type', 'text/xml');
    }
}
