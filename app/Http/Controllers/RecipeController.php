<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::all();
        return view('welcome', ['recipes' => $recipes]);
    }

    public function search(Request $request)
    {
        $selectedFood = $request->input('food-dropdown');

        if (!$selectedFood) {
            // Redirect back with an error message or handle as needed
            return redirect()->back()->with('error', 'Please select a valid food.');
        }

        $recipes = [];

        if (strtolower($selectedFood) === 'egg') {
            // Assuming 'egg_1' and 'egg_2' are the IDs in your database
            $egg1 = Recipe::find('Egg_1');
            $egg2 = Recipe::find('Egg_2');

            if ($egg1) {
                $recipes[] = $egg1;
            }

            if ($egg2) {
                $recipes[] = $egg2;
            }
        } else {
            // Fetch recipes based on the selected food type from the database
            $recipes = Recipe::where('food', $selectedFood)->get();
        }

        return view('welcome', ['recipes' => $recipes]);
    }
}
