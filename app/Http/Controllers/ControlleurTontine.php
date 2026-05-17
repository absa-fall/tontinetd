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
        return redirect('/');
    }

    public function update(Request $request, $id)
    {
        $tontine = Tontine::findOrFail($id);
        $tontine->update($request->except(['_token', '_method']));
        return redirect('/');
    }

    public function destroy($id)
    {
        Tontine::findOrFail($id)->delete();
        return redirect('/');
    }
}