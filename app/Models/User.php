<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\UserRole;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unique_key = $model->createUniqueKey(Str::random(30));
        });
    }

    //check if unique_key exists
    private function createUniqueKey($string){
        if (static::whereUniqueKey($unique_key = $string)->exists()) {
            $random = rand(1000, 9000);
            $unique_key = $string.''.$random;
            return $unique_key;
        }

        return $string;
    }

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

    public function shortName($name) {
        $shortName = "";
    
        $names = explode(" ", $name);
    
        foreach ($names as $w) {
            $shortName .= $w[0];
        }
        return $shortName;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');  
    }

    public function roles() {
        return $this->belongsToMany(Role::class,'user_roles');
    }

    public function hasAnyRole($user_id) {
        return (bool) UserRole::where('user_id', $user_id)->count();
    }

    public function role($user_id) {
        return UserRole::where('user_id', $user_id)->first();
    }

}
