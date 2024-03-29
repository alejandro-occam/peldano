<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;

    public $table = 'role_user';

    public $fillable = [
        'id_role',
        'id_user',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_role' => 'integer',
        'id_user' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_role',
        'id_user',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
