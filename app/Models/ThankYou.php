<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ThankYou extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unique_key = $model->createUniqueKey(Str::random(30));
            
            $embedded_url = url('/').'/thankYou-embedded/'.$model->unique_key;
            $model->url = 'view-thankyou-templates/'.$model->unique_key;
            $model->embedded_tag = '<embed type="text/html" src="'.$embedded_url.'"  width="100%" height="700">';
            $model->iframe_tag = '<iframe src="'.$embedded_url.'" width="100%" height="700" style="border:0"></iframe>';
            
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
}
