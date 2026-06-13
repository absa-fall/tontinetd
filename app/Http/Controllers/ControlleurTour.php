<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Membre;
use App\Models\NotificationMembre;
use Illuminate\Http\Request;

class ControlleurTour extends Controller
{
    public function index()
    {
        $tours = Tour::with('tontine')->get();
        return view('tours.index', compact('tours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'date_tour'  => 'required|date',
            'etat'       => 'required|in:en_attente,terminer',
        ]);

        $tour = Tour::create($request->all());

        // Notifier tous les membres
        $membres = Membre::all();
        foreach ($membres as $m) {
            NotificationMembre::create([
                'membre_id' => $m->id,
                'titre'     => 'Nouveau tour',
                'message'   => 'Un nouveau tour a été programmé pour le ' . \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') . '.',
                'lu'        => false,
            ]);
        }

        return redirect('/dashboard')->with('success', 'Tour ajouté avec succès !')->with('section', 'tours');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'date_tour'  => 'required|date',
            'etat'       => 'required|in:en_attente,terminer',
        ]);

        $tour = Tour::findOrFail($id);
        $tour->update($request->all());

        return redirect('/dashboard')->with('success', 'Tour modifié avec succès !')->with('section', 'tours');
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();

        return redirect('/dashboard')->with('success', 'Tour supprimé avec succès !')->with('section', 'tours');
    }
}