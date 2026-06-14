<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Tontine;
use App\Models\Cotisation;
use App\Models\Tour;
use App\Models\NotificationMembre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GerantController extends Controller
{
    private function getGerant()
    {
        $id = Session::get('membre_id');
        if (!$id) return null;
        return Membre::find($id);
    }

    public function index()
    {
        $gerant = $this->getGerant();
        if (!$gerant) return redirect('/login');
        if (!in_array($gerant->role, ['gerant', 'admin'])) return redirect('/mon-espace');

        $membres         = Membre::where('role', 'membre')->orWhere('role', 'gerant')->orWhere('role', 'admin')->get();
        $membresEnAttente = Membre::where('statut', 'en_attente')->get();
        $tontines        = Tontine::with(['membres', 'tours'])->get();
        $cotisations     = Cotisation::with(['membre', 'tontine'])->get();
        $tours           = Tour::with('tontine')->get();

        return view('gerant', compact('gerant', 'membres', 'membresEnAttente', 'tontines', 'cotisations', 'tours'));
    }
}