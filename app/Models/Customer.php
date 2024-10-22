<?php

namespace App\Models;

use App\Helpers\FieldMatcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;

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
            // Define the variations for each field
            $fieldVariations = [
                'firstname' => ['first_name', 'firstname', 'name', 'full_name', 'first', 'given_name', 'forename'],
                'lastname' => ['last_name', 'lastname', 'surname', 'family_name', 'second_name', 'last', 'surname_name'],
                'phone_number' => ['phone_number', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'phoneNumber', 'cell', 'cellphone', 'cell_number', 'telephone', 'tel_number'],
                'whatsapp_phone_number' => ['contact', 'whatsapp_number', 'whatsapp_number', 'whatsapp', 'whatsApp', 'phone', 'number', 'mobile_number', 'contact_number', 'mobile', 'whatsapp_number', 'wa_number', 'whatsapp_contact', 'whatsappPhone'],
                'email' => ['email', 'email_address', 'e-mail', 'mail', 'contact_email'],
                'city' => ['city', 'location', 'town', 'municipality', 'urban_area', 'metropolis'],
                'state' => ['state', 'region', 'province', 'territory', 'county', 'district'],
                'delivery_address' => ['address', 'delivery_address', 'shipping_address', 'postal_address', 'street_address', 'recipient_address'],
                'delivery_duration' => ['duration', 'delivery_duration', 'time', 'delivery_time', 'shipping_time', 'estimated_time', 'eta', 'delivery_period'],
            ];

            // Call the autoFillFields method to fill in the fields intelligently

            $data = (new FieldMatcher())->matchFields($fieldVariations, $model->data);
            Log::alert("input:" . json_encode($model->data));
            Log::alert("output:" . json_encode($data));

            $model->unique_key = $model->createUniqueKey(Str::random(30));

            // Helper function to determine if a value is considered "empty" (null or empty string)
            $isEmpty = function ($value) {
                return $value === null || $value === '';
            };

            // Set fields if not already set, using the auto-filled data or default values
            $model->firstname = !$isEmpty($model->firstname) ? $model->firstname : ($data['firstname'] ?? 'Customer');
            $model->lastname = !$isEmpty($model->lastname) ? $model->lastname : ($data['lastname'] && $data['lastname'] != $data['firstname'] ? $data['lastname'] : 'lastname');
            $model->phone_number = !$isEmpty($model->phone_number) ? $model->phone_number : ($data['phone_number'] ?? '');
            $model->whatsapp_phone_number = !$isEmpty($model->whatsapp_phone_number) ? $model->whatsapp_phone_number : ($data['whatsapp_phone_number'] ?? '');
            $model->email = !$isEmpty($model->email) ? $model->email : ($data['email'] ?? 'default@site.com');
            $model->city = !$isEmpty($model->city) ? $model->city : ($data['city'] ?? '');
            $model->state = !$isEmpty($model->state) ? $model->state : ($data['state'] ?? '');
            $model->delivery_address = !$isEmpty($model->delivery_address) ? $model->delivery_address : ($data['delivery_address'] ?? '');
            $model->delivery_duration = !$isEmpty($model->delivery_duration) ? $model->delivery_duration : ($data['delivery_duration'] ?? '');
        });



        static::created(function ($model) {
            // If the lastname is still empty after creation, set it as '<inserted_id>'
            if ($model->lastname == 'lastname') {
                if ($model->firstname == 'Customer') {
                    $model->lastname = $model->id;
                    $model->save();
                } else {
                    $firstNameParts = explode(' ', $model->firstname);
                    if (count($firstNameParts) > 1) {
                        // If firstname contains multiple words, assign the first part to firstname and the rest to lastname
                        $model->firstname = $firstNameParts[0];
                        $model->lastname = implode(' ', array_slice($firstNameParts, 1));
                        $model->save();
                    } else {
                        $model->lastname = ' ';
                    }
                }
            }


            if ($model->email == 'default@site.com') {
                $name = preg_replace('/[^a-z0-9]/', '', strtolower($model->firstname . $model->lastname));
                // Generate a default email using a combination of firstname, lastname, and the model ID
                $defaultEmail = $name . '@site.com';

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
     * Route notifications for the SMS channel.
     *
     * @return string
     */
    public function routeNotificationForSMS()
    {
        // Return the WhatsApp number to be used
        return $this->phone_number ?? $this->whatsapp_phone_number;
    }
    /**
     * Route notifications for the WhatsApp channel.
     *
     * @return string
     */
    public function routeNotificationForWhatsapp()
    {
        // Return the WhatsApp number to be used
        return $this->whatsapp_phone_number ?? $this->phone_number;
    }

    /**
     * Route notifications for the Email channel.
     *
     * @return string
     */
    public function routeNotificationForEmail()
    {
        // Return the WhatsApp number to be used
        return $this->email;
    }
}
