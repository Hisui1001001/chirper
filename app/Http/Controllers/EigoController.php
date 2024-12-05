<?php

namespace App\Http\Controllers;

use App\Http\Requests\EigoRequest;
use App\Models\Eigo;
use Inertia\Inertia;

class EigoController extends Controller
{
    public function index()
    {
        return Inertia::render('Eigo/Index', [
            'movies' => Eigo::latest()->get()
        ]);
    }

    public function store(EigoRequest $request)
    {
        Eigo::create($request->validated());

        return redirect()->back();
    }

    public function update(EigoRequest $request, Eigo $eigo)
    {
        $eigo->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Eigo $eigo)
    {
        $eigo->delete();

        return redirect()->route('eigo.index');
    }
}
