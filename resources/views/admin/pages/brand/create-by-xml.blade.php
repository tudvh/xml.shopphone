@extends('admin.layouts.main')

@section('title', 'Thêm mới thương hiệu với XML - 777 Zone Admin')
@section('title-content', 'Thêm mới thương hiệu với XML')

@section('css')
@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.brands.index') }}">Thương hiệu</a></li>
<li class="breadcrumb-item active" aria-current="page">Thêm mới với XML</li>
@stop

@section('content')
<form action="{{ route('admin.brands.storeXml') }}" method="POST" enctype="multipart/form-data">
    <div class="d-flex flex-column gap-4">
        @csrf

        <div class="col-12">
            <input class="btn btn-success ms-auto" type="submit" value="Tạo mới">
        </div>

        <div class="col-12">
            <div class="form-group">
                <label class="form-label fw-bold">Mẫu XML</label>
                <textarea cols="30" rows="10" class="form-control" disabled><brands>
	<brand>
		<name>Nokia</name>
		<avatar_url>https://cdn.tgdd.vn/Brand/1/logo-iphone-220x48.png</avatar_url>
		<avatar_object_name/>
		<status>0</status>
	</brand>
	<brand>
		<name>Honor</name>
		<avatar_url>https://cdn.tgdd.vn/Brand/1/samsungnew-220x48-1.png</avatar_url>
		<avatar_object_name/>
		<status>0</status>
	</brand>
</brands></textarea>
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                <label for="xml_file" class="form-label fw-bold">File XML <span class="text-danger">*</span></label>
                <input type="file" class="form-control @if($errors->has('xml_file')) is-invalid @endif" id="xml_file" name="xml_file" accept=".xml">
                @if ($errors->has('xml_file'))
                <small class="text-danger">{{ $errors->first('xml_file') }}</small>
                @endif
            </div>
        </div>
    </div>
</form>
@stop

@section('js')
@stop