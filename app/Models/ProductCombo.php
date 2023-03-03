<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductCombo extends Model
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

            $code = 'kp-combo' . date("his");
            $randomCodes = static::where('code', 'like', $code.'%')->pluck('code');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));

            do {
                $randomCode = $code.rand(100000, 999999);
            } while ($randomCodes->contains($randomCode));
    
            $model->unique_key = $randomString;
            $model->code = $randomCode;
            // $model->url = 'order-form/'.$model->unique_key;

        });
    }

    
}
