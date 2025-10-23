<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'street',
        'postal_code',
    ];

    /**
     * Define o relacionamento Many-to-Many com User.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_addresses');
    }
}
