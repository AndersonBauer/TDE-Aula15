<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
        public function index()
    {
        $pokemon = Pokemon::all();
        return view('pokemon.index', compact('pokemon'));
    }

    public function create()
    {
        $coaches = Coach::all();
        return view('pokemon.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'coach_id' => 'required',
            'type' => 'required',
            'power_of_points' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $pokemon = new Pokemon();
        $pokemon->name = $request->name;
        $pokemon->type = $request->type;
        $pokemon->coach_id = $request->coach_id;
        $pokemon->power_of_points = $request->power_of_points;
        $pokemon->image = 'images/'.$imageName;
        $pokemon->save();
        
        return redirect('pokemon')->with('success', 'pokemon created successfully.');
    }

    public function edit($id)
    {
        $coaches = Coach::all();
        $pokemon = Pokemon::findOrFail($id);
        return view('pokemon.edit', compact('pokemon', 'coaches'));
    }

    public function update(Request $request, $id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->update($request->all());

        $pokemon->name = $request->name;
        $pokemon->type = $request->type;
        $pokemon->power_of_points = $request->power_of_points;

        if(!is_null($request->image)) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            $pokemon->image = 'images/'.$imageName;
        }
        $pokemon->save();

        return redirect('pokemon')->with('success', 'pokemon updated successfully.');
    }

    public function destroy($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->delete();
        return redirect('pokemon')->with('success', 'pokemon deleted successfully.');
    }
}