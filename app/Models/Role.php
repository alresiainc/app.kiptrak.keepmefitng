<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     $model->unique_key = $model->createUniqueKey(Str::random(30));
        // });

        static::creating(function ($model) {
            
            $string = Str::random(30);
            $randomStrings = static::where('unique_key', 'like', $string.'%')->pluck('unique_key');

            do {
                $randomString = Str::random(30);
            } while ($randomStrings->contains($randomString));
    
            $model->unique_key = $randomString;
            // $model->url = 'order-form/'.$model->unique_key;

        });
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class,'role_permissions');    
    }

    public function hasPermissionById($permission_id) {
        return (bool) $this->permissions->where('id', $permission_id)->count();
     }
}
