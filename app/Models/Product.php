<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getRouteKeyName() {
        return 'slug';
    }
    public function image()
    {
        return $this->hasOne(Productimage::class, 'product_id')->select('id','image','product_id');
    }
    public function images()
    {
        return $this->hasMany(Productimage::class, 'product_id')->select('id','image','product_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id')->select('id');
    }
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id')->select('id','name','slug');
    }
    public function subcategory()
    {
        return $this->hasOne(Subcategory::class,'id','subcategory_id')->select('id','subcategoryName','slug');
    }
    public function childcategory()
    {
        return $this->hasOne(Childcategory::class,'id','childcategory_id')->select('id','childcategoryName','slug');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class,'id','brand_id')->select('id','name','slug');
    }

    public function variable()
    {
        return $this->hasOne(ProductVariable::class)->where('stock','>',0);
    }

    public function variables()
    {
        return $this->hasMany(ProductVariable::class)->where('stock','>',0);
    }
}
