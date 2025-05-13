<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1)
 * @method static create(string[] $array)
 * @method static orderBy(string $string, string $string1)
 */
class Manager extends Model
{
    protected $fillable = [
        'name',
        'email'
    ];
}
