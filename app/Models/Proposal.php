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
        'discount',
        'commercial_name',
        'language',
        'type_proyect',
        'name_proyect',
        'date_proyect',
        'objetives',
        'proposal',
        'actions',
        'observations',
        'show_discounts',
        'show_inserts',
        'show_invoices',
        'show_pvp',
        'sales_possibilities',
        'id_sector'
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
        'commercial_name' => 'string',
        'language' => 'integer',
        'type_proyect' => 'integer',
        'name_proyect' => 'string',
        'date_proyect' => 'string',
        'objetives' => 'string',
        'proposal' => 'string',
        'actions' => 'string',
        'observations' => 'string',
        'show_discounts' => 'boolean',
        'show_inserts' => 'boolean',
        'show_invoices' => 'boolean',
        'show_pvp' => 'boolean',
        'sales_possibilities' => 'string',
        'id_sector' => 'integer'
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
        'discount',
        'commercial_name',
        'language',
        'type_proyect',
        'name_proyect',
        'date_proyect',
        'objetives',
        'proposal',
        'actions',
        'observations',
        'show_discounts',
        'show_inserts',
        'show_invoices',
        'show_pvp',
        'sales_possibilities',
        'id_sector'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'id_sector');
    }
}
