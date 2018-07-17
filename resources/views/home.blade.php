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
        <div id="pokeking"></div>
        <div class="links" >
            <a href="/pokeking" id="declare-king" class="pokeking-btn">Find the pokeking</a>
        </div>

    </div>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous">
    </script>
    <script>
        jQuery(document).ready(function(){
            jQuery('#declare-king').click(function(e){
                e.preventDefault();
                jQuery.ajax({
                    url: "{{ url('/pokeking') }}",
                    method: 'get',
                    data: {},
                    success: function(result){
                        jQuery('#pokeking').html(result)
                    }
                });
            });
        });
    </script>
@endsection
