<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Cotisation;
use App\Models\Tour;
use App\Models\Tontine;
use App\Models\NotificationMembre;
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
                'titre'     => ' Nouveau tour programmé',
                'message'   => 'Un tour a été programmé pour le ' . \Carbon\Carbon::parse($tour->date_tour)->locale('fr')->isoFormat('dddd D MMMM YYYY') . '. État : ' . ($tour->etat === 'terminer' ? 'Terminé' : 'En attente') . '.',
            ]);
        }

        return back()->with('success', 'Notifications envoyées à tous les membres !');
    }
}