<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vignette;

class VignetteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vignettes = Vignette::with('vehicle')->get();
        return response()->json($vignettes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'category' => 'required|string',
            'region' => 'nullable|string',
            'year' => 'required|integer',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
        ]);

        $vignette = \App\Models\Vignette::create($validatedData);

        return response()->json($vignette, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vignette = Vignette::findOrFail($id);
        return response()->json($vignette);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'category' => 'required|string',
            'region' => 'nullable|string', // region can be null for country vignettes
            'year' => 'required|integer',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
        ]);

        $vignette = Vignette::findOrFail($id);
        $vignette->update($validatedData);

        return response()->json($vignette);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vignette = Vignette::findOrFail($id);
        $vignette->delete();

        return response()->json(null, 204);
    }
}
