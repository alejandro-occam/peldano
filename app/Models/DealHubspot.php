<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealHubspot extends Model
{
    use HasFactory;

    public $table = 'deal_hubspot';

    public $fillable = [
        'name',
        'id_hubspot',
        'id_contact',
        'id_company',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'id_hubspot' => 'integer',
        'id_contact' => 'integer',
        'id_company' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name',
        'id_hubspot',
        'id_contact',
        'id_company',
    ];
}
