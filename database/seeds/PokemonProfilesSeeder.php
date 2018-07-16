<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class PokemonProfilesSeeder extends Seeder
{
    /**
     * Progress bar
     */
    protected $bar;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pokemons = DB::table('pokemons')->get();
        if (empty($pokemons)) {
            $this->command->info('Pokemon database is empty, please put some data first');
            return;
        }

        $this->command->info('Creating pokemon profiles, this will take some time..');
        $this->insertPokemonProfiles($pokemons);
        $this->command->info('All Pokemon profiles created!');
    }

    /**
     * Get an http pool from every pokemon url stored in db
     * @param Illuminate\Support\Collection $pokemons
     * @return void
     */
    protected function insertPokemonProfiles($pokemons)
    {
        $client = new Client();

        $requests = function ($pokemons) {
            foreach ($pokemons as $pokemon) {
                yield new Request('GET', $pokemon->url);
            }
        };


        $pool = new Pool( $client, $requests( $pokemons ), [
        'concurrency' => 20,
            'fulfilled' => function ($response, $index) {
                $responseBody = json_decode($response->getBody());
                $this->importPokemonProfile($responseBody);
            },
            'rejected' => function ($reason, $index) {
                $this->command->info('Rejected request reason: ' . $reason);
            },
        ]);
        
        $promise = $pool->promise();
        $promise->wait();
    }

    /**
     * Add pokemon profile to database
     * @param $response
     * @return void
     */
    protected function importPokemonProfile($response)
    {
        if (empty($response))
            return;

        //get only taller than 50 and with an image sprite front default set
        if ($response->height >= 50 && (!empty($response->sprites->front_default))) {
            DB::table('pokemon_profiles')->insert([
                'sprite' => $response->sprites->front_default,
                'base_experience' => $response->base_experience,
                'height' => $response->height,
                'weight' => $response->weight,
                'additional' => json_encode($response, JSON_UNESCAPED_UNICODE)
            ]);
        }
    }
}
