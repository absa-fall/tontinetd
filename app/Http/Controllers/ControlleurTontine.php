<?php

namespace App\Http\Controllers;

use App\Models\Tontine;
use App\Models\Membre;
use App\Models\NotificationMembre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControlleurTontine extends Controller
{
    public function index()
    {
        $tontines = Tontine::with(['membres', 'tours'])->get();
        return view('tontines.index', compact('tontines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'montant'     => 'required|numeric|min:1',
            'frequence'   => 'required|in:semaine,mensuelle,journalier',
        ]);

        $tontine = Tontine::create($request->all());

        $adminId = Session::get('membre_id');
        if ($adminId) {
            $tontine->membres()->attach($adminId, ['role' => 'admin']);
        }

        // Notifier tous les membres
        $membres = Membre::all();
        foreach ($membres as $m) {
            NotificationMembre::create([
                'membre_id' => $m->id,
                'titre'     => 'Nouvelle tontine',
                'message'   => 'Une nouvelle tontine "' . $tontine->nom . '" a été créée.',
                'lu'        => false,
            ]);
        }

        return redirect('/dashboard')->with('success', 'Tontine créée avec succès !')->with('section', 'tontines');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'montant'     => 'required|numeric|min:1',
            'frequence'   => 'required|in:semaine,mensuelle,journalier',
        ]);

        $tontine = Tontine::findOrFail($id);
        $tontine->update($request->all());

        return back()->with('success', 'Tontine modifiée avec succès !')->with('section', 'tontines');
    }
    public function details($id)
    {
        $tontine = Tontine::with(['membres', 'tours.membre', 'cotisations.membre'])->findOrFail($id);

        $nbToursTermines = $tontine->tours->where('etat', 'terminer')->count();

        return view('tontine-details', compact('tontine', 'nbToursTermines'));
    }

    public function destroy($id)
{
    $tontine = Tontine::findOrFail($id); // ← était $tontineId
    $tontine->delete();

    return redirect('/dashboard')->with('success', 'Tontine supprimée avec succès !')->with('section', 'tontines');
}
public function supprimerSelection(Request $request)
    {
        $ids = $request->input('tontine_ids', []);
        Tontine::whereIn('id', $ids)->delete();

        return back()->with('success', 'Tontines supprimées avec succès !')->with('section', 'tontines');
    }

    public function supprimerTout()
    {
        Tontine::truncate();

        return back()->with('success', 'Toutes les tontines ont été supprimées !')->with('section', 'tontines');
    }
    public function ajouterMembre(Request $request, $tontineId)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
        ]);

        $tontine = Tontine::findOrFail($tontineId);
        $membreId = $request->membre_id;

        if ($tontine->membres()->where('membre_id', $membreId)->exists()) {
            return redirect('/dashboard')->with('error', 'Ce membre est déjà dans la tontine !')->with('section', 'tontines');
        }

        $tontine->membres()->attach($membreId, ['role' => 'membre']);

        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => 'Nouvelle tontine',
            'message'   => 'Vous avez été ajouté à la tontine : ' . $tontine->nom,
            'lu'        => false,
        ]);

        return redirect('/dashboard')->with('success', 'Membre ajouté avec succès !')->with('section', 'tontines');
    }

    public function retirerMembre($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);
        $tontine->membres()->detach($membreId);

        return redirect('/dashboard')->with('success', 'Membre retiré avec succès !')->with('section', 'tontines');
    }
}