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
use Illuminate\Support\Facades\Session;

class AdminExportController extends Controller
{
    public function pdfMembre($id)
    {
        $membre = Membre::with('cotisations')->findOrFail($id);
        $pdf = Pdf::loadView('exports.transactions-pdf', compact('membre'));
        return $pdf->download('transactions-' . $membre->nom . '.pdf');
    }

    public function excelMembre($id)
    {
        $membre = Membre::with('cotisations')->findOrFail($id);
        return Excel::download(new TransactionsExport($membre), 'transactions-' . $membre->nom . '.xlsx');
    }

    public function pdfGlobal()
    {
        $membres     = Membre::with('cotisations')->get();
        $cotisations = Cotisation::with('membre')->get();
        $tours       = Tour::all();
        $tontines    = Tontine::all();
        $pdf = Pdf::loadView('exports.global-pdf', compact('membres', 'cotisations', 'tours', 'tontines'));
        return $pdf->download('rapport-global.pdf');
    }

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

    public function approuverMembre($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'approuve']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte approuvé',
            'message'   => 'Félicitations ' . $membre->prenom . ' ! Votre compte a été approuvé. Vous pouvez maintenant accéder à votre espace membre.',
            'lu'        => false,
        ]);

        NotificationAdmin::where('membre_id', $membre->id)->update(['lu' => true]);

        return back()->with('success', $membre->prenom . ' a été approuvé !')->with('section', 'inscriptions');
    }

    public function refuserMembre($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'refuse']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte refusé',
            'message'   => 'Votre demande d\'inscription a été refusée. Contactez-nous pour plus d\'informations.',
            'lu'        => false,
        ]);

        NotificationAdmin::where('membre_id', $membre->id)->update(['lu' => true]);

        return back()->with('success', $membre->prenom . ' a été refusé.')->with('section', 'inscriptions');
    }

    public function approuverTout()
    {
        $membres = Membre::where('statut', 'en_attente')->get();

        foreach ($membres as $membre) {
            $membre->update(['statut' => 'approuve']);
            NotificationMembre::create([
                'membre_id' => $membre->id,
                'titre'     => 'Compte approuvé',
                'message'   => 'Félicitations ' . $membre->prenom . ' ! Votre compte a été approuvé.',
                'lu'        => false,
            ]);
        }

        NotificationAdmin::whereIn('membre_id', $membres->pluck('id'))->update(['lu' => true]);

        return back()->with('success', $membres->count() . ' membre(s) approuvé(s) !')->with('section', 'inscriptions');
    }

    public function refuserTout()
    {
        $membres = Membre::where('statut', 'en_attente')->get();

        foreach ($membres as $membre) {
            $membre->update(['statut' => 'refuse']);
            NotificationMembre::create([
                'membre_id' => $membre->id,
                'titre'     => 'Compte refusé',
                'message'   => 'Votre demande d\'inscription a été refusée.',
                'lu'        => false,
            ]);
        }

        NotificationAdmin::whereIn('membre_id', $membres->pluck('id'))->update(['lu' => true]);

        return back()->with('success', $membres->count() . ' membre(s) refusé(s).')->with('section', 'inscriptions');
    }

    // Rendre admin d'une tontine (table pivot)
    public function rendreAdminTontine($tontineId, $membreId)
    {
        $tontine = Tontine::findOrFail($tontineId);
        $tontine->membres()->updateExistingPivot(
            $tontine->membres->pluck('id')->toArray(),
            ['role' => 'membre']
        );
        $tontine->membres()->updateExistingPivot($membreId, ['role' => 'admin']);

        $membre = Membre::findOrFail($membreId);
        NotificationMembre::create([
            'membre_id' => $membreId,
            'titre'     => 'Vous êtes admin de tontine',
            'message'   => 'Vous avez été nommé administrateur de la tontine "' . $tontine->nom . '".',
            'lu'        => false,
        ]);

        return back()->with('success', $membre->prenom . ' est maintenant admin de "' . $tontine->nom . '" !')->with('section', 'tontines');
    }

    // Rendre admin global (accès dashboard)
    public function rendreAdmin($id)
    {
        if (Session::get('role') !== 'super_admin') {
            return redirect()->back()->with('error', 'Action non autorisée.')->with('section', 'membres');
        }

        $membre = Membre::findOrFail($id);
        $membre->update(['role' => 'admin']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Vous êtes maintenant admin',
            'message'   => 'Félicitations ' . $membre->prenom . ' ! Vous avez été nommé administrateur de TontineTD.',
            'lu'        => false,
        ]);

        return back()->with('success', $membre->prenom . ' est maintenant admin !')->with('section', 'membres');
    }

    // Rendre gérant (accès dashboard gérant)
    public function rendreGerant($id)
    {
        if (Session::get('role') !== 'super_admin') {
            return redirect()->back()->with('error', 'Action non autorisée.')->with('section', 'membres');
        }

        $membre = Membre::findOrFail($id);
        $membre->update(['role' => 'gerant']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Vous êtes maintenant gérant',
            'message'   => 'Félicitations ' . $membre->prenom . ' ! Vous avez été nommé gérant de TontineTD.',
            'lu'        => false,
        ]);

        return back()->with('success', $membre->prenom . ' est maintenant gérant !')->with('section', 'membres');
    }

    // Retirer le rôle (redevient membre simple)
    public function retirerRole($id)
    {
        if (Session::get('role') !== 'super_admin') {
            return redirect()->back()->with('error', 'Action non autorisée.')->with('section', 'membres');
        }

        $membre = Membre::findOrFail($id);
        $membre->update(['role' => 'membre']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Rôle retiré',
            'message'   => 'Votre rôle a été retiré par l\'administrateur. Vous êtes maintenant membre simple.',
            'lu'        => false,
        ]);

        return back()->with('success', 'Rôle retiré pour ' . $membre->prenom . '.')->with('section', 'membres');
    }

    // Activer un membre
    public function activer($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'approuve']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte activé',
            'message'   => 'Votre compte a été activé. Vous pouvez maintenant vous connecter.',
            'lu'        => false,
        ]);

        return back()->with('success', $membre->prenom . ' a été activé !')->with('section', 'membres');
    }

    // Désactiver un membre
    public function desactiver($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'desactive']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte désactivé',
            'message'   => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            'lu'        => false,
        ]);

        return back()->with('success', $membre->prenom . ' a été désactivé.')->with('section', 'membres');
    }

    public function marquerToutLu()
    {
        NotificationAdmin::where('lu', false)->update(['lu' => true]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.')->with('section', 'inscriptions');
    }

    public function supprimerToutNotifs()
    {
        NotificationAdmin::truncate();
        return back()->with('success', 'Toutes les notifications ont été supprimées.')->with('section', 'inscriptions');
    }
}