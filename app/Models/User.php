<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Scopes\SoftDeleteUserScope;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'email',
        'password',
        'name',
        'surname',
        'user',
        'id_position',
        'extension',
        'mobile',
        'commission',
        'active',
        'soft_delete'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roleUser()
    {
        return $this->hasOne(RoleUser::class, 'id_user');
    }

    public function getNameUser($id){
        $user = User::find($id);
        return $user->name;
    }

    public function getFirstLetterName($id){
        $user = User::find($id);
        return substr($user->name,0,1);
    }

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SoftDeleteUserScope);
    }
}
