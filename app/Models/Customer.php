<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $guard = 'customer';
    protected $fillable = [
        'name', 'email', 'password','address','phone','status',
    ];
    protected $hidden = [
      'password', 'remember_token',
    ];

    public function cust_area()
    {
        return $this->belongsTo(District::class,'area');
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'customer_id');
    }
    public function withdraws()
    {
        return $this->hasMany(SellerWithdraw::class, 'seller_id','id')->latest();
    }
    
    public function seller_orders()
    {
        return $this->hasMany(Order::class,'reseller_id')->where('order_status',6);
    }
}
