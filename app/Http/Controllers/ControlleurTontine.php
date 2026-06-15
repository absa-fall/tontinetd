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

    $tontine = Tontine::create($request->only([
        'nom', 'description', 'date_debut', 'date_fin', 'montant', 'frequence'
    ]));

    // Attacher le gérant comme admin
    $adminId = Session::get('membre_id');
    if ($adminId) {
        $tontine->membres()->attach($adminId, ['role' => 'admin']);
    }

    // ✅ Attacher les membres sélectionnés
    $membreIds = $request->input('membre_ids', []);
    foreach ($membreIds as $membreId) {
        if ($membreId != $adminId) {
            $tontine->membres()->attach($membreId, ['role' => 'membre']);

            // Notifier chaque membre ajouté
            NotificationMembre::create([
                'membre_id' => $membreId,
                'titre'     => 'Ajout à une tontine',
                'message'   => 'Vous avez été ajouté à la tontine "' . $tontine->nom . '".',
                'lu'        => false,
            ]);
        }
    }

    // Notifier tous les membres de la création
    $membres = Membre::all();
    foreach ($membres as $m) {
        NotificationMembre::create([
            'membre_id' => $m->id,
            'titre'     => 'Nouvelle tontine',
            'message'   => 'Une nouvelle tontine "' . $tontine->nom . '" a été créée.',
            'lu'        => false,
        ]);
    }

    return back()->with('success', 'Tontine créée avec succès !')->with('section', 'tontines');
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

    public function prochainBeneficiaire($id)
    {
        $tontine = Tontine::with('membres', 'tours')->findOrFail($id);

        $membresDejaBeneficiaires = $tontine->tours
            ->where('etat', 'terminer')
            ->pluck('membre_id')
            ->toArray();

        $prochain = $tontine->membres
            ->whereNotIn('id', $membresDejaBeneficiaires)
            ->first();

        if (!$prochain) {
            $prochain = $tontine->membres->first();
        }

        $membres = $tontine->membres->map(function($m) {
            return ['id' => $m->id, 'nom' => $m->nom . ' ' . $m->prenom];
        });

        return response()->json([
            'membre_id' => $prochain ? $prochain->id : null,
            'nom'       => $prochain ? $prochain->nom . ' ' . $prochain->prenom : null,
            'membres'   => $membres,
        ]);
    }

    public function destroy($id)
    {
        $tontine = Tontine::findOrFail($id);
        $tontine->delete();

        return back()->with('success', 'Tontine supprimée avec succès !')->with('section', 'tontines'); // ✅ return ajouté
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
            return back()->with('error', 'Ce membre est déjà dans cette tontine !'); // ✅ return ajouté
        }

        $tontine->membres()->attach($membreId, ['role' => 'membre']);

        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => 'Nouvelle tontine',
            'message'   => 'Vous avez été ajouté à la tontine : ' . $tontine->nom,
            'lu'        => false,
        ]);

        return back()->with('success', 'Membre ajouté avec succès !'); // ✅ return ajouté
    }

    public function retirerMembre($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);
        $tontine->membres()->detach($membreId);

        return back()->with('success', 'Membre retiré avec succès !'); // ✅ return ajouté
    }
}