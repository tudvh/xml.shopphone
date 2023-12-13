@extends('admin.layouts.main')

@section('title', 'Thêm mới danh mục với XML - 777 Zone Admin')
@section('title-content', 'Thêm mới danh mục với XML')

@section('css')
@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>
<li class="breadcrumb-item active" aria-current="page">Thêm mới với XML</li>
@stop

@section('content')
<form action="{{ route('admin.categories.storeXml') }}" method="POST" enctype="multipart/form-data">
    <div class="d-flex flex-column gap-4">
        @csrf

        <div class="col-12">
            <input class="btn btn-success ms-auto" type="submit" value="Tạo mới">
        </div>

        <div class="col-12">
            <div class="form-group">
                <label class="form-label fw-bold">Mẫu XML</label>
                <textarea cols="30" rows="10" class="form-control" disabled><categories>
	<category>
		<name>Tai nghe</name>
		<status>0</status>
	</category>
	<category>
		<name>Loa</name>
		<status>0</status>
	</category>
</categories></textarea>
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