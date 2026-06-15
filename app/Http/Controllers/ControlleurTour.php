<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Membre;
use App\Models\Tontine;
use App\Models\NotificationMembre;
use App\Models\NotificationAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControlleurTour extends Controller
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

        // Notifier le bénéficiaire
        NotificationMembre::create([
            'membre_id' => $beneficiaire->id,
            'titre'     => 'Vous êtes bénéficiaire !',
            'message'   => 'Vous êtes bénéficiaire du tour du ' . \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') . '. Souhaitez-vous recevoir en présentiel ou via un opérateur ? Merci de confirmer.',
            'lu'        => false,
        ]);

        // Notifier les autres membres
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

        // ✅ Notifier le super admin
        $this->notifierAdmin(
            'Nouveau tour créé',
            Session::get('membre_nom') . ' a créé un tour pour le ' . \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') . '. Bénéficiaire : ' . $beneficiaire->prenom . ' ' . $beneficiaire->nom . '.'
        );

        return back()->with('success', 'Tour créé avec succès !')->with('section', 'tours');
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'tontine_id'     => 'required|exists:tontines,id',
            'date_tour'      => 'required|date',
            'etat'           => 'required|in:en_attente,terminer',
            'mode_reception' => 'nullable|in:presentiel,operateur',
        ]);

        $tour = Tour::findOrFail($id);

        $dateLimite = \Carbon\Carbon::parse($tour->date_tour)->subDays(3);
        $peutModifierMode = now()->lessThanOrEqualTo($dateLimite) === false && now()->lessThanOrEqualTo(\Carbon\Carbon::parse($tour->date_tour));

        $data = $request->only(['tontine_id', 'date_tour', 'etat']);

        if ($peutModifierMode) {
            $data['mode_reception'] = $request->mode_reception;
        }

        $tour->update($data);

        return back()->with('success', 'Tour modifié avec succès !')->with('section', 'tours');
    }
public function choisirModeReception(Request $request, $id)
    {
        $request->validate([
            'mode_reception' => 'required|in:presentiel,operateur',
        ]);

        $tour = Tour::findOrFail($id);

        $dateLimite = \Carbon\Carbon::parse($tour->date_tour)->subDays(3);

        if (now()->greaterThanOrEqualTo($dateLimite)) {
            $role = Session::get('role');
            if ($role === 'gerant') {
                return redirect('/gerant')->with('error', 'Vous ne pouvez plus changer le mode de réception (moins de 3 jours avant le tour).')->with('section', 'tontines');
            }
            return redirect('/mon-espace')->with('error', 'Vous ne pouvez plus changer votre mode de réception (moins de 3 jours avant le tour).')->with('section', 'notifications');
        }

        $tour->update(['mode_reception' => $request->mode_reception]);

        $membre = $tour->membre;
        $modeTexte = $request->mode_reception === 'presentiel' ? 'en présentiel' : 'via un opérateur';

        NotificationAdmin::create([
            'membre_id' => $membre->id,
            'titre'     => 'Choix du mode de réception',
            'message'   => $membre->nom . ' ' . $membre->prenom . ' a choisi de recevoir son tour du ' . \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y') . ' ' . $modeTexte . '.',
            'lu'        => false,
        ]);

        $role = Session::get('role');
        if ($role === 'gerant') {
            return redirect('/gerant')->with('success', 'Mode de réception enregistré !')->with('section', 'tontines');
        }
        return redirect('/mon-espace')->with('success', 'Mode de réception enregistré !')->with('section', 'notifications');
    }
    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $date = \Carbon\Carbon::parse($tour->date_tour)->format('d/m/Y');

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Tour supprimé par l\'admin',
                'L\'administrateur a supprimé le tour du ' . $date . '.'
            );
        } else {
            $this->notifierAdmin(
                'Tour supprimé',
                Session::get('membre_nom') . ' a supprimé le tour du ' . $date . '.'
            );
        }

        \App\Models\Cotisation::where('tour_id', $tour->id)->delete();
        $tour->delete();

        return back()->with('success', 'Tour supprimé avec succès !')->with('section', 'tours');
    }

    public function supprimerSelection(Request $request)
    {
        $ids = $request->input('tour_ids', []);
        $tours = Tour::whereIn('id', $ids)->get();

        foreach ($tours as $tour) {
            \App\Models\Cotisation::where('tour_id', $tour->id)->delete();
            $tour->delete();
        }

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Tours supprimés par l\'admin',
                'L\'administrateur a supprimé ' . count($ids) . ' tour(s).'
            );
        } else {
            $this->notifierAdmin(
                'Tours supprimés',
                Session::get('membre_nom') . ' a supprimé ' . count($ids) . ' tour(s).'
            );
        }

        return back()->with('success', 'Tours supprimés avec succès !')->with('section', 'tours');
    }

    public function supprimerTout()
    {
        \App\Models\Cotisation::whereNotNull('tour_id')->delete();
        Tour::all()->each(fn($tour) => $tour->delete());

        // ✅ Notifications
        if (Session::get('is_admin')) {
            $this->notifierGerants(
                'Tous les tours supprimés',
                'L\'administrateur a supprimé tous les tours.'
            );
        } else {
            $this->notifierAdmin(
                'Tous les tours supprimés',
                Session::get('membre_nom') . ' a supprimé tous les tours.'
            );
        }

        return back()->with('success', 'Tous les tours ont été supprimés !')->with('section', 'tours');
    }
}