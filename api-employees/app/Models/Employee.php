<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static insert(array $arrayValues)
 */
class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'city',
        'state',
        'manager_id'
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
