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
    'role',
];

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }
    public function tours()
    {
        return $this->hasMany(Tour::class);
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

    public function isGerant()
    {
        return $this->role === 'gerant';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}