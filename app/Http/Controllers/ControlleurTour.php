<?php

namespace App\Http\Controllers;
use App\Models\Tour;
use Illuminate\Http\Request;

class ControlleurTour extends Controller
{
    public function index()
    {
        $tours = Tour::all();
        return view('tour', compact('tours'));
    }

    public function store(Request $request)
    {
        Tour::create($request->all());
        return redirect('/');
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $tour->update($request->except(['_token', '_method']));
        return redirect('/');
    }

    public function destroy($id)
    {
        Tour::findOrFail($id)->delete();
        return redirect('/');
    }
}