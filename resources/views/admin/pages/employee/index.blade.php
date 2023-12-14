@extends('admin.layouts.main')

@section('title', 'Danh sách nhân viên - 777 Zone Admin')
@section('title-content', 'Danh sách nhân viên')

@section('css')
<link rel="stylesheet" href="{{ url('public/admin/css/employee/index.css') }}">
@stop

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Nhân viên</li>
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
        <a href="{{ route('admin.employees.create') }}" class="btn btn-success gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm mới</span>
        </a>
        <a href="{{ route('admin.employees.exportXml') }}" class="btn btn-info gap-2" target="_blank">
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
                    <th>Username</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr id="tr-{{ $employee->id }}">
                    <th>{{ $employee->id }}</th>
                    <td>
                        <img src="{{ $employee->avatar ?? 'https://storage.googleapis.com/laravel-img.appspot.com/user/employee-default.png' }}" alt="" class="employee-avatar">
                    </td>
                    <td>{{ $employee->full_name }}</td>
                    <td>{{ $employee->phone_number }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->username }}</td>
                    <td>{{ $employee['full_address'] }}</td>
                    <td>
                        @if($employee->status)
                        <span class='badge bg-success'>Hoạt động</span>
                        @else
                        <span class='badge bg-danger'>Bị cấm</span>
                        @endif
                    </td>
                    <td>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                            @if($employee->status)
                            <button class='btn btn-danger' onclick="lookCustomer('{{ $employee->id }}')" title="Khóa tài khoản">
                                <i class="fa-regular fa-lock"></i>
                            </button>
                            @else
                            <button class='btn btn-success' onclick="unLookCustomer('{{ $employee->id }}')" title="Mở khóa tài khoản">
                                <i class="fa-regular fa-lock-open"></i>
                            </button>
                            @endif
                            <button class='btn btn-primary' onclick="resetPassword('{{ $employee->id }}')" title="Đặt lại mật khẩu">
                                <i class="fa-regular fa-arrow-rotate-left"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $employees->withQueryString()->links('pagination::bootstrap-5') }}
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
                    url: `${rootURL}/admin/employees/${id}/look`,
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
                    url: `${rootURL}/admin/employees/${id}/un-look`,
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

    const resetPassword = async (id) => {
        Swal.fire({
            title: "Bạn chắc chắn chứ?",
            text: `Bạn có thực sự muốn đặt lại mật khẩu cho tài khoản này không!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya sure, chắc chắn rồi!",
            cancelButtonText: "Không bé ơi!",
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = `${rootURL}/admin/employees/${id}/reset-password`
            }
        });
    }
</script>
@stop