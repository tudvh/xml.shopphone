<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AddressService;

class CustomerController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->middleware('admin');

        $this->addressService = $addressService;
    }

    public function index()
    {
        $customers = User::where('role', 'customer')->orderBy('id', 'desc')->paginate(20);

        foreach ($customers as $index => $customer) {
            if ($customer->ward_id) {
                $address = $this->addressService->getDetailByWardId($customer->ward_id);
                $customers[$index]['full_address'] = "{$customer->address}, {$address['districts']['wards']['name']}, {$address['districts']['name']}, {$address['name']}";
            }
        }

        return view('admin.pages.customer.index', compact('customers'));
    }

    public function look(User $customer)
    {
        if ($customer->role != 'customer') {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy tài khoản khách hàng!'
            ], 403);
        }

        $customer->update(['status' => 0]);

        if ($customer->ward_id) {
            $address = $this->addressService->getDetailByWardId($customer->ward_id);
            $customer['full_address'] = "{$customer->address}, {$address['districts']['wards']['name']}, {$address['districts']['name']}, {$address['name']}";
        } else {
            $customer['full_address'] = '';
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Khóa tài khoản thành công!',
            'data' => view('admin.pages.customer.data-tr', compact('customer'))->render()
        ], 201);
    }

    public function unLook(User $customer)
    {
        if ($customer->role != 'customer') {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy tài khoản khách hàng!'
            ], 403);
        }

        $customer->update(['status' => 1]);

        if ($customer->ward_id) {
            $address = $this->addressService->getDetailByWardId($customer->ward_id);
            $customer['full_address'] = "{$customer->address}, {$address['districts']['wards']['name']}, {$address['districts']['name']}, {$address['name']}";
        } else {
            $customer['full_address'] = '';
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Mở khóa tài khoản thành công!',
            'data' => view('admin.pages.customer.data-tr', compact('customer'))->render()
        ], 201);
    }

    public function exportXml()
    {
        $customers = User::allToXml('customer');
        return response($customers, 200)
            ->header('Content-Type', 'text/xml');
    }
}
