<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'unit_price'];

    /**
     * Get the category that owns the Product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
