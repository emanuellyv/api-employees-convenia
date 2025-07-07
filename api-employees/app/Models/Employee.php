<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static insert(array $arrayValues)
 * @method static where(string $string, mixed $cpf)
 * @method static create(array $employeeData)
 */
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cpf',
        'city',
        'state',
        'manager_id',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
