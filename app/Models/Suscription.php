<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscription extends Model
{
    use HasFactory;

    public $table = 'suscriptions';

    public $fillable = [
        'id',
        'id_contact',
        'id_article',
        'id_calendar',
        'num',
        'num_finish',
        'payment_method'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_contact' => 'integer',
        'id_article' => 'integer',
        'id_calendar' => 'integer',
        'num' => 'string',
        'num_finish' => 'string',
        'payment_method' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'id_contact',
        'id_article',
        'id_calendar',
        'num',
        'num_finish',
        'payment_method'
    ];
}
