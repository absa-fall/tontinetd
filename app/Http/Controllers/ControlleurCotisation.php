<?php

namespace App\Http\Controllers;
use App\Models\Cotisation;
use App\Models\Membre;
use Illuminate\Http\Request;

class ControlleurCotisation extends Controller
{
    public function index()
    {
        $membres     = Membre::all();
        $cotisations = Cotisation::all();
        return view('cotisation', compact('membres', 'cotisations'));
    }

    public function store(Request $request)
    {
        Cotisation::create($request->all());
        return redirect('/dashboard')
            ->with('success', 'Cotisation ajoutée avec succès !')
            ->with('section', 'cotisations');
    }

    public function update(Request $request, $id)
    {
        $cotisation = Cotisation::findOrFail($id);
        $cotisation->update($request->except(['_token', '_method']));
        return redirect('/dashboard')
            ->with('success', 'Cotisation modifiée avec succès !')
            ->with('section', 'cotisations');
    }

    public function destroy($id)
    {
        Cotisation::findOrFail($id)->delete();
        return redirect('/dashboard')
            ->with('success', 'Cotisation supprimée !')
            ->with('section', 'cotisations');
    }
}