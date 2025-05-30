<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Message extends Model
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
    private function createUniqueKey($string)
    {
        if (static::whereUniqueKey($unique_key = $string)->exists()) {
            $random = rand(1000, 9000);
            $unique_key = $string . '' . $random;
            return $unique_key;
        }

        return $string;
    }

    //recipient users
    public function users($recipients)
    {
        $recipients = \unserialize($recipients);
        $users = User::whereIn('id', $recipients)->get();

        return $users;
    }

    public function customers($recipients)
    {

        $recipients = \unserialize($recipients);
        $customers = Customer::whereIn('id', $recipients)->get();

        return $customers;
    }

    public function resolveRecipients()
    {
        $recipients = @\unserialize($this->recipients);

        // Check if unserialize was successful and the result is an array
        if ($recipients === false || !is_array($recipients)) {
            return collect(); // Return an empty collection to avoid errors
        }

        if (isset($this->to) && $this->to === 'employees') {
            return User::whereIn('id', $recipients)->get();
        } else {
            return Customer::whereIn('id', $recipients)->get();
        }
    }
}
