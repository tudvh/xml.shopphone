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