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

        Cotisation::create([
            'montant'         => $request->montant,
            'date_cotisation' => $request->date_cotisation,
            'membre_id'       => $request->membre_id,
            'tontine_id'      => $request->tontine_id,
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