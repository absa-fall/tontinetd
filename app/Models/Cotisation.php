<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    protected $fillable = [
        'id',
        'montant',
        'date_cotisation',
        'membre_id',
        'ajout_par',
        'moyen_paiement',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
}