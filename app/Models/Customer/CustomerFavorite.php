<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFavorite extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_favorites';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
        'product_id'
    ];

    protected $casts = [
        
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['customer_id'] ?? null, function ($query, $customer_id) {
            $query->where('customer_id', $customer_id);
        })->when($filters['product_id'] ?? null, function ($query, $product_id) {
            $query->where('product_id', $product_id);
        });
        
        return $query;
    }
}