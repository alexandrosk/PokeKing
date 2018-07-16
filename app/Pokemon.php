<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    public $table = 'pokemons';

    protected $fillable = ['url', 'name'];

}
