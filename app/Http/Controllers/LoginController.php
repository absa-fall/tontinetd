<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Session::get('is_admin')) return redirect('/dashboard');
        if (Session::get('membre_id')) return redirect('/mon-espace');
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // ADMIN
        if ($request->email === 'admin@tontinetd.sn' && $request->password === 'TontineTD@2026') {
            Session::put('is_admin', true);
            return redirect('/dashboard');
        }

        // MEMBRE
        $membre = Membre::where('email', $request->email)->first();

        if (!$membre || !Hash::check($request->password, $membre->password)) {
            return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
        }

        Session::put('membre_id', $membre->id);
        Session::put('membre_nom', $membre->nom . ' ' . $membre->prenom);
        Session::put('is_admin', false);

        return redirect('/mon-espace');
    }

    public function showRegister()
    {
        if (Session::get('membre_id')) return redirect('/mon-espace');
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'email'          => 'required|email|unique:membres,email',
            'adresse'        => 'required|string|max:255',
            'telephone'      => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'password'       => 'required|min:6|confirmed',
        ]);

        $membre = Membre::create([
            'nom'            => $request->nom,
            'prenom'         => $request->prenom,
            'email'          => $request->email,
            'adresse'        => $request->adresse,
            'telephone'      => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'password'       => Hash::make($request->password),
        ]);

        Session::put('membre_id', $membre->id);
        Session::put('membre_nom', $membre->nom . ' ' . $membre->prenom);
        Session::put('is_admin', false);

        return redirect('/mon-espace')->with('success', 'Compte créé avec succès !');
    }

    public function logout()
    {
        Session::forget(['membre_id', 'membre_nom', 'is_admin']);
        return redirect('/login');
    }

    public function changerMotDePasse(Request $request)
    {
        $membre = Membre::find(Session::get('membre_id'));
        if (!$membre) return redirect('/login');

        $request->validate([
            'ancien_password' => 'required',
            'password'        => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->ancien_password, $membre->password)) {
            return back()->withErrors(['ancien_password' => 'Ancien mot de passe incorrect.']);
        }

        $membre->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe modifié avec succès !');
    }
}