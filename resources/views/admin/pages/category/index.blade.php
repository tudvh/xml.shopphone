@extends('admin.layouts.main')

@section('title', 'Danh sách danh mục - 777 Zone Admin')
@section('title-content', 'Danh sách danh mục')

@section('css')
<link rel="stylesheet" href="{{ url('public/admin/css/category/index.css') }}">
@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Danh mục</li>
@stop

@section('content')
<div class="d-flex flex-column gap-4">
    @if (session('success'))
    <div class="alert alert-success m-0">
        {{ session('success') }}
    </div>
    @endif

    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm mới</span>
        </a>
        <a href="{{ route('admin.categories.exportXml') }}" class="btn btn-info gap-2" target="_blank">
            <i class="fa-regular fa-file-export"></i>
            <span>Xuất XML</span>
        </a>
        <a href="{{ route('admin.categories.createByXml') }}" class="btn btn-info gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm mới với XML</span>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-secondary">
                <tr>
                    <th>Id</th>
                    <th>Tên danh mục</th>
                    <th>Số lượng sản phẩm</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <th>{{ $category->id }}</th>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->products->count() }}</td>
                    <td>
                        @if($category->status)
                        <span class='badge bg-success'>Hiển thị</span>
                        @else
                        <span class='badge bg-secondary'>Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                            <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}" class="btn btn-primary" title="Cập nhật">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@stop