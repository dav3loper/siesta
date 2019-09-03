<?php

/** @var \App\Presentation\FilmFestivalListDecorator $decorator */
?>

@extends('base')

@section('content')
    <div class="container">
        <div class="row margin-top-30 justify-content-center">
            <h2>¡Bienvenido a SIESTA!</h2>
        </div>
        <br/>
        @foreach($decorator as $index=>$filmFestival)
            @if($index % 2 === 0)
                <div class="row">
                    @endif
                    <div class="col-md-6">
                        <div class="card mb-6 shadow-sm">
                            <img src="{{ asset('/img/'.$filmFestival->getEdition().'.jpg') }}" alt="Sitges {{ $filmFestival->getEdition() }}" class="img-thumbnail">
                            <div class="card-body">
                                <p class="card-text"><strong>{{ $filmFestival->getEdition() }}</strong></p>
                                <p class="card-text">{{$filmFestival->getName()}}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{$decorator->getNextMovieToVote()}}" type="button" class="btn btn-sm btn-outline-secondary">Votar</a>
                                        <button type="button" class="btn btn-sm btn-outline-secondary">Ver horario</button>
                                    </div>
                                    <small class="text-muted">{{$decorator->getCurrentDuration()}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($index % 2 === 1)
                </div>
            @endif
        @endforeach
    </div>
@endsection
