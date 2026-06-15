<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\NotificationMembre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControlleurMembre extends Controller
{
   private function redirectRole($message, $section = 'membres')
{
    $role = Session::get('role');
    if ($role === 'gerant' || $role === 'admin') {
        return redirect('/gerant')
            ->with('success', $message)
            ->with('section', $section);
    }
    return redirect('/dashboard')
        ->with('success', $message)
        ->with('section', $section);
}

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
            'statut'         => 'approuve',
        ]);

        $membres = Membre::where('id', '!=', $nouveau->id)->get();
        foreach ($membres as $m) {
            NotificationMembre::create([
                'membre_id' => $m->id,
                'titre'     => 'Nouveau membre',
                'message'   => $nouveau->prenom . ' ' . $nouveau->nom . ' a rejoint TontineTD.',
                'lu'        => false,
            ]);
        }

       return back()->with('success', 'Membre ajouté avec succès ! Mot de passe par défaut : password123 (le membre peut le modifier dans Sécurité après connexion).')->with('section', 'membres');
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
        $membre->update([
            'nom'            => $request->nom,
            'prenom'         => $request->prenom,
            'email'          => $request->email,
            'telephone'      => $request->telephone,
            'adresse'        => $request->adresse,
            'date_naissance' => $request->date_naissance,
        ]);

        return $this->redirectRole($membre->prenom . ' a ete modifie avec succes !', 'membres');
    }

    public function destroy($id)
    {
        $membre = Membre::findOrFail($id);
        $nom = $membre->prenom . ' ' . $membre->nom;
        $membre->delete();

        return $this->redirectRole($nom . ' a ete supprime.', 'membres');
    }

    public function show($id)
    {
        $membre = Membre::with(['cotisations', 'tontines', 'notifications'])->findOrFail($id);
        return view('membres.show', compact('membre'));
    }
    public function supprimerSelection(Request $request)
{
    $ids = $request->input('membre_ids', []);
    Membre::whereIn('id', $ids)->delete();

    return $this->redirectRole('Membres supprimés avec succès !', 'membres');
}

public function supprimerTout()
{
    Membre::where('role', 'membre')->delete();

    return $this->redirectRole('Tous les membres ont été supprimés !', 'membres');
}
}