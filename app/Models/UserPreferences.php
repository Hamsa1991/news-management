<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreferences extends Model
{
    protected $table = 'user_preferences';
    protected $fillable = ['user_id', 'preference_id', 'preference_type'];

    /**
     * Get the user.
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
