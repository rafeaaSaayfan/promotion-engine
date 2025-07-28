<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'salience',
        'stackable',
        'condition',
        'action',
    ];

    protected $casts = [
        'condition' => 'array', 
        'action' => 'array',   
        'stackable' => 'boolean',
    ];

    // Order by salience
    public function scopeOrderBySalience($query)
    {
        return $query->orderBy('salience', 'asc');
    }
}
