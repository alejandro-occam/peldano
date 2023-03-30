<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserObjetive extends Model
{
    use HasFactory;

    public $table = 'users_objetives';

    public $fillable = [
        'id',
        'id_user',
        'obj_print',
        'obj_dig',
        'obj_eve',
        'year'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_user' => 'integer',
        'obj_print' => 'string',
        'obj_dig' => 'string',
        'obj_eve' => 'string',
        'year' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'id_user',
        'obj_print',
        'obj_dig',
        'obj_eve',
        'year'
    ];
}
