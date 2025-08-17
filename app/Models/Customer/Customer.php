<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\Customer\Customer\EmailCast;
use App\Casts\Customer\Customer\NameCast;

class Customer extends Model
{
    use HasFactory, SoftDeletes, Authenticatable;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
    ];

    protected $casts = [
        'email' => EmailCast::class,
        'name' => NameCast::class,
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $name) {
            $name = str_replace(' ', '%', $name);
            $query->where('name', 'like', "%{$name}%");
        })->when($filters['email'] ?? null, function ($query, $email) {
            $query->where('email', 'like', "%{$email}%");
        });

        return $query;
    }

    public function favorites()
    {
        return $this->hasMany(CustomerFavorite::class);
    }
}