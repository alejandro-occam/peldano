<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultanOrder extends Model
{
    use HasFactory;

    public $table = 'consultants_orders';

    public $fillable = [
        'id_consultant',
        'id_order',
        'percentage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_consultant' => 'integer',
        'id_order' => 'integer',
        'percentage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_consultant',
        'id_order',
        'percentage'
    ];
}
