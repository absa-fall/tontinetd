<?php

namespace App\Http\Controllers;
use App\Models\Tontine;
use Illuminate\Http\Request;

class ControlleurTontine extends Controller
{
    public function index()
    {
        $tontines = Tontine::all();
        return view('tontine', compact('tontines'));
    }

    public function store(Request $request)
    {
        Tontine::create($request->all());
        return redirect('/dashboard')
            ->with('success', 'Tontine créée avec succès !')
            ->with('section', 'tontines');
    }

    public function update(Request $request, $id)
    {
        $tontine = Tontine::findOrFail($id);
        $tontine->update($request->except(['_token', '_method']));
        return redirect('/dashboard')
            ->with('success', 'Tontine modifiée avec succès !')
            ->with('section', 'tontines');
    }

    public function destroy($id)
    {
        Tontine::findOrFail($id)->delete();
        return redirect('/dashboard')
            ->with('success', 'Tontine supprimée !')
            ->with('section', 'tontines');
    }
}