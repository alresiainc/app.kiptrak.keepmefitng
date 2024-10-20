<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\UserRole;
use App\Notifications\UserNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shortName($name)
    {
        $shortName = "";

        $names = explode(" ", $name);

        foreach ($names as $w) {
            $shortName .= $w[0];
        }
        return $shortName;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'assigned_to');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasAnyRole($user_id)
    {
        return (bool) UserRole::where('user_id', $user_id)->count();
    }

    public function role($user_id)
    {
        return UserRole::where('user_id', $user_id)->first();
    }

    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'staff_assigned_id');
    }

    /**
     * Route notifications for the WhatsApp channel.
     *
     * @return string
     */
    public function routeNotificationForWhatsapp()
    {
        // Return the WhatsApp number to be used
        return $this->phone_1 ?? $this->phone_2;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)
            ->where(function ($query) {
                // Get the current user's ID
                $user_id = $this->id;

                // $query->whereNull('user_id')->orWhere('user_id', $user_id);

                // Check if the notification has a specific user_id
                // $query->whereNull('user_id')->where(function ($subQuery) use ($user_id) {
                //     $subQuery->orWhere('user_id', $user_id);
                // });

                // $query->whereNull('user_id')->where(function ($subQuery) use ($user_id) {
                //     $subQuery->where(function ($innerQuery) use ($user_id) {
                //         $innerQuery->orWhere('user_id', $user_id);
                //     });
                // });

                // Check if the notification has a specific user_id
                $query->where(function ($subQuery) use ($user_id) {


                    // Allow notifications if user_id is null or matches the current user
                    $subQuery->whereNull('user_id')->where(function ($innerQuery) use ($user_id) {
                        // Check for user_role conditions
                        $userRoles = $this->roles->pluck('id')->toArray(); // Assuming you want role IDs
                        $notificationRoles = $innerQuery->pluck('roles')->toArray();

                        // Only check user_roles if the notification specifies any
                        // if (!empty($notificationRoles)) {
                        //     $innerQuery->where(function ($innerQuery) use ($notificationRoles, $userRoles) {
                        //         $subQuery->where(function ($innerQuery) use ($userRoles, $notificationRoles) {
                        //             foreach ($userRoles as $role) {
                        //                 if (in_array($role, $notificationRoles)) {
                        //                     $innerQuery->orWhereJsonContains('roles', $role);
                        //                 }
                        //             }
                        //         });
                        //     });
                        // }

                        // Check for types conditions
                        // $userType = $this->type; // Assuming this is the user's type
                        // $notificationTypes = $innerQuery->pluck('types')->toArray();

                        // // Only check typess if the notification specifies any
                        // if (!empty($notificationTypes)) {
                        //     $innerQuery->where(function ($subQuery) use ($notificationTypes, $userType) {
                        //         $subQuery->whereNull('types')
                        //             ->orWhereIn('types', [$userType]); // Check against user's type
                        //     });
                        // }
                    })->orWhere('user_id', $user_id);
                });
            });
    }

    public function notificationss()
    {
        return $this->hasMany(Notification::class)
            ->where(function ($query) {

                $user_id = $this->id; // Get the current user's ID

                // Check if the notification has a specific user_id
                $query->where(function ($subQuery) use ($user_id) {


                    // Allow notifications if user_id is null or matches the current user
                    $subQuery->whereNull('user_id')
                        ->orWhere('user_id', $user_id);
                });

                // dd($query->get());

                // Retrieve the roles and types for the current user
                $userRoles = $this->roles->pluck('id')->toArray(); // Assuming you want role IDs
                $userType = $this->type; // Assuming this is the user's type

                // Check if the notification specifies any roles
                $query->when(function ($query) {

                    return !is_null($query->pluck('roles')->toArray());
                }, function ($query) use ($userRoles) {
                    $notificationRoles = $query->pluck('roles')->toArray();
                    // Only include notifications if the user has a matching role
                    // dd($query->pluck('types')->toArray());
                    $query->where(function ($subQuery) use ($notificationRoles, $userRoles) {
                        $subQuery->whereIn('roles', $userRoles);
                    });
                });

                // Check if the notification specifies any types
                $query->when(function ($query) {
                    return !is_null($query->pluck('types')->toArray());
                }, function ($query) use ($userType) {
                    $notificationTypes = $query->pluck('types')->toArray();
                    // Only include notifications if the user has a matching type
                    $query->whereIn('types', [$userType]);
                });
            })
            ->orderBy('created_at', 'desc');
    }




    public function sendNotification($title, $message)
    {
        // Create a new notification instance
        $notification = Notification::create([
            'user_id' => $this->id,
            'title' => $title,
            'message' => $message,
        ]);

        // Notify the user
        $this->notify(new UserNotification($notification));
    }
}