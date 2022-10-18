<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $table = 'contacts';

    public $fillable = [
        'name',
        'surnames',
        'email',
        'phone',
        'id_company'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'surnames' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'id_company' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name',
        'surnames',
        'email',
        'phone',
        'id_company'
    ];
}
