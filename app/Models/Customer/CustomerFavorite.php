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

    // Basic filter scope example
    public function scopeFilter($query, array $filters)
    {
        foreach ($filters as $field => $value) {
            if ($value === null || $value === '') continue;
            $query->where($field, $value);
        }
        return $query;
    }
}