<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    protected $fillable = [
        'membre_id',
        'tontine_id',
        'montant',
        'date_cotisation',
        'ajout_par',
        'moyen_paiement',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function tontine()
    {
        return $this->belongsTo(Tontine::class);
    }
}