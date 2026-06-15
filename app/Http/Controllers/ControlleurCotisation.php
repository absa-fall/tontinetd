<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use Illuminate\Http\Request;

class ControlleurCotisation extends Controller
{
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
            return redirect('/dashboard')
                ->with('error', 'Aucun tour en attente pour cette tontine.')
                ->with('section', 'cotisations');
        }

        if (now()->toDateString() > $tourEnCours->date_tour) {
            return redirect('/dashboard')
                ->with('error', 'La date de ce tour est dépassée, vous ne pouvez plus cotiser.')
                ->with('section', 'cotisations');
        }

        Cotisation::create([
            'montant'         => $request->montant,
            'date_cotisation' => $request->date_cotisation,
            'membre_id'       => $request->membre_id,
            'tontine_id'      => $request->tontine_id,
            'tour_id'         => $tourEnCours->id,
            'ajout_par'       => 'admin',
            'moyen_paiement'  => 'cash',
        ]);

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

        return redirect('/gerant')
            ->with('success', 'Cotisation modifiée avec succès !')
            ->with('section', 'cotisations');
    }

    public function destroy($id)
    {
        $cotisation = Cotisation::findOrFail($id);
        $cotisation->delete();

        return back()->with('success', 'Cotisation supprimée avec succès !')->with('section', 'cotisations');
    }

    public function supprimerSelection(Request $request)
    {
        $ids = $request->input('cotisation_ids', []);
        Cotisation::whereIn('id', $ids)->delete();

        return back()->with('success', 'Cotisations supprimées avec succès !')->with('section', 'cotisations');
    }

    public function supprimerTout()
    {
        Cotisation::truncate();

        return back()->with('success', 'Toutes les cotisations ont été supprimées !')->with('section', 'cotisations');
    }
}