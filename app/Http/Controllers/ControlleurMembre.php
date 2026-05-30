<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ControlleurMembre extends Controller
{
    public function index()
    {
        $membres = Membre::all();
        return view('membre', compact('membres'));
    }

    public function show($id)
    {
        $membre = Membre::with(['cotisations', 'notifications'])->findOrFail($id);
        return view('membre-detail', compact('membre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'email'          => 'required|email|unique:membres,email',
            'telephone'      => 'required|string|max:20',
            'adresse'        => 'required|string|max:255',
            'date_naissance' => 'required|date',
        ]);

        $passwordParDefaut = '1234' . $request->nom;

        Membre::create([
            'nom'            => $request->nom,
            'prenom'         => $request->prenom,
            'email'          => $request->email,
            'telephone'      => $request->telephone,
            'adresse'        => $request->adresse,
            'password'       => Hash::make($passwordParDefaut),
            'date_naissance' => $request->date_naissance,
        ]);

        return redirect('/dashboard')
            ->with('success', 'Membre ajouté ! Mot de passe par défaut : ' . $passwordParDefaut)
            ->with('section', 'membres');
    }

    public function update(Request $request, $id)
    {
        $membre = Membre::findOrFail($id);

        $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'email'          => 'required|email|unique:membres,email,' . $id,
            'telephone'      => 'required|string|max:20',
            'adresse'        => 'required|string|max:255',
            'date_naissance' => 'required|date',
        ]);

        $data = [
            'nom'            => $request->nom,
            'prenom'         => $request->prenom,
            'email'          => $request->email,
            'telephone'      => $request->telephone,
            'adresse'        => $request->adresse,
            'date_naissance' => $request->date_naissance,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $membre->update($data);

        return redirect('/dashboard')
            ->with('success', 'Membre mis à jour !')
            ->with('section', 'membres');
    }

    public function destroy($id)
    {
        Membre::findOrFail($id)->delete();
        return redirect('/dashboard')
            ->with('success', 'Membre supprimé !')
            ->with('section', 'membres');
    }
}