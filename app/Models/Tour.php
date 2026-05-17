<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'date_tour',
        'etat',
    ];

    public function tontine()
    {
        return $this->belongsTo(Tontine::class);
    }
}
