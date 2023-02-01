<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Task extends Model
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

    public function sameMonth() {
        $start_month = \Carbon\Carbon::parse($this->start_date)->format('M');
        $end_month = \Carbon\Carbon::parse($this->end_date)->format('M');
        if ($start_month == $end_month) {
            return true;
        }
        return false;
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');    
    }

    public function assignedTo() {
        return $this->belongsTo(User::class, 'assigned_to');    
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');    
    }

    public function category() {
        return $this->belongsTo(TaskCategory::class, 'category_id');    
    }

    public function remarks() {
        return $this->hasMany(TaskRemark::class, 'task_id');    
    }

    //for testing purpose only
    function sortLargest($X, $Y)
    {
        // append Y to X
        $XY = $Y.$X;
        
        // then append X to Y
        $YX = $X.$Y;
        
        // check for the greater number
        // return strcmp($XY, $YX) > 0 ? 1: 0;
        if ($X==$Y) return 0;

        return ($X>$Y) ? -1 : 1; //for largest possible
    }

    function sortSmallest($X, $Y)
    {
        // append Y to X
        $XY = $Y.$X;
        
        // then append X to Y
        $YX = $X.$Y;
        
        // check for the greater number
        // return strcmp($XY, $YX) > 0 ? 1: 0;
        if ($X==$Y) return 0;

        return ($X<$Y) ? -1 : 1; //for smallest possible
    }

    public function getLagestOrSmallest($arr, $largest)
    {
        // Sort the array using a custom-defined my_sort function
        // usort($arr, "my_sort");
        if ($largest) {
            usort($arr, array( $this, 'sortLagest'));
        } else {
            usort($arr, array( $this, 'sortSmallest'));
        }
        
        for ($i = 0; $i < count($arr) ; $i++ ) {
            echo $arr[$i];
        }
            
    }
 

}
