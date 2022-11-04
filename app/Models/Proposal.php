<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    public $table = 'proposals';

    public $fillable = [
        'id',
        'id_proposal_custom',
        'id_user',
        'id_contact',
        'discount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_proposal_custom' => 'integer',
        'id_user' => 'integer',
        'id_contact' => 'integer',
        'discount' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'id_proposal_custom',
        'id_user',
        'id_contact',
        'discount'
    ];
}
