<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOrder extends Model
{
    use HasFactory;

    public $table = 'bills_orders';

    public $fillable = [
        'number',
        'date',
        'expiration',
        'way_to_pay',
        'observations',
        'num_order',
        'internal_observations',
        'amount',
        'iva',
        'id_sage',
        'id_order',
        'receipt_order_sage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'number' => 'string',
        'date' => 'string',
        'expiration' => 'integer',
        'way_to_pay' => 'integer',
        'observations' => 'string',
        'num_order' => 'string',
        'internal_observations' => 'string',
        'amount' => 'double',
        'iva' => 'double',
        'id_sage' => 'string',
        'id_order' => 'integer',
        'receipt_order_sage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'number',
        'date',
        'expiration',
        'way_to_pay',
        'observations',
        'num_order',
        'internal_observations',
        'amount',
        'iva',
        'id_sage',
        'id_order',
        'receipt_order_sage'
    ];
}
