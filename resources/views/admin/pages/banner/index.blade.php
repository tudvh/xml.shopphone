@extends('admin.layouts.main')

@section('title', 'Danh sách banner - 777 Zone Admin')
@section('title-content', 'Danh sách banner')

@section('css')
<link rel="stylesheet" href="{{ url('public/admin/css/banner/index.css') }}">
@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Banner</li>
@stop

@section('content')
<div class="d-flex flex-column gap-4">
    @if (session('success'))
    <div class="alert alert-success m-0">
        {{ session('success') }}
    </div>
    @endif

    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route('admin.banners.create') }}" class="btn btn-success gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm mới</span>
        </a>
        <a href="{{ route('admin.banners.exportXml') }}" class="btn btn-info gap-2" target="_blank">
            <i class="fa-regular fa-file-export"></i>
            <span>Xuất XML</span>
        </a>
        <a href="{{ route('admin.banners.createByXml') }}" class="btn btn-info gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm mới với XML</span>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-secondary">
                <tr>
                    <th>Id</th>
                    <th>Hình ảnh</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($banners as $banner)
                <tr>
                    <th>{{ $banner->id }}</th>
                    <td>
                        <img src="{{ $banner->image ?? '' }}" alt="" class="banner-image">
                    </td>
                    <td>
                        @if($banner->status)
                        <span class='badge bg-success'>Hiển thị</span>
                        @else
                        <span class='badge bg-secondary'>Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                            <a href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}" class="btn btn-primary" title="Cập nhật">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $banners->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@stop