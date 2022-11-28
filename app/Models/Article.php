<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public $table = 'articles';

    public $fillable = [
        'id',
        'name',
        'english_name',
        'pvp',
        'id_batch',
        'is_exempt',
        'id_sage',
        'id_family_sage',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'english_name' => 'string',
        'pvp' => 'double',
        'id_batch' => 'integer',
        'is_exempt' => 'boolean',
        'id_sage' => 'string',
        'id_family_sage' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id',
        'name',
        'english_name',
        'pvp',
        'id_batch',
        'is_exempt',
        'id_sage',
        'id_family_sage'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
