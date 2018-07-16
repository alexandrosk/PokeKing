<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class PokemonProfilesSeeder extends Seeder
{

    /**
     * Pokemon total counter from rest
     */
    protected $pokemonCount;

    /**
     * Pokemons imported
     */
    protected $importedCount;

    /**
     * Progress bar
     */
    protected $bar;

    /**
     * Guzzle Client
     */
    protected $client;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Collecting all Pokemons..');
        $this->getPokemons('https://pokeapi.co/api/v2/pokemon/');
        $this->command->info('All Pokemons collected successfully.');
    }

    protected function getPokemons($url)
    {
        $this->client = new GuzzleClient;
        $bodyRequest = $this->client->request('GET', $url)->getBody();
        $pokemonCollection = json_decode($bodyRequest);

        //count pokemons from rest first time
        if (!isset($this->bar)) {
            $this->pokemonCount = $pokemonCollection->count;
            $this->bar = $this->command->getOutput()->createProgressBar($this->pokemonCount);
        }

        foreach ($pokemonCollection->results as $result) {
            if (!isset($result->name) || !isset($result->url)) {
                continue;
            }
            DB::table('pokemon_profiles')->insert([
                'name'      => $result->name,
                'url'       => $result->url,
            ]);
            $this->bar->advance();
        }

        if ($pokemonCollection->next !== null) {
            $this->getPokemons($pokemonCollection->next);
        }

        $this->bar->finish();
    }
}
