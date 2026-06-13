<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Cotisation;
use App\Models\Tour;
use App\Models\Tontine;
use App\Models\NotificationMembre;
use App\Models\NotificationAdmin;
use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AdminExportController extends Controller
{
    // Export PDF d'un membre
    public function pdfMembre($id)
    {
        $membre = Membre::with('cotisations')->findOrFail($id);
        $pdf = Pdf::loadView('exports.transactions-pdf', compact('membre'));
        return $pdf->download('transactions-' . $membre->nom . '.pdf');
    }

    // Export Excel d'un membre
    public function excelMembre($id)
    {
        $membre = Membre::with('cotisations')->findOrFail($id);
        return Excel::download(new TransactionsExport($membre), 'transactions-' . $membre->nom . '.xlsx');
    }

    // Export PDF global
    public function pdfGlobal()
    {
        $membres     = Membre::with('cotisations')->get();
        $cotisations = Cotisation::with('membre')->get();
        $tours       = Tour::all();
        $tontines    = Tontine::all();
        $pdf = Pdf::loadView('exports.global-pdf', compact('membres', 'cotisations', 'tours', 'tontines'));
        return $pdf->download('rapport-global.pdf');
    }

    // Envoyer notification à tous les membres pour un tour
    public function notifierTour($tourId)
    {
        $tour    = Tour::findOrFail($tourId);
        $membres = Membre::all();

        foreach ($membres as $membre) {
            NotificationMembre::create([
                'membre_id' => $membre->id,
                'titre'     => 'Nouveau tour programmé',
                'message'   => 'Un tour a été programmé pour le ' . \Carbon\Carbon::parse($tour->date_tour)->locale('fr')->isoFormat('dddd D MMMM YYYY') . '. État : ' . ($tour->etat === 'terminer' ? 'Terminé' : 'En attente') . '.',
            ]);
        }

        return back()->with('success', 'Notifications envoyées à tous les membres !');
    }

    // Approuver un membre
    public function approuverMembre($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'approuve']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => '✅ Compte approuvé',
            'message'   => 'Félicitations ' . $membre->prenom . ' ! Votre compte a été approuvé par l\'administrateur. Vous pouvez maintenant accéder à votre espace membre.',
            'lu'        => false,
        ]);

        NotificationAdmin::where('membre_id', $membre->id)->update(['lu' => true]);

        return back()->with('success', $membre->prenom . ' ' . $membre->nom . ' a été approuvé avec succès !');
    }

    // Refuser un membre
    public function refuserMembre($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'refuse']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => '❌ Compte refusé',
            'message'   => 'Votre demande d\'inscription a été refusée par l\'administrateur. Contactez-nous pour plus d\'informations.',
            'lu'        => false,
        ]);

        NotificationAdmin::where('membre_id', $membre->id)->update(['lu' => true]);

        return back()->with('success', $membre->prenom . ' ' . $membre->nom . ' a été refusé.');
    }

    // Approuver tous les membres en attente
    public function approuverTout()
    {
        $membres = Membre::where('statut', 'en_attente')->get();

        foreach ($membres as $membre) {
            $membre->update(['statut' => 'approuve']);

            NotificationMembre::create([
                'membre_id' => $membre->id,
                'titre'     => '✅ Compte approuvé',
                'message'   => 'Félicitations ' . $membre->prenom . ' ! Votre compte a été approuvé par l\'administrateur. Vous pouvez maintenant accéder à votre espace membre.',
                'lu'        => false,
            ]);
        }

        NotificationAdmin::whereIn('membre_id', $membres->pluck('id'))->update(['lu' => true]);

        return back()->with('success', $membres->count() . ' membre(s) approuvé(s) avec succès !');
    }

    // Refuser tous les membres en attente
    public function refuserTout()
    {
        $membres = Membre::where('statut', 'en_attente')->get();

        foreach ($membres as $membre) {
            $membre->update(['statut' => 'refuse']);

            NotificationMembre::create([
                'membre_id' => $membre->id,
                'titre'     => '❌ Compte refusé',
                'message'   => 'Votre demande d\'inscription a été refusée par l\'administrateur. Contactez-nous pour plus d\'informations.',
                'lu'        => false,
            ]);
        }

        NotificationAdmin::whereIn('membre_id', $membres->pluck('id'))->update(['lu' => true]);

        return back()->with('success', $membres->count() . ' membre(s) refusé(s).');
    }

    // Rendre un membre admin d'une tontine
    public function rendreAdmin($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        // Remettre tous les membres de la tontine en 'membre'
        $tontine->membres()->updateExistingPivot(
            $tontine->membres->pluck('id')->toArray(),
            ['role' => 'membre']
        );

        // Rendre le membre choisi admin
        $tontine->membres()->updateExistingPivot($membreId, ['role' => 'admin']);

        $membre = Membre::findOrFail($membreId);

        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => '⭐ Vous êtes admin',
            'message'   => 'Vous avez été nommé administrateur de la tontine "' . $tontine->nom . '" par l\'administrateur principal.',
            'lu'        => false,
        ]);

        return back()->with('success', $membre->prenom . ' ' . $membre->nom . ' est maintenant admin de la tontine "' . $tontine->nom . '" !');
    }

    // Marquer toutes les notifications admin comme lues
    public function marquerToutLu()
    {
        NotificationAdmin::where('lu', false)->update(['lu' => true]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    // Supprimer toutes les notifications admin
    public function supprimerToutNotifs()
    {
        NotificationAdmin::truncate();
        return back()->with('success', 'Toutes les notifications ont été supprimées.');
    }
}