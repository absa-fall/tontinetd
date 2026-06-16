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

    $admin = Membre::where('role', 'gerant')
        ->orWhere('role', 'admin')
        ->first();

    $tontinesGerees = $membre->tontines->filter(function($t) {
        return $t->pivot->role === 'admin';
    })->map(function($t) {
        return \App\Models\Tontine::with([
            'membres',
            'membresApprouves',
            'demandesEnAttente',
            'tours'
        ])->find($t->id);
    });

    $cotisations   = $membre->cotisations;
    $tontines      = $membre->tontines;
    $notifications = $membre->notifications()->orderBy('created_at', 'desc')->get();

    return view('espace-membre', compact(
        'membre',
        'membres',
        'admin',
        'tontinesGerees',
        'cotisations',
        'tontines',
        'notifications'
    ));

}
   public function marquerLu($id)
    {
        $notif = NotificationMembre::find($id);
        if ($notif && $notif->membre_id === Session::get('membre_id')) {
            $notif->update(['lu' => true]);
        }
        return back()->with('section', 'notifications');
    }
    public function marquerToutLu()
    {
        NotificationMembre::where('membre_id', Session::get('membre_id'))
            ->where('lu', false)
            ->update(['lu' => true]);

        return back()->with('section', 'notifications');
    }

    public function supprimerToutNotifs()
    {
        NotificationMembre::where('membre_id', Session::get('membre_id'))->delete();

        return back()->with('section', 'notifications');
    }

    public function supprimerSelectionNotifs(Request $request)
    {
        $ids = $request->input('notif_ids', []);

        NotificationMembre::where('membre_id', Session::get('membre_id'))
            ->whereIn('id', $ids)
            ->delete();

        return back()->with('section', 'notifications');
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
 public function voirTontines()
{
    $membreId = Session::get('membre_id');

    $tontines = \App\Models\Tontine::with(['membresApprouves', 'membres'])->get();

    $tontinesduMembre = \App\Models\Tontine::whereHas('membres', function ($q) use ($membreId) {
        $q->where('membre_id', $membreId);
    })->pluck('id')->toArray();

     return view('espace-membre.tontines', compact('tontines', 'tontinesduMembre', 'membreId'));
}
}