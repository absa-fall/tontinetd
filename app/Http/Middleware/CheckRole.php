<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $isAdmin = Session::get('is_admin');
        $role    = Session::get('role');
        $membreId = Session::get('membre_id');

        // Pas connecté du tout
        if (!$isAdmin && !$membreId) {
            return redirect('/login');
        }

        // Super admin
        if ($isAdmin === true && in_array('super_admin', $roles)) {
            return $next($request);
        }

        // Membre avec rôle
        if ($role && in_array($role, $roles)) {
            return $next($request);
        }

        // Mauvais rôle → rediriger selon son vrai rôle
        if ($isAdmin === true) return redirect('/dashboard');
        if (in_array($role, ['gerant', 'admin'])) return redirect('/gerant');
        return redirect('/mon-espace');
    }
}