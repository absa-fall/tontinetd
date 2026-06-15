<?php

namespace App\Http\Controllers;

use App\Models\Tontine;
use App\Models\Membre;
use App\Models\NotificationMembre;
use App\Models\NotificationAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControlleurTontine extends Controller
{
    // Notifier le super admin d'une action du gérant
    private function notifierAdmin($titre, $message)
    {
        $membreId = Session::get('membre_id');
        if ($membreId) {
            NotificationAdmin::create([
                'membre_id' => $membreId,
                'titre'     => $titre,
                'message'   => $message,
            ]);
        }
    }

    // Notifier tous les gérants d'une action du super admin
    private function notifierGerants($titre, $message)
    {
        $gerants = Membre::whereIn('role', ['gerant', 'admin'])->get();
        foreach ($gerants as $g) {
            NotificationMembre::create([
                'membre_id' => $g->id,
                'titre'     => $titre,
                'message'   => $message,
                'lu'        => false,
            ]);
        }
    }

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

        // Attacher les membres sélectionnés
        $membreIds = $request->input('membre_ids', []);
        foreach ($membreIds as $membreId) {
            if ($membreId != $adminId) {
                $tontine->membres()->attach($membreId, ['role' => 'membre']);
                NotificationMembre::create([
                    'membre_id' => $membreId,
                    'titre'     => 'Ajout à une tontine',
                    'message'   => 'Vous avez été ajouté à la tontine "' . $tontine->nom . '".',
                    'lu'        => false,
                ]);
            }
        }

        // Notifier tous les membres
        $membres = Membre::whereNotIn('id', $membreIds)->get();
        foreach ($membres as $m) {
            NotificationMembre::create([
                'membre_id' => $m->id,
                'titre'     => 'Nouvelle tontine',
                'message'   => 'Une nouvelle tontine "' . $tontine->nom . '" a été créée.',
                'lu'        => false,
            ]);
        }

        // ✅ Notifier le super admin
        $this->notifierAdmin(
            'Nouvelle tontine créée',
            Session::get('membre_nom') . ' a créé la tontine "' . $tontine->nom . '".'
        );

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

        // ✅ Notifier le super admin
        $this->notifierAdmin(
            'Tontine modifiée',
            Session::get('membre_nom') . ' a modifié la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Tontine modifiée avec succès !')->with('section', 'tontines');
    }

    public function details($id)
    {
        $tontine = Tontine::with(['membres', 'tours.membre', 'cotisations.membre'])->findOrFail($id);
        $nbToursTermines = $tontine->tours->where('etat', 'terminer')->count();

        // ✅ Membres approuvés qui ne sont pas encore dans cette tontine
        $idsDejaDansTontine = $tontine->membres->pluck('id')->toArray();
        $membresDisponibles = Membre::where('statut', 'approuve')
            ->whereNotIn('id', $idsDejaDansTontine)
            ->get();

        return view('tontine-details', compact('tontine', 'nbToursTermines', 'membresDisponibles'));
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
        $nom = $tontine->nom;

        // ✅ Notifier les gérants si c'est le super admin qui supprime
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Tontine supprimée par l\'admin',
                'L\'administrateur a supprimé la tontine "' . $nom . '".'
            );
        } else {
            // Notifier le super admin si c'est le gérant
            $this->notifierAdmin(
                'Tontine supprimée',
                Session::get('membre_nom') . ' a supprimé la tontine "' . $nom . '".'
            );
        }

        $tontine->membres()->detach();
        $tontine->delete();

        return back()->with('success', 'Tontine supprimée avec succès !')->with('section', 'tontines');
    }

    public function supprimerSelection(Request $request)
    {
        $ids = $request->input('tontine_ids', []);
        $tontines = Tontine::whereIn('id', $ids)->get();
        $noms = $tontines->pluck('nom')->implode(', ');

        foreach ($tontines as $tontine) {
            $tontine->membres()->detach();
            $tontine->delete();
        }

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Tontines supprimées par l\'admin',
                'L\'administrateur a supprimé les tontines : ' . $noms . '.'
            );
        } else {
            $this->notifierAdmin(
                'Tontines supprimées',
                Session::get('membre_nom') . ' a supprimé les tontines : ' . $noms . '.'
            );
        }

        return back()->with('success', 'Tontines supprimées avec succès !')->with('section', 'tontines');
    }

    public function supprimerTout()
    {
        $tontines = Tontine::all();

        foreach ($tontines as $tontine) {
            $tontine->membres()->detach();
            $tontine->delete();
        }

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Toutes les tontines supprimées',
                'L\'administrateur a supprimé toutes les tontines.'
            );
        } else {
            $this->notifierAdmin(
                'Toutes les tontines supprimées',
                Session::get('membre_nom') . ' a supprimé toutes les tontines.'
            );
        }

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
            return back()->with('error', 'Ce membre est déjà dans cette tontine !');
        }

        $tontine->membres()->attach($membreId, ['role' => 'membre']);

        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => 'Ajout à une tontine',
            'message'   => 'Vous avez été ajouté à la tontine : ' . $tontine->nom,
            'lu'        => false,
        ]);

        // ✅ Notifier le super admin
        $this->notifierAdmin(
            'Membre ajouté à une tontine',
            Session::get('membre_nom') . ' a ajouté un membre à la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Membre ajouté avec succès !');
    }

    public function retirerMembre($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);
        $membre = Membre::find($membreId);
        $tontine->membres()->detach($membreId);

        // ✅ Notifier le super admin
        $this->notifierAdmin(
            'Membre retiré d\'une tontine',
            Session::get('membre_nom') . ' a retiré ' . ($membre ? $membre->prenom . ' ' . $membre->nom : 'un membre') . ' de la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Membre retiré avec succès !');
    }
}