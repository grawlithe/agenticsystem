<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'srv.survery';

    protected $fillable = [
        'name',
        'email',
        'feedback',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEmailFeedbackAttribute()
    {
        return $this->email . " - " . $this->feedback;
    }
}
