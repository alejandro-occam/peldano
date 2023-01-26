<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $table = 'orders';

    public $fillable = [
        'id_company',
        'id_proposal',
        'is_custom',
        'discount',
        'status',
        'reason_update'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_company' => 'integer',
        'id_proposal' => 'integer',
        'is_custom' => 'boolean',
        'discount' => 'string',
        'status' => 'integer',
        'reason_update' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_company',
        'id_proposal',
        'is_custom',
        'discount',
        'status',
        'reason_update'
    ];
}
