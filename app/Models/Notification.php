<?php

namespace App\Models;

use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'roles',
        'types',
        'is_read',
    ];

    protected $casts = [
        'roles' => 'array',  // Cast to array
        'types' => 'array',  // Cast to array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendNotification($title, $message, $user_id = null, $userRoles = null, $userTypes = null)
    {
        // Ensure $userRoles and $userTypes are arrays
        $userRoles = is_array($userRoles) ? $userRoles : (isset($userRoles) ? [$userRoles] : []);
        $userTypes = is_array($userTypes) ? $userTypes : (isset($userTypes) ? [$userTypes] : []);
        // Create a new notification instance
        $notification = $this->create([
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
            'roles' => $userRoles, // Array of roles
            'types' => $userTypes, // Array of user types
        ]);

        // Broadcast the notification event
        // broadcast(new NotificationCreated($notification));

        // Notify the user
        $user = User::find($user_id);
        if ($user) {
            $user->notify(new UserNotification($notification));
        }
    }
}
