<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    public $table = 'proposals';

    public $fillable = [
        'id_user',
        'id_company',
        'discount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_user' => 'integer',
        'id_company' => 'integer',
        'discount' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_user',
        'id_company',
        'discount'
    ];
}
