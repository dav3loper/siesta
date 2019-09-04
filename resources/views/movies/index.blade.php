<?php

/** @var \App\Presentation\MovieDecorator $movie */
/** @var \siesta\domain\user\User[] $users */
?>

@extends('base')
@section('content')
    <div class="container">
        <div class="row margin-top-30">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h2><i class="material-icons">movie</i> {{ $movie->getTitle()}}
                    <small>({{ $movie->getDuration() }})</small>
                </h2>
            </div>
        </div>
        <div class="row margin-top-30">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <img class="border border-primary box-shadow" height="100%" width="100%"
                     src={!! html_entity_decode($movie->getPoster()) !!} />
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <h2><i class="material-icons">comment</i> Sinopsis</h2>
                {{ $movie->getSummary()}}
                <div style="height: 50%">
                    <iframe style="margin-top:10px; border: 10px double #ddd;" width="100%" height="100%" src="{{$movie->getTrailer()}}" frameborder="0"
                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <br/>
        <form method="post">
            <div class="row margin-top-20">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <h2><i class="material-icons">sms</i> Comentarios</h2>
                    <textarea rows="4" cols="50" name="movie_comments" id="movie_comments_id">{{ $movie->getComments()}}</textarea>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <h2><i class="material-icons">thumb_up</i> Votaciones</h2>

                    {{ csrf_field() }}
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Falta algo, mozo
                        </div>
                    @endif
                    @foreach($users as $user)
                        <span><strong>{{$user->getName()}}</strong></span>
                        <fieldset class="margin-left-15">
                            <label>
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary" value="-1" checked>&nbsp;Sin votar
                            </label>
                            <label>
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary" value="0" {{$movie->isNoScore($user->getId())}}>&nbsp;No querer
                            </label>
                            <label>
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary" value="1" {{$movie->isWeakScore($user->getId())}}>&nbsp;Podría
                                verla
                            </label>
                            <label>
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary" value="2" {{$movie->isStrongScore($user->getId())}}>&nbsp;Quiero
                                verla!!
                            </label>
                        </fieldset>
                    @endforeach
                    <button type="submit" class="margin-top-10">Enviar voto!</button>

                </div>
            </div>
        </form>
        <div class="row margin-top-30">
            <div class="col-md-12 col-sm-12 col-xs-12">

            </div>
        </div>


    </div>
@stop
