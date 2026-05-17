<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationMembre extends Model
{
    protected $table = 'notifications_membre';

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