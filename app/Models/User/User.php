<?php

namespace App\Models\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $table = 'users';
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