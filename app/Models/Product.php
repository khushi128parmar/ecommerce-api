<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use LogsActivity, HasFactory; 

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'sku',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

     public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()

        ->logOnly([

            'name',

            'price',

            'stock'
        ])

        ->logOnlyDirty()

        ->setDescriptionForEvent(

            fn(string $eventName) => "Product {$eventName}"
        );
}
}
