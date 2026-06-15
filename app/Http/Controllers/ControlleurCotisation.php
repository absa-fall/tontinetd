<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Membre;
use App\Models\NotificationMembre;
use App\Models\NotificationAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControlleurCotisation extends Controller
{
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

    private function redirectRole($section = 'cotisations')
    {
        $role = Session::get('role');
        if (in_array($role, ['gerant', 'admin'])) {
            return redirect('/gerant')->with('section', $section);
        }
        return redirect('/dashboard')->with('section', $section);
    }

    public function index()
    {
        $cotisations = Cotisation::with(['membre', 'tontine'])->get();
        return view('cotisations.index', compact('cotisations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'montant'         => 'required|numeric|min:1',
            'date_cotisation' => 'required|date',
            'membre_id'       => 'required|exists:membres,id',
            'tontine_id'      => 'required|exists:tontines,id',
        ]);

        $tourEnCours = \App\Models\Tour::where('tontine_id', $request->tontine_id)
            ->where('etat', 'en_attente')
            ->orderBy('date_tour', 'asc')
            ->first();

        if (!$tourEnCours) {
            return $this->redirectRole('cotisations')
                ->with('error', 'Aucun tour en attente pour cette tontine.');
        }

        if (now()->toDateString() > $tourEnCours->date_tour) {
            return $this->redirectRole('cotisations')
                ->with('error', 'La date de ce tour est dépassée, vous ne pouvez plus cotiser.');
        }

        $cotisation = Cotisation::create([
            'montant'         => $request->montant,
            'date_cotisation' => $request->date_cotisation,
            'membre_id'       => $request->membre_id,
            'tontine_id'      => $request->tontine_id,
            'tour_id'         => $tourEnCours->id,
            'ajout_par'       => 'admin',
            'moyen_paiement'  => 'cash',
        ]);

        // Notifier le membre concerné
        NotificationMembre::create([
            'membre_id' => $request->membre_id,
            'titre'     => 'Nouvelle cotisation enregistrée',
            'message'   => 'Une cotisation de ' . number_format($request->montant, 0, ',', ' ') . ' F CFA a été enregistrée pour vous.',
            'lu'        => false,
        ]);

        // ✅ Notifier le super admin
        $this->notifierAdmin(
            'Nouvelle cotisation',
            Session::get('membre_nom') . ' a enregistré une cotisation de ' . number_format($request->montant, 0, ',', ' ') . ' F CFA.'
        );

        return back()->with('success', 'Cotisation ajoutée avec succès !')->with('section', 'cotisations');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'montant'         => 'required|numeric|min:1',
            'date_cotisation' => 'required|date',
            'membre_id'       => 'required|exists:membres,id',
            'tontine_id'      => 'required|exists:tontines,id',
        ]);

        $cotisation = Cotisation::findOrFail($id);
        $cotisation->update($request->all());

        // ✅ Notifier le super admin
        $this->notifierAdmin(
            'Cotisation modifiée',
            Session::get('membre_nom') . ' a modifié une cotisation de ' . number_format($request->montant, 0, ',', ' ') . ' F CFA.'
        );

        return back()->with('success', 'Cotisation modifiée avec succès !')->with('section', 'cotisations');
    }

    public function destroy($id)
    {
        $cotisation = Cotisation::findOrFail($id);
        $montant = $cotisation->montant;

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Cotisation supprimée par l\'admin',
                'L\'administrateur a supprimé une cotisation de ' . number_format($montant, 0, ',', ' ') . ' F CFA.'
            );
        } else {
            $this->notifierAdmin(
                'Cotisation supprimée',
                Session::get('membre_nom') . ' a supprimé une cotisation de ' . number_format($montant, 0, ',', ' ') . ' F CFA.'
            );
        }

        $cotisation->delete();

        return back()->with('success', 'Cotisation supprimée avec succès !')->with('section', 'cotisations');
    }

    public function supprimerSelection(Request $request)
    {
        $ids = $request->input('cotisation_ids', []);
        $cotisations = Cotisation::whereIn('id', $ids)->get();
        $total = $cotisations->sum('montant');

        Cotisation::whereIn('id', $ids)->delete();

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Cotisations supprimées par l\'admin',
                'L\'administrateur a supprimé ' . count($ids) . ' cotisation(s).'
            );
        } else {
            $this->notifierAdmin(
                'Cotisations supprimées',
                Session::get('membre_nom') . ' a supprimé ' . count($ids) . ' cotisation(s).'
            );
        }

        return back()->with('success', 'Cotisations supprimées avec succès !')->with('section', 'cotisations');
    }

    public function supprimerTout()
    {
        Cotisation::truncate();

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Toutes les cotisations supprimées',
                'L\'administrateur a supprimé toutes les cotisations.'
            );
        } else {
            $this->notifierAdmin(
                'Toutes les cotisations supprimées',
                Session::get('membre_nom') . ' a supprimé toutes les cotisations.'
            );
        }

        return back()->with('success', 'Toutes les cotisations ont été supprimées !')->with('section', 'cotisations');
    }
}