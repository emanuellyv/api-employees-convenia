<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array $arrayValues)
 * @method static where(string $string, mixed $key)
 * @method static orderBy(string $string, string $string1)
 * @method static create(array $array)
 */
class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'city',
        'state'
    ];

    public function manager(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
