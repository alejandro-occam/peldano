<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public $table = 'companies';

    public $fillable = [
        'name',
        'nif',
        'address',
        'id_hubspot',
        'id_sage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'nif' => 'string',
        'address' => 'string',
        'id_hubspot' => 'string',
        'id_sage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name',
        'nif',
        'address',
        'id_hubspot',
        'id_sage'
    ];
}
