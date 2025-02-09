<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerWithdraw extends Model
{
    use HasFactory;

    
    public function customer()
    {
        return $this->belongsTo(Customer::class,'seller_id')->select('id','name','phone', 'withdraw');
    }
}
