<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultantProposal extends Model
{
    use HasFactory;

    public $table = 'consultants_proposals';

    public $fillable = [
        'id_consultant',
        'id_proposal',
        'percentage'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_consultant' => 'integer',
        'id_proposal' => 'integer',
        'percentage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_consultant',
        'id_proposal',
        'percentage'
    ];
}
