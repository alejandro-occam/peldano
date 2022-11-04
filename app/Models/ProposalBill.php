<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalBill extends Model
{
    use HasFactory;

    public $table = 'proposals_bills';

    public $fillable = [
        'id',
        'id_proposal',
        'id_bill',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_proposal' => 'integer',
        'id_bill' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'id_proposal',
        'id_bill',
    ];
}
