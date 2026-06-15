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
{private function redirectRole($message, $section = 'home')
{
    $role = Session::get('role');
    if ($role === 'gerant' || $role === 'admin') {
        return redirect('/gerant')
            ->with('success', $message)
            ->with('section', $section);
    }
    return redirect('/dashboard')
        ->with('success', $message)
        ->with('section', $section);
}
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
                'titre'     => 'Nouveau tour programme',
                'message'   => 'Un tour a ete programme pour le ' . \Carbon\Carbon::parse($tour->date_tour)->locale('fr')->isoFormat('dddd D MMMM YYYY') . '. Etat : ' . ($tour->etat === 'terminer' ? 'Termine' : 'En attente') . '.',
            ]);
        }

        return $this->redirectRole('Notifications envoyees a tous les membres !', 'tours');
    }

    public function approuverMembre($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'approuve']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte approuve',
            'message'   => 'Felicitations ' . $membre->prenom . ' ! Votre compte a ete approuve. Vous pouvez maintenant acceder a votre espace membre.',
            'lu'        => false,
        ]);

        NotificationAdmin::where('membre_id', $membre->id)->update(['lu' => true]);

        return $this->redirectRole($membre->prenom . ' a ete approuve !', 'inscriptions');
    }

    public function refuserMembre($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'refuse']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte refuse',
            'message'   => 'Votre demande d\'inscription a ete refusee. Contactez-nous pour plus d\'informations.',
            'lu'        => false,
        ]);

        NotificationAdmin::where('membre_id', $membre->id)->update(['lu' => true]);

        return $this->redirectRole($membre->prenom . ' a ete refuse.', 'inscriptions');
    }

    public function approuverTout()
    {
        $membres = Membre::where('statut', 'en_attente')->get();

        foreach ($membres as $membre) {
            $membre->update(['statut' => 'approuve']);
            NotificationMembre::create([
                'membre_id' => $membre->id,
                'titre'     => 'Compte approuve',
                'message'   => 'Felicitations ' . $membre->prenom . ' ! Votre compte a ete approuve.',
                'lu'        => false,
            ]);
        }

        NotificationAdmin::whereIn('membre_id', $membres->pluck('id'))->update(['lu' => true]);

        return $this->redirectRole($membres->count() . ' membre(s) approuve(s) !', 'inscriptions');
    }

    public function refuserTout()
    {
        $membres = Membre::where('statut', 'en_attente')->get();

        foreach ($membres as $membre) {
            $membre->update(['statut' => 'refuse']);
            NotificationMembre::create([
                'membre_id' => $membre->id,
                'titre'     => 'Compte refuse',
                'message'   => 'Votre demande d\'inscription a ete refusee.',
                'lu'        => false,
            ]);
        }

        NotificationAdmin::whereIn('membre_id', $membres->pluck('id'))->update(['lu' => true]);

        return $this->redirectRole($membres->count() . ' membre(s) refuse(s).', 'inscriptions');
    }

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
            'titre'     => 'Vous etes admin de tontine',
            'message'   => 'Vous avez ete nomme administrateur de la tontine "' . $tontine->nom . '".',
            'lu'        => false,
        ]);

        return $this->redirectRole($membre->prenom . ' est maintenant admin de "' . $tontine->nom . '" !', 'tontines');
    }

    public function rendreAdmin($id)
    {
        if (Session::get('role') !== 'super_admin') {
            return redirect('/dashboard')->with('error', 'Action non autorisee.')->with('section', 'membres');
        }

        $membre = Membre::findOrFail($id);
        $membre->update(['role' => 'admin']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Vous etes maintenant admin',
            'message'   => 'Felicitations ' . $membre->prenom . ' ! Vous avez ete nomme administrateur de TontineTD.',
            'lu'        => false,
        ]);

        return $this->redirectRole($membre->prenom . ' est maintenant admin !', 'membres');
    }

    public function rendreGerant($id)
    {
        if (Session::get('role') !== 'super_admin') {
            return redirect('/dashboard')->with('error', 'Action non autorisee.')->with('section', 'membres');
        }

        $membre = Membre::findOrFail($id);
        $membre->update(['role' => 'gerant']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Vous etes maintenant gerant',
            'message'   => 'Felicitations ' . $membre->prenom . ' ! Vous avez ete nomme gerant de TontineTD.',
            'lu'        => false,
        ]);

        return $this->redirectRole($membre->prenom . ' est maintenant gerant !', 'membres');
    }

    public function retirerRole($id)
    {
        if (Session::get('role') !== 'super_admin') {
            return redirect('/dashboard')->with('error', 'Action non autorisee.')->with('section', 'membres');
        }

        $membre = Membre::findOrFail($id);
        $membre->update(['role' => 'membre']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Role retire',
            'message'   => 'Votre role a ete retire par l\'administrateur. Vous etes maintenant membre simple.',
            'lu'        => false,
        ]);

        return $this->redirectRole('Role retire pour ' . $membre->prenom . '.', 'membres');
    }

    public function activer($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'approuve']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte active',
            'message'   => 'Votre compte a ete active. Vous pouvez maintenant vous connecter.',
            'lu'        => false,
        ]);

        return $this->redirectRole($membre->prenom . ' a ete active !', 'membres');
    }

    public function desactiver($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->update(['statut' => 'refuse']);

        NotificationMembre::create([
            'membre_id' => $membre->id,
            'titre'     => 'Compte desactive',
            'message'   => 'Votre compte a ete desactive. Contactez l\'administrateur pour plus d\'informations.',
            'lu'        => false,
        ]);

        return $this->redirectRole($membre->prenom . ' a ete desactive.', 'membres');
    }

    public function marquerToutLu()
    {
        NotificationAdmin::where('lu', false)->update(['lu' => true]);
        return $this->redirectRole('Toutes les notifications ont ete marquees comme lues.', 'inscriptions');
    }

    public function supprimerToutNotifs()
    {
        NotificationAdmin::truncate();
        return $this->redirectRole('Toutes les notifications ont ete supprimees.', 'inscriptions');
    }
}