<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBillOrder extends Model
{
    use HasFactory;

    public $table = 'services_bills_orders';

    public $fillable = [
        'id',
        'id_service',
        'id_bill_order',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_service' => 'integer',
        'id_bill_order' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'id_service',
        'id_bill_order',
    ];
}
