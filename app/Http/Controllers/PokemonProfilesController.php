<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PokemonProfilesController extends Controller
{
    /**
     * Pokemons index page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $pokemons =  DB::table('pokemon_profiles')->orderBy('weight', 'desc')->paginate(5);
        return view('/home', compact('pokemons'));
    }
}