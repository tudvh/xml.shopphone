@extends('admin.layouts.main')

@section('title', 'Thêm mới sản phẩm với XML - 777 Zone Admin')
@section('title-content', 'Thêm mới sản phẩm với XML')

@section('css')
@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.products.index') }}">Sản phẩm</a></li>
<li class="breadcrumb-item active" aria-current="page">Thêm mới với XML</li>
@stop

@section('content')
<form action="{{ route('admin.products.storeXml') }}" method="POST" enctype="multipart/form-data">
    <div class="d-flex flex-column gap-4">
        @csrf

        <div class="col-12">
            <input class="btn btn-success ms-auto" type="submit" value="Tạo mới">
        </div>

        <div class="col-12">
            <div class="form-group">
                <label class="form-label fw-bold">Mẫu XML</label>
                <textarea cols="30" rows="10" class="form-control" disabled><products>
	<product>
		<name>Samsung Galaxy A05 128GB</name>
		<category_id>1</category_id>
		<brand_id>2</brand_id>
		<price>3090000</price>
		<quantity>8</quantity>
		<specs>
			<tr>
				<td>Màn hình</td>
				<td>6.56 inch, LCD, HD+, 720 x 1612 Pixels</td>
			</tr>
		</specs>
		<description>
			<p style="text-align: justify;">
				<strong>Samsung Galaxy A05 128GB</strong>
			</p>
		</description>
		<status>1</status>
		<images>
			<image>https://fptshop.com.vn/Uploads/Originals/2023/10/4/638320083063534028_samsung-galaxy-a05-den-4.jpg</image>
		</images>
	</product>
</products></textarea>
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