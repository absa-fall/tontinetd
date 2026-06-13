<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationAdmin extends Model
{
    protected $table = 'notifications_admin';

    protected $fillable = [
        'membre_id',
        'titre',
        'message',
        'lu',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
}