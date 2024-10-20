<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FormHolder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'staff_assigned_ids' => 'array',
        'staff_attendance' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unique_key = $model->createUniqueKey(Str::random(30));
            // $model->slug = $model->createUniqueSlug(Str::slug($model->name));
            $url = url('/') . '/new-form-link/' . $model->unique_key;
            $embedded_url = url('/') . '/form-embedded/' . $model->unique_key;
            $model->url = 'new-form-link/' . $model->unique_key;
            $model->embedded_tag = '<embed type="text/html" src="' . $embedded_url . '"  width="100%" height="1024px">';
            $model->iframe_tag = '<iframe src="' . $embedded_url . '" width="100%" height="1024px" style="border:0"></iframe>';
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

    //check if unique_key exists
    private function createUniqueSlug($string)
    {
        if (static::whereSlug($slug = $string)->exists()) {
            $random = rand(1000, 9000);
            $slug = $string . '' . $random;
            return $slug;
        }

        return $string;
    }

    public function entries()
    {
        $orders = $this->formOrders;
        $entries_count = 0;
        foreach ($orders as $key => $order) {
            if (isset($order->customer_id)) {
                $entries_count += 1;
            }
        }

        //if parent form has entry
        // if (isset($this->order->customer_id)) {
        //     $entries_count += 1;
        // }
        return $entries_count;
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function formOrders()
    {
        return $this->hasMany(Order::class, 'form_holder_id');
    }

    public function orderbump()
    {
        return $this->belongsTo(OrderBump::class, 'orderbump_id');
    }

    public function upsell()
    {
        return $this->belongsTo(UpSell::class, 'upsell_id');
    }

    //$cat->categories as subcat
    public function formHolders()
    {
        return $this->hasMany(FormHolder::class, 'parent_id', 'id'); //mapping categories to its 'parent_id'
    }

    //can serve as entries
    public function customers()
    {
        return $this->hasMany(Customer::class, 'form_holder_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_assigned_id');
    }

    public function getStaffsAttribute()
    {
        // Decode the JSON and retrieve the IDs
        $staffIds = $this->staff_assigned_ids;

        // Check if the staffIds are set
        if (empty($staffIds)) {
            return collect(); // Return an empty collection if no IDs are present
        }

        // Use the User model to fetch users based on staff IDs
        return User::whereIn('id', $staffIds)->get();
    }

    public function thankYou()
    {
        return $this->belongsTo(ThankYou::class, 'thankyou_id');
    }
}
