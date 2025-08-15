<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'password'
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