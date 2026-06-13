<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\NotificationMembre;
use Illuminate\Http\Request;

class ControlleurMembre extends Controller
{
    public function index()
    {
        $membres = Membre::all();
        return view('membres.index', compact('membres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'email'          => 'required|email|unique:membres',
            'telephone'      => 'required|string|max:20',
            'adresse'        => 'required|string',
            'date_naissance' => 'required|date',
        ]);

        $nouveau = Membre::create([
            'nom'            => $request->nom,
            'prenom'         => $request->prenom,
            'email'          => $request->email,
            'password'       => bcrypt('password123'),
            'telephone'      => $request->telephone,
            'adresse'        => $request->adresse,
            'date_naissance' => $request->date_naissance,
        ]);

        // Notifier tous les membres existants (sauf le nouveau)
        $membres = Membre::where('id', '!=', $nouveau->id)->get();
        foreach ($membres as $m) {
            NotificationMembre::create([
                'membre_id' => $m->id,
                'titre'     => 'Nouveau membre',
                'message'   => $nouveau->prenom . ' ' . $nouveau->nom . ' a rejoint TontineTD.',
                'lu'        => false,
            ]);
        }

        return redirect('/dashboard')->with('success', 'Membre ajouté avec succès !')->with('section', 'membres');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'email'          => 'required|email|unique:membres,email,' . $id,
            'telephone'      => 'required|string|max:20',
            'adresse'        => 'required|string',
            'date_naissance' => 'required|date',
        ]);

        $membre = Membre::findOrFail($id);
        $membre->update($request->all());

        return redirect('/dashboard')->with('success', 'Membre modifié avec succès !')->with('section', 'membres');
    }

    public function destroy($id)
    {
        $membre = Membre::findOrFail($id);
        $membre->delete();

        return redirect('/dashboard')->with('success', 'Membre supprimé avec succès !')->with('section', 'membres');
    }

    public function show($id)
    {
        $membre = Membre::with(['cotisations', 'tontines', 'notifications'])->findOrFail($id);
        return view('membres.show', compact('membre'));
    }
}