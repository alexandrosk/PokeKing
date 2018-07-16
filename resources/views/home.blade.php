@extends('app')
@section('title', 'Home')
@section('content')
    <div class="content">
        <div class="title m-b-md">
            <div class="pokeball">
                <div class="pokeball__button"></div>
            </div>
            Find the PokeKing!
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Sprite</th>
                <th>Name</th>
                <th>Base Experience</th>
                <th>Weight</th>
                <th>Height</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pokemons as $pokemon)
                <tr>
                    <td><img src="{{ $pokemon->sprite }}"></td>
                    <td>{{\GuzzleHttp\json_decode($pokemon->additional)->name}}</td>
                    <td>{{$pokemon->base_experience}}</td>
                    <td>{{$pokemon->weight}}</td>
                    <td>{{$pokemon->height}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="links">
            {{ $pokemons->links() }}
        </div>
        <div class="links" >
            <a href="/pokeking" class="pokeking-btn">Find the pokeking</a>
        </div>

    </div>
@endsection