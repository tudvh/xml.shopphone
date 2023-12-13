<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "accounts";

    protected $fillable = ['full_name', 'phone_number', 'email', 'avatar', 'username', 'password', 'province_id', 'district_id', 'ward_id', 'address', 'role', 'status', 'google_id', 'verification_token', 'last_email_sent_at'];

    protected $hidden = ['password', 'verification_token', 'last_email_sent_at'];

    protected $casts = [
        'last_email_sent_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public static function allToXml($role)
    {
        $customers = User::where('role', $role)->get();
        $xml = new \SimpleXMLElement("<{$role}s/>");

        foreach ($customers as $customer) {
            $xmlCustomer = $xml->addChild("{$role}");
            $xmlCustomer->addChild('id', $customer->id);
            $xmlCustomer->addChild('full_name', $customer->full_name);
            $xmlCustomer->addChild('phone_number', $customer->phone_number);
            $xmlCustomer->addChild('email', $customer->email);
            $xmlCustomer->addChild('username', $customer->username);
            $xmlCustomer->addChild('avatar_url', $customer->avatar);
            $xmlCustomer->addChild('province_id', $customer->province_id);
            $xmlCustomer->addChild('district_id', $customer->district_id);
            $xmlCustomer->addChild('ward_id', $customer->ward_id);
            $xmlCustomer->addChild('address', $customer->address);
            $xmlCustomer->addChild('status', $customer->status);
            $xmlCustomer->addChild('google_id', $customer->google_id);
        }

        return $xml->asXML();
    }
}
