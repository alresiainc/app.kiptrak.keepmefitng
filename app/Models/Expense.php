<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

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

    public function expenseDate(){
        $time = strtotime($this->purchase_date);
        $newformat = date('D, jS M Y',$time);
        return $newformat;
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');  
    }

    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'warehouse_id');  
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');  
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');  
    }
}
