<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'tontine_id',
        'membre_id',
        'date_tour',
        'etat',
        'mode_reception',
        'notifie',
    ];

    public function tontine()
    {
        return $this->belongsTo(Tontine::class);
    }

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }
}