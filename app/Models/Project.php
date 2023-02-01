<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
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

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');    
    }

    public function assignedTo() {
        return $this->belongsTo(User::class, 'assigned_to');    
    }

    public function tasks() {
        return $this->hasMany(Task::class, 'project_id');    
    }
}
