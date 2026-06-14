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

        // Vérifier le tour en cours pour cette tontine
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

        return redirect('/dashboard')->with('success', 'Cotisation ajoutée avec succès !')->with('section', 'cotisations');
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

        return redirect('/dashboard')->with('success', 'Cotisation modifiée avec succès !')->with('section', 'cotisations');
    }

    public function destroy($id)
    {
        $cotisation = Cotisation::findOrFail($id);
        $cotisation->delete();

        return redirect('/dashboard')->with('success', 'Cotisation supprimée avec succès !')->with('section', 'cotisations');
    }
}