<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPro extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class, 'campaign_id', 'id')->select('id', 'name', 'slug', 'campaign_id', 'new_price', 'old_price', 'type')->orderBy('id', 'DESC');
    }

}
