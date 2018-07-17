<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PokemonProfilesController extends Controller
{
    protected $table = "pokemon_profiles";

    /**
     * Pokemons index page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $pokemons =  DB::table($this->table)->orderBy('weight', 'desc')->paginate(5);
        return view('/home', compact('pokemons'));
    }

    /**
     * Pokemons index page
     *
     * @return \stdClass
     */
    public function declareKing() {
        //@todo wewrite this function to use the model
        $pokemons = DB::table($this->table)->orderBy('weight', 'desc')->get();
        $pokekingBattle = [];

        foreach ($pokemons as $key => $pokemon){
            $pokekingBattle[$key]['pokemon'] = $pokemon->id;
            $pokekingBattle[$key]['total_base_stat'] = $this->getTotalBaseStat($pokemon);

            if ($key == 0){
                //first iteration so it can compare
                $pokeking = $pokemon;
                continue;
            }
            if($pokekingBattle[$key]['total_base_stat'] > $pokekingBattle[$key-1]['total_base_stat']){
                $pokeking = $pokemon;
            }
        }
        return view('/pokeking', compact('pokeking'))->render();

    }

    public function getTotalBaseStat($pokemon) {
        $totalBaseStat = 0;
        foreach ( json_decode($pokemon->additional)->stats as $stat ) {
            $totalBaseStat += $stat->base_stat;
        }
        return $totalBaseStat;
    }


}