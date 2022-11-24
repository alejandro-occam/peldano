<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public $table = 'batchs';

    public $fillable = [
        'id',
        'name',
        'english_name',
        'nomenclature',
        'pvp',
        'is_exempt',
        'id_chapter',
        'id_sage',
        'id_family_sage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'english_name' => 'string',
        'nomenclature' => 'string',
        'pvp' => 'double',
        'is_exempt' => 'boolean',
        'id_chapter' => 'integer',
        'id_sage' => 'string',
        'id_family_sage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'name',
        'english_name',
        'nomenclature',
        'pvp',
        'is_exempt',
        'id_chapter',
        'id_sage',
        'id_family_sage'
    ];
}
