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
    // -----------------------------------------------
    // NOTIFICATIONS INTERNES
    // -----------------------------------------------
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

    // -----------------------------------------------
    // INDEX (gérant/admin/super_admin)
    // -----------------------------------------------
    public function index()
    {
        $tontines = Tontine::with(['membres', 'tours'])->get();
        return view('tontines.index', compact('tontines'));
    }

    // -----------------------------------------------
    // CRÉER UNE TONTINE
    // -----------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'nom'                 => 'required|string|max:255',
            'description'         => 'required|string',
            'date_debut'          => 'required|date',
            'date_fin'            => 'required|date|after:date_debut',
            'montant'             => 'required|numeric|min:1',
            'frequence'           => 'required|in:semaine,mensuelle,journalier',
            'nombre_max_membres'  => 'required|integer|min:2|max:500',
        ]);

        $tontine = Tontine::create($request->only([
            'nom', 'description', 'date_debut', 'date_fin',
            'montant', 'frequence', 'nombre_max_membres',
        ]));

        // Attacher le gérant créateur comme admin de la tontine
        $adminId = Session::get('membre_id');
        if ($adminId) {
            $tontine->membres()->attach($adminId, [
                'role'   => 'admin',
                'statut' => 'approuve',
            ]);
        }

        // Attacher les membres sélectionnés (directement approuvés par le gérant)
        $membreIds = $request->input('membre_ids', []);
        foreach ($membreIds as $membreId) {
            if ($membreId != $adminId) {
                $tontine->membres()->attach($membreId, [
                    'role'   => 'membre',
                    'statut' => 'approuve',
                ]);
                NotificationMembre::create([
                    'membre_id' => $membreId,
                    'titre'     => 'Ajout à une tontine',
                    'message'   => 'Vous avez été ajouté à la tontine "' . $tontine->nom . '".',
                    'lu'        => false,
                ]);
            }
        }

        // Notifier tous les autres membres qu'une tontine existe
        $membresNonAjoutes = Membre::where('statut', 'approuve')
            ->whereNotIn('id', array_merge($membreIds, [$adminId]))
            ->get();

        foreach ($membresNonAjoutes as $m) {
            NotificationMembre::create([
                'membre_id' => $m->id,
                'titre'     => 'Nouvelle tontine disponible',
                'message'   => 'Une nouvelle tontine "' . $tontine->nom . '" est disponible. Vous pouvez faire une demande pour y adhérer.',
                'lu'        => false,
            ]);
        }

        $this->notifierAdmin(
            'Nouvelle tontine créée',
            Session::get('membre_nom') . ' a créé la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Tontine créée avec succès !')->with('section', 'tontines');
    }

    // -----------------------------------------------
    // MODIFIER UNE TONTINE
    // -----------------------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom'                => 'required|string|max:255',
            'description'        => 'required|string',
            'date_debut'         => 'required|date',
            'date_fin'           => 'required|date|after:date_debut',
            'montant'            => 'required|numeric|min:1',
            'frequence'          => 'required|in:semaine,mensuelle,journalier',
            'nombre_max_membres' => 'required|integer|min:2|max:500',
        ]);

        $tontine = Tontine::findOrFail($id);

        // Vérifier que le nouveau max n'est pas inférieur aux membres actuels
        $nbMembresActuels = $tontine->membresApprouves()->count();
        if ($request->nombre_max_membres < $nbMembresActuels) {
            return back()->with('error', 'Le nombre maximum ne peut pas être inférieur au nombre de membres actuels (' . $nbMembresActuels . ').');
        }

        $tontine->update($request->only([
            'nom', 'description', 'date_debut', 'date_fin',
            'montant', 'frequence', 'nombre_max_membres',
        ]));

        $this->notifierAdmin(
            'Tontine modifiée',
            Session::get('membre_nom') . ' a modifié la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Tontine modifiée avec succès !')->with('section', 'tontines');
    }

    // -----------------------------------------------
    // DÉTAILS D'UNE TONTINE (gérant/admin/super_admin)
    // -----------------------------------------------
    public function details($id)
    {
        $tontine = Tontine::with([
            'membresApprouves',
            'demandesEnAttente',
            'tours.membre',
            'cotisations.membre',
        ])->findOrFail($id);

        $nbToursTermines = $tontine->tours->where('etat', 'terminer')->count();

        // Membres approuvés qui ne sont pas encore dans cette tontine
        $idsDejaDansTontine = $tontine->membres->pluck('id')->toArray();
        $membresDisponibles = Membre::where('statut', 'approuve')
            ->whereNotIn('id', $idsDejaDansTontine)
            ->get();

        return view('tontine-details', compact(
            'tontine', 'nbToursTermines', 'membresDisponibles'
        ));
    }

    // -----------------------------------------------
    // AJOUTER UN MEMBRE DIRECTEMENT (gérant/admin/super_admin)
    // -----------------------------------------------
    public function ajouterMembre(Request $request, $tontineId)
    {
        $request->validate([
            'membre_id' => 'required|exists:membres,id',
        ]);

        $tontine  = Tontine::findOrFail($tontineId);
        $membreId = $request->membre_id;

        // Vérifier si déjà dans la tontine (peu importe le statut)
        if ($tontine->membres()->where('membre_id', $membreId)->exists()) {
            return back()->with('error', 'Ce membre est déjà dans cette tontine ou a une demande en cours.');
        }

        // Vérifier la limite
        if ($tontine->estPleine()) {
            return back()->with('error', 'Cette tontine a atteint son nombre maximum de membres (' . $tontine->nombre_max_membres . ').');
        }

        $tontine->membres()->attach($membreId, [
            'role'   => 'membre',
            'statut' => 'approuve',
        ]);

        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => 'Ajout à une tontine',
            'message'   => 'Vous avez été ajouté à la tontine "' . $tontine->nom . '".',
            'lu'        => false,
        ]);

        $this->notifierAdmin(
            'Membre ajouté à une tontine',
            Session::get('membre_nom') . ' a ajouté un membre à la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Membre ajouté avec succès !');
    }

    // -----------------------------------------------
    // RETIRER UN MEMBRE (gérant/admin/super_admin)
    // -----------------------------------------------
    public function retirerMembre($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);
        $membre  = Membre::find($membreId);
        $tontine->membres()->detach($membreId);

        if ($membre) {
            NotificationMembre::create([
                'membre_id' => $membreId,
                'titre'     => 'Retrait de tontine',
                'message'   => 'Vous avez été retiré de la tontine "' . $tontine->nom . '".',
                'lu'        => false,
            ]);
        }

        $this->notifierAdmin(
            'Membre retiré d\'une tontine',
            Session::get('membre_nom') . ' a retiré ' .
            ($membre ? $membre->prenom . ' ' . $membre->nom : 'un membre') .
            ' de la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Membre retiré avec succès !');
    }

    // -----------------------------------------------
    // DEMANDE D'ADHESION (membre simple)
    // -----------------------------------------------
    public function demanderAdhesion(Request $request, $tontineId)
    {
        $membreId = Session::get('membre_id');
        $tontine  = Tontine::findOrFail($tontineId);

        // Déjà membre ou demande en cours
        if ($tontine->membres()->where('membre_id', $membreId)->exists()) {
            return back()->with('error', 'Vous êtes déjà membre de cette tontine ou avez déjà une demande en cours.');
        }

        // Tontine pleine
        if ($tontine->estPleine()) {
            return back()->with('error', 'Cette tontine est complète (' . $tontine->nombre_max_membres . ' membres max).');
        }

        // Créer la demande avec statut en_attente
        $tontine->membres()->attach($membreId, [
            'role'   => 'membre',
            'statut' => 'en_attente',
        ]);

        // Notifier le gérant de la tontine
        $gerant = $tontine->gerant();
        $demandeur = Membre::find($membreId);

        if ($gerant) {
            NotificationMembre::create([
                'membre_id' => $gerant->id,
                'titre'     => 'Nouvelle demande d\'adhésion',
                'message'   => ($demandeur ? $demandeur->prenom . ' ' . $demandeur->nom : 'Un membre') .
                               ' demande à rejoindre la tontine "' . $tontine->nom . '".',
                'lu'        => false,
            ]);
        }

        // Notifier aussi l'admin
        $this->notifierAdmin(
            'Demande d\'adhésion',
            ($demandeur ? $demandeur->prenom . ' ' . $demandeur->nom : 'Un membre') .
            ' a demandé à rejoindre la tontine "' . $tontine->nom . '".'
        );

        return back()->with('success', 'Votre demande a été envoyée. Le gérant va l\'examiner.');
    }

    // -----------------------------------------------
    // APPROUVER UNE DEMANDE (gérant/admin/super_admin)
    // -----------------------------------------------
    public function approuverDemande($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        // Vérifier à nouveau la limite avant d'approuver
        if ($tontine->estPleine()) {
            return back()->with('error', 'La tontine est pleine. Impossible d\'approuver.');
        }

        $tontine->membres()->updateExistingPivot($membreId, ['statut' => 'approuve']);

        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => 'Demande acceptée',
            'message'   => 'Votre demande pour rejoindre la tontine "' . $tontine->nom . '" a été acceptée.',
            'lu'        => false,
        ]);

        return back()->with('success', 'Demande approuvée avec succès !');
    }

    // -----------------------------------------------
    // REFUSER UNE DEMANDE (gérant/admin/super_admin)
    // -----------------------------------------------
    public function refuserDemande($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);
        $tontine->membres()->detach($membreId);

        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => 'Demande refusée',
            'message'   => 'Votre demande pour rejoindre la tontine "' . $tontine->nom . '" a été refusée.',
            'lu'        => false,
        ]);

        return back()->with('success', 'Demande refusée.');
    }

    // -----------------------------------------------
    // PROCHAIN BENEFICIAIRE
    // -----------------------------------------------
    public function prochainBeneficiaire($id)
    {
        $tontine = Tontine::with(['membresApprouves', 'tours'])->findOrFail($id);

        $membresDejaBeneficiaires = $tontine->tours
            ->where('etat', 'terminer')
            ->pluck('membre_id')
            ->toArray();

        $prochain = $tontine->membresApprouves
            ->whereNotIn('id', $membresDejaBeneficiaires)
            ->first();

        if (!$prochain) {
            $prochain = $tontine->membresApprouves->first();
        }

        $membres = $tontine->membresApprouves->map(function ($m) {
            return ['id' => $m->id, 'nom' => $m->nom . ' ' . $m->prenom];
        });

        return response()->json([
            'membre_id' => $prochain ? $prochain->id : null,
            'nom'       => $prochain ? $prochain->nom . ' ' . $prochain->prenom : null,
            'membres'   => $membres,
        ]);
    }

    // -----------------------------------------------
    // SUPPRIMER UNE TONTINE
    // -----------------------------------------------
    public function destroy($id)
    {
        $tontine = Tontine::findOrFail($id);
        $nom = $tontine->nom;

        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Tontine supprimée par l\'admin',
                'L\'administrateur a supprimé la tontine "' . $nom . '".'
            );
        } else {
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
        $ids     = $request->input('tontine_ids', []);
        $tontines = Tontine::whereIn('id', $ids)->get();
        $noms    = $tontines->pluck('nom')->implode(', ');

        foreach ($tontines as $tontine) {
            $tontine->membres()->detach();
            $tontine->delete();
        }

        if (Session::get('is_admin')) {
            $this->notifierGerants('Tontines supprimées', 'L\'administrateur a supprimé : ' . $noms);
        } else {
            $this->notifierAdmin('Tontines supprimées', Session::get('membre_nom') . ' a supprimé : ' . $noms);
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

        if (Session::get('is_admin')) {
            $this->notifierGerants('Toutes les tontines supprimées', 'L\'administrateur a supprimé toutes les tontines.');
        } else {
            $this->notifierAdmin('Toutes les tontines supprimées', Session::get('membre_nom') . ' a supprimé toutes les tontines.');
        }

        return back()->with('success', 'Toutes les tontines ont été supprimées !')->with('section', 'tontines');
    }
}