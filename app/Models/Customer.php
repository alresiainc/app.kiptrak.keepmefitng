<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Helpers\Helper;

class Customer extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();


        // Automatically fill fields on creation
        static::creating(function ($model) {
            // Call the autoFillFields method to fill in the fields intelligently
            $data = $model->autoFillFields($model->data);

            $model->unique_key = $model->createUniqueKey(Str::random(30));

            // Helper function to determine if a value is considered "empty" (null or empty string)
            $isEmpty = function ($value) {
                return $value === null || $value === '';
            };

            // Set fields if not already set, using the auto-filled data or default values
            $model->firstname = !$isEmpty($model->firstname) ? $model->firstname : ($data['firstname'] ?? 'firstname');
            $model->lastname = !$isEmpty($model->lastname) ? $model->lastname : ($data['lastname'] ?? 'lastname');
            $model->phone_number = !$isEmpty($data['phone_number']) ? $data['phone_number'] : $model->phone_number;
            $model->whatsapp_phone_number = !$isEmpty($model->whatsapp_phone_number) ? $model->whatsapp_phone_number : ($data['whatsapp_phone_number'] ?? '');
            $model->email = !$isEmpty($model->email) ? $model->email : ($data['email'] ?? 'default@site.com');
            $model->city = !$isEmpty($model->city) ? $model->city : ($data['city'] ?? '');
            $model->state = !$isEmpty($model->state) ? $model->state : ($data['state'] ?? '');
            $model->delivery_address = !$isEmpty($model->delivery_address) ? $model->delivery_address : ($data['delivery_address'] ?? '');
            $model->delivery_duration = !$isEmpty($model->delivery_duration) ? $model->delivery_duration : ($data['delivery_duration'] ?? '');
        });


        static::created(function ($model) {
            // If the firstname is still empty after creation, set it as 'Customer <inserted_id>'
            if ($model->firstname == 'firstname') {
                $model->firstname = 'Customer ' . $model->id;
                $model->save();
            }

            if ($model->email == 'default@site.com') {
                // Generate a default email using a combination of firstname, lastname, and the model ID
                $defaultEmail = strtolower($model->firstname . '.' . $model->lastname . $model->id . '@example.com');
                $model->email = $defaultEmail;
                $model->save();
            }
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

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function deliveredOrders()
    {
        return $this->hasMany(Order::class)->where('status', 'delivered_and_remitted');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    /**
     * Automatically fill fields based on the available data.
     */
    public function autoFillFields(array $data)
    {
        // Fields that the model expects
        $requiredFields = [
            'firstname',
            'lastname',
            'phone_number',
            'whatsapp_phone_number',
            'email',
            'city',
            'state',
            'delivery_address',
            'delivery_duration',
        ];

        // Match fields intelligently
        $matchedFields = (new Helper())->matchFormFields($requiredFields, array_keys($data), false, false);

        $modelFields = [];
        foreach ($matchedFields as $field => $matchedField) {
            if ($matchedField && empty($this->$field)) {
                // If the matched field exists and the model field is empty, fill it with the value from $data
                $modelFields[$field] = $data[$matchedField];
            }
        }
        return $modelFields;
    }
}
