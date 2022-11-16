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
        'paid_out',
        'amount',
        'iva',
        'id_sage',
        'id_order'
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
        'paid_out' => 'integer',
        'amount' => 'double',
        'iva' => 'double',
        'id_sage' => 'string',
        'id_order' => 'integer'
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
        'paid_out',
        'amount',
        'iva',
        'id_sage',
        'id_order'
    ];
}
