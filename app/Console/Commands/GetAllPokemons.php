<?php

namespace App\Console\Commands;

use App\Pokemon;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetAllPokemons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect:pokemons {truncate : true or false to empty table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect all Pokemons from pokeapi.co';

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $truncate = $this->argument('truncate');
        if($truncate == "true"){
            DB::table('pokemons')->truncate();
            $this->info('Pokemon table truncated succesfully.');
        }

        $this->info('Collecting all Pokemons..');
        $this->getPokemons('https://pokeapi.co/api/v2/pokemon/');
        $this->comment('Total Pokemons from REST: ' .$this->pokemonCount);
        $this->info('All Pokemons collected successfully.');
    }


    /**
     * Get all Pokemons from pokeapi.co
     *
     */
    public function getPokemons($url)
    {
        $this->client = new GuzzleClient;
        $bodyRequest = $this->client->request('GET', $url)->getBody();
        $pokemonCollection = json_decode($bodyRequest);

        //count pokemons from rest first time
        if (!isset($this->bar)){
            $this->pokemonCount = $pokemonCollection->count;
            $this->bar = $this->output->createProgressBar($this->pokemonCount);
        }

        foreach ($pokemonCollection->results as $result) {
            if (!isset($result->name) || !isset($result->url)) {
                continue;
            }
            $pokemon = new Pokemon();
            $pokemon->name = $result->name;
            $pokemon->url = $result->url;
            $pokemon->save();
            $this->bar->advance();
        }

        if ($pokemonCollection->next !== null) {
            $this->getPokemons($pokemonCollection->next);
        }

        $this->bar->finish();
    }
}
