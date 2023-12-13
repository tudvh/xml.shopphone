<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use App\Http\Requests\Admin\Category\CreateCategoryXmlRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use XMLReader;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(5);

        return view('admin.pages.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.pages.category.create');
    }

    public function store(CreateCategoryRequest $request)
    {
        Category::create([
            'name' => str()->ucfirst($request->name),
            'slug' => str()->slug(trim($request->name)),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.categories.index')->with("success", "Thêm danh mục thành công!");
    }

    public function createByXml()
    {
        return view('admin.pages.category.create-by-xml');
    }

    public function storeXml(CreateCategoryXmlRequest $request)
    {
        try {
            $file = $request->file('xml_file')->getRealPath();
            $reader = new XMLReader();
            $reader->open($file);

            while ($reader->read()) {
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->localName == 'category') {
                    $category = new Category();
                    $reader->read();
                    $reader->next('name');
                    $category->name = $reader->readString();
                    $category->slug = str()->slug(trim($category->name));
                    $reader->next('status');
                    $category->status = $reader->readString();
                    $category->save();
                }
            }

            $reader->close();

            return redirect()->route('admin.categories.index')->with("success", "Thêm danh mục thành công!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['xml_file' => $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Category $category)
    {
        return view('admin.pages.category.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => str()->ucfirst($request->name),
            'slug' => str()->slug(trim($request->name)),
            'status' => $request->status,
        ]);

        return redirect()->back()->with("success", "Cập nhật danh mục thành công!");
    }

    public function exportXml()
    {
        $categories = Category::allToXml();
        return response($categories, 200)
            ->header('Content-Type', 'text/xml');
    }
}
