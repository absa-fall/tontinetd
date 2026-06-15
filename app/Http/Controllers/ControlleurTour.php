<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Membre;
use App\Models\Tontine;
use App\Models\NotificationMembre;
use Illuminate\Http\Request;

class ControlleurTour extends Controller
{
    public function index()
    {
        $tours = Tour::with(['tontine', 'membre'])->get();
        $tontines = Tontine::all();
        $membres = Membre::all();
        return view('tour', compact('tours', 'tontines', 'membres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'membre_id'  => 'required|exists:membres,id',
            'date_tour'  => 'required|date',
            'etat'       => 'required|in:en_attente,terminer',
        ]);

        $tour = Tour::create([
            'tontine_id' => $request->tontine_id,
            'membre_id'  => $request->membre_id,
            'date_tour'  => $request->date_tour,
            'etat'       => $request->etat,
            'notifie'    => false,
        ]);

        $beneficiaire = Membre::findOrFail($request->membre_id);
        $tontine = Tontine::findOrFail($request->tontine_id);

        NotificationMembre::create([
            'membre_id' => $beneficiaire->id,
            'titre'     => 'Vous êtes bénéficiaire !',
            'message'   => 'Vous êtes bénéficiaire du tour du ' . \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') . '. Souhaitez-vous recevoir en présentiel ou via un opérateur ? Merci de confirmer.',
            'lu'        => false,
        ]);

        $autresMembres = $tontine->membres()->where('membres.id', '!=', $beneficiaire->id)->get();
        foreach ($autresMembres as $m) {
            NotificationMembre::create([
                'membre_id' => $m->id,
                'titre'     => 'Nouveau tour',
                'message'   => 'Un nouveau tour a été programmé pour le ' . \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') . '. ' . $beneficiaire->nom . ' ' . $beneficiaire->prenom . ' en est le bénéficiaire.',
                'lu'        => false,
            ]);
        }

        $tour->update(['notifie' => true]);

        return back()->with('success', 'Tour créé avec succès !')->with('section', 'tours'); // ✅ return ajouté
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'date_tour'  => 'required|date',
            'etat'       => 'required|in:en_attente,terminer',
        ]);

        $tour = Tour::findOrFail($id);
        $tour->update($request->only(['tontine_id', 'date_tour', 'etat']));

        return back()->with('success', 'Tour modifié avec succès !')->with('section', 'tours'); // ✅ return ajouté
    }

    public function choisirModeReception(Request $request, $id)
    {
        $request->validate([
            'mode_reception' => 'required|in:presentiel,operateur',
        ]);

        $tour = Tour::findOrFail($id);
        $tour->update(['mode_reception' => $request->mode_reception]);

        $membre = $tour->membre;
        $modeTexte = $request->mode_reception === 'presentiel' ? 'en présentiel' : 'via un opérateur';

        \App\Models\NotificationAdmin::create([
            'membre_id' => $membre->id,
            'titre'     => 'Choix du mode de réception',
            'message'   => $membre->nom . ' ' . $membre->prenom . ' a choisi de recevoir son tour du ' . \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') . ' ' . $modeTexte . '.',
            'lu'        => false,
        ]);

        return redirect('/mon-espace')->with('success', 'Mode de réception enregistré !');
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();

        return redirect('/dashboard')->with('success', 'Tour supprimé avec succès !')->with('section', 'tours');
    }

    public function supprimerSelection(Request $request)
    {
        $ids = $request->input('tour_ids', []);
        Tour::whereIn('id', $ids)->delete();

        return back()->with('success', 'Tours supprimés avec succès !')->with('section', 'tours');
    }

    public function supprimerTout()
    {
        Tour::truncate();

        return back()->with('success', 'Tous les tours ont été supprimés !')->with('section', 'tours');
    }
}