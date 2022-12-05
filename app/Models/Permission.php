<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Permission extends Model
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

    //$perm->permissions, for sub_permissions
    public function permissions() {
        return $this->hasMany(Permission::class, 'parent_id', 'id'); //mapping permissions to its 'parent_id'
       }
}
