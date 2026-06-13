<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Cotisation;
use App\Models\NotificationMembre;
use App\Exports\TransactionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EspaceMembreController extends Controller
{
    private function getMembre()
    {
        $id = Session::get('membre_id');
        if (!$id) return null;
        
        return Membre::with([
            'cotisations.tontine',
            'tontines.tours',
            'tontines.membres',
            'notifications'
        ])->find($id);
    }

    public function index()
    {
        $membre = $this->getMembre();
        if (!$membre) return redirect('/login');
        
        $membres = Membre::all();
       
$admin = Membre::whereHas('tontines', function($q) {
    $q->where('membre_tontine.role', 'admin');
})->first();
        return view('espace-membre', compact('membre', 'membres', 'admin'));
    }

    public function ajouterCotisation(Request $request)
    {
        $membre = $this->getMembre();
        if (!$membre) return redirect('/login');

        $request->validate([
            'montant'         => 'required|numeric|min:1',
            'date_cotisation' => 'required|date',
            'tontine_id'      => 'required|exists:tontines,id',
        ]);

        Cotisation::create([
            'montant'         => $request->montant,
            'date_cotisation' => $request->date_cotisation,
            'membre_id'       => $membre->id,
            'tontine_id'      => $request->tontine_id,
            'ajout_par'       => 'membre',
            'moyen_paiement'  => $request->moyen_paiement ?? 'cash',
        ]);

        return back()->with('success', 'Cotisation ajoutée avec succès !');
    }

    public function marquerLu($id)
    {
        $notif = NotificationMembre::find($id);
        if ($notif && $notif->membre_id === Session::get('membre_id')) {
            $notif->update(['lu' => true]);
        }
        return back();
    }

    public function exportPdf()
    {
        $membre = $this->getMembre();
        if (!$membre) return redirect('/login');

        $pdf = Pdf::loadView('exports.transactions-pdf', compact('membre'));
        return $pdf->download('transactions-' . $membre->nom . '.pdf');
    }

    public function exportExcel()
    {
        $membre = $this->getMembre();
        if (!$membre) return redirect('/login');

        return Excel::download(
            new TransactionsExport($membre),
            'transactions-' . $membre->nom . '.xlsx'
        );
    }
}