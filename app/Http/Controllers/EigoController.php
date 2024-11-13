<?php

namespace App\Http\Controllers;

use App\Models\Eigo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EigoController extends Controller
{
    public function index()
    {
        return Inertia::render('Eigo/Index', [
            'movies' => Eigo::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'year_released' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'rating' => 'required|integer|min:1|max:5',
            'times_watched' => 'required|integer|min:0',
            'recommended' => 'required|boolean'
        ]);

        Eigo::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Eigo $eigo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'year_released' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'rating' => 'required|integer|min:1|max:5',
            'times_watched' => 'required|integer|min:0',
            'recommended' => 'required|boolean'
        ]);

        $eigo->update($validated);

        return redirect()->back();
    }

    public function destroy(Eigo $eigo)
    {
        $eigo->delete();

        return redirect()->route('eigo.index');
    }
}
