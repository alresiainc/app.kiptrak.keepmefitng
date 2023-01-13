<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SoundNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            
            $string = Str::random(30);
            $randomStrings = static::where('unique_key', 'like', $string.'%')->pluck('unique_key');

            do {
                $randomString = $string.rand(100000, 999999);
            } while ($randomStrings->contains($randomString));
    
            $model->unique_key = $randomString;

        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');  
    }

    // public function getCreatedAtAttribute($value)
    // {
    //     return $date = (int) $value;
    //     $start_date = date('Y-m-d', );
    //     return $start_date;
        
    //     // return \Carbon\Carbon::parse($value->created_at)->diffForHumans();
    // }
}
