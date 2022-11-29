<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; 
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unique_key = $model->createUniqueKey(Str::random(30));

            $account_no = 'kpa-' . date("Ymd") . '-'. date("his");
            $randomStrings = static::where('account_no', 'like', $account_no.'%')->pluck('account_no');

            do {
                // $randomString = $string.rand(100000, 999999);
                $randomString = 'kpa-' . date("Ymd") . '-'. date("his");
            } while ($randomStrings->contains($randomString));
    
            $model->account_no = $randomString;
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
