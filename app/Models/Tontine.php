<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tontine extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'date_debut',
        'date_fin',
        'montant',
    ];

    public function membres()
    {
        return $this->belongsToMany(Membre::class);
    }
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }
}
