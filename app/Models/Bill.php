<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    public $table = 'bills';

    public $fillable = [
        'id',
        'id_bill_internal',
        'amount',
        'date',
        'observations',
        'num_order',
        'internal_observations',
        'way_to_pay',
        'expiration'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_bill_internal' => 'integer',
        'amount' => 'double',
        'date' => 'string',
        'observations' => 'string',
        'num_order' => 'string',
        'internal_observations' => 'string',
        'way_to_pay' => 'integer',
        'expiration' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'id_bill_internal',
        'amount',
        'date',
        'observations',
        'num_order',
        'internal_observations',
        'way_to_pay',
        'expiration'
    ];
}
