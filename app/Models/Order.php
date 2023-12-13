<?php

namespace App\Models;

use App\Services\AddressService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'email',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'pay_method',
        'pay_id',
        'pay_status',
        'ship_fee',
        'note',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function totalPrice()
    {
        $orderDetails = $this->orderDetails;
        $total = 0;
        foreach ($orderDetails as $order) {
            $total += $order->price * $order->quantity;
        }
        $total += $this->ship_fee;
        return $total;
    }

    public function nameAddress()
    {
        $adrS = new AddressService();
        return $adrS->getNameAdress($this->ward_id);
    }

    public static function allToXml()
    {
        $orders = Order::all();
        $xml = new \SimpleXMLElement('<banners/>');

        foreach ($orders as $order) {
            $xmlOrder = $xml->addChild('order');
            $xmlOrder->addChild('id', $order->id);
            $xmlOrder->addChild('user_id', $order->user_id);
            $xmlOrder->addChild('name', $order->name);
            $xmlOrder->addChild('phone_number', $order->phone_number);
            $xmlOrder->addChild('email', $order->email);
            $xmlOrder->addChild('province_id', $order->province_id);
            $xmlOrder->addChild('district_id', $order->district_id);
            $xmlOrder->addChild('ward_id', $order->ward_id);
            $xmlOrder->addChild('address', $order->address);
            $xmlOrder->addChild('pay_method', $order->pay_method);
            $xmlOrder->addChild('pay_id', $order->pay_id);
            $xmlOrder->addChild('pay_status', $order->pay_status);
            $xmlOrder->addChild('ship_fee', $order->ship_fee);
            $xmlOrder->addChild('status', $order->status);

            $xmlDetails = $xmlOrder->addChild('orderDetails');
            foreach ($order->orderDetails as $detail) {
                $xmlDetail = $xmlDetails->addChild('orderDetail');
                $xmlDetail = $detail->toXml($xmlDetail);
            }
        }

        return $xml->asXML();
    }
}
