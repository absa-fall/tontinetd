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

        $membres          = Membre::all();
        $membresEnAttente = Membre::where('statut', 'en_attente')->get();
        $tontines         = Tontine::with(['membres', 'tours'])->get();
        $cotisations      = Cotisation::with(['membre', 'tontine'])->get();
        $tours            = Tour::with('tontine')->get();
        $notifications    = NotificationMembre::where('membre_id', $gerant->id)->orderBy('created_at', 'desc')->get();
        $notifsNonLues    = $notifications->where('lu', false)->count();
        $admin            = Membre::whereIn('role', ['admin', 'gerant'])->first();

        return view('gerant', compact('gerant', 'membres', 'membresEnAttente', 'tontines', 'cotisations', 'tours', 'notifications', 'notifsNonLues', 'admin'));
    }

    public function marquerLu($id)
    {
        $notif = NotificationMembre::find($id);
        if ($notif && $notif->membre_id === Session::get('membre_id')) {
            $notif->update(['lu' => true]);
        }
        return redirect('/gerant')->with('section', 'notifications');
    }

    public function marquerToutLu()
    {
        NotificationMembre::where('membre_id', Session::get('membre_id'))
            ->where('lu', false)
            ->update(['lu' => true]);
        return redirect('/gerant')->with('section', 'notifications');
    }

    public function supprimerToutNotifs()
    {
        NotificationMembre::where('membre_id', Session::get('membre_id'))->delete();
        return redirect('/gerant')->with('section', 'notifications');
    }
}