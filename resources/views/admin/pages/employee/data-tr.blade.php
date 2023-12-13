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