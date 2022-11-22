<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarMagazine extends Model
{
    use HasFactory;

    public $table = 'calendars_magazines';

    public $fillable = [
        'number',
        'title',
        'topics',
        'drafting',
        'commercial',
        'output',
        'billing',
        'front_page',
        'id_calendar',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'number' => 'string',
        'title' => 'string',
        'topics' => 'string',
        'drafting' => 'string',
        'commercial' => 'string',
        'output' => 'string',
        'billing' => 'string',
        'front_page' => 'string',
        'id_calendar' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'number',
        'title',
        'topics',
        'drafting',
        'commercial',
        'output',
        'billing',
        'front_page',
        'id_calendar',
    ];
}
