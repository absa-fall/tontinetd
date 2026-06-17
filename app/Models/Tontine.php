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
    'frequence',
    'nombre_max_membres',
    'lien_whatsapp',
];

    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'membre_tontine')
                    ->withPivot('role', 'statut')
                    ->withTimestamps();
    }

    // Membres approuvés uniquement (ceux qui participent réellement)
    public function membresApprouves()
    {
        return $this->belongsToMany(Membre::class, 'membre_tontine')
                    ->withPivot('role', 'statut')
                    ->withTimestamps()
                    ->wherePivot('statut', 'approuve');
    }

    // Demandes en attente
    public function demandesEnAttente()
    {
        return $this->belongsToMany(Membre::class, 'membre_tontine')
                    ->withPivot('role', 'statut')
                    ->withTimestamps()
                    ->wherePivot('statut', 'en_attente');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class);
    }

    // Vérifie si la tontine est pleine (membres approuvés uniquement)
    public function estPleine(): bool
    {
        return $this->membresApprouves()->count() >= $this->nombre_max_membres;
    }

    // Récupérer le gérant de la tontine (celui qui a role='admin' dans le pivot)
    public function gerant()
    {
        return $this->membresApprouves()->wherePivot('role', 'admin')->first();
    }
}