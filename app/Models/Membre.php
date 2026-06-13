<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'adresse',
        'telephone',
        'date_naissance',
        'statut',
    ];

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }

    public function notifications()
    {
        return $this->hasMany(NotificationMembre::class)->orderBy('created_at', 'desc');
    }

    public function notificationsNonLues()
    {
        return $this->hasMany(NotificationMembre::class)->where('lu', false);
    }

    public function notificationsAdmin()
    {
        return $this->hasMany(NotificationAdmin::class);
    }

    public function tontines()
    {
        return $this->belongsToMany(Tontine::class, 'membre_tontine')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}