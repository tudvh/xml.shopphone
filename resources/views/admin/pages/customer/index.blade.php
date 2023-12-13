@extends('admin.layouts.main')

@section('title', 'Danh sách khách hàng - 777 Zone Admin')
@section('title-content', 'Danh sách khách hàng')

@section('css')
<link rel="stylesheet" href="{{ url('public/admin/css/customer/index.css') }}">
@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Khách hàng</li>
@stop

@section('content')
<div class="d-flex flex-column gap-4">
    @if (session('success'))
    <div class="alert alert-success m-0">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger m-0">
        {{ session('error') }}
    </div>
    @endif

    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route('admin.customers.exportXml') }}" class="btn btn-info gap-2" target="_blank">
            <i class="fa-regular fa-file-export"></i>
            <span>Xuất XML</span>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-secondary">
                <tr>
                    <th>Id</th>
                    <th>Ảnh đại diện</th>
                    <th>Họ và tên</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr id="tr-{{ $customer->id }}">
                    <th>{{ $customer->id }}</th>
                    <td>
                        <img src="{{ $customer->avatar ?? 'https://storage.googleapis.com/laravel-img.appspot.com/user/customer-default.png' }}" alt="" class="customer-avatar">
                    </td>
                    <td>{{ $customer->full_name }}</td>
                    <td>{{ $customer->phone_number }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer['full_address'] }}</td>
                    <td>
                        @if($customer->status)
                        <span class='badge bg-success'>Hoạt động</span>
                        @else
                        <span class='badge bg-danger'>Bị cấm</span>
                        @endif
                    </td>
                    <td>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                            @if($customer->status)
                            <button class='btn btn-danger' onclick="lookCustomer('{{ $customer->id }}')" title="Khóa tài khoản">
                                <i class="fa-regular fa-lock"></i>
                            </button>
                            @else
                            <button class='btn btn-success' onclick="unLookCustomer('{{ $customer->id }}')" title="Mở khóa tài khoản">
                                <i class="fa-regular fa-lock-open"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@stop

@section('js')
<script>
    const lookCustomer = async id => {
        const confirmResult = await Swal.fire({
            title: 'Bạn chắc chứ?',
            text: 'Bạn có chắc muốn khóa tài khoản này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Chắc chắn!',
            cancelButtonText: 'Hủy!',
        })

        if (confirmResult.isConfirmed) {
            const trElement = document.querySelector(`#tr-${id}`)

            try {
                const response = await $.ajax({
                    type: 'GET',
                    url: `${rootURL}/admin/customers/${id}/look`,
                })

                await Swal.fire({
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: true,
                })

                trElement.innerHTML = response.data
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: error.message,
                    showConfirmButton: false,
                })
            }
        }
    }

    const unLookCustomer = async id => {
        const confirmResult = await Swal.fire({
            title: 'Bạn chắc chứ?',
            text: 'Bạn có chắc muốn mở khóa tài khoản này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Chắc chắn!',
            cancelButtonText: 'Hủy!',
        })

        if (confirmResult.isConfirmed) {
            const trElement = document.querySelector(`#tr-${id}`)

            try {
                const response = await $.ajax({
                    type: 'GET',
                    url: `${rootURL}/admin/customers/${id}/un-look`,
                })

                await Swal.fire({
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: true,
                })

                trElement.innerHTML = response.data
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: error.message,
                    showConfirmButton: false,
                })
            }
        }
    }
</script>
@stop