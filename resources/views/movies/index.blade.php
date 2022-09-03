<?php

/** @var \App\Presentation\MovieDecorator $movie */
/** @var \siesta\domain\user\User[] $users */
/** @var int $remaining */
?>

@extends('base')
@section('content')
    <div class="container">
        <div class="row margin-top-30">
            <div class="col-md-9 col-sm-9 col-xs-9">
                <h2>
                    <i class="material-icons">movie</i>
                    @if($movie->getLink())
                        <a target="_blank" href="{{ $movie->getLink()}}">
                            @endif
                            {{ $movie->getTitle()}}
                            @if($movie->getLink())
                        </a>
                    @endif
                    <small>({{ $movie->getDuration() }})</small>
                </h2>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h3>
                    <i class="material-icons">wb_iridescent</i>
                    Te quedan {{$remaining}}
                </h3>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <input id="updateAliasMovieInput" class="col-md-5 col-sm-11 col-xs-11" type="text"
                       value="{{ $movie->getAlias() }}"/>
                <i id="updateAliasMovie" class="material-icons" data-movieId="{{$movie->getId()}}">save</i>
                <span id="infoForUpdateAliasMovie"></span>
            </div>
        </div>
        <div class="row margin-top-30">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <img class="border border-primary box-shadow" height="100%" width="100%"
                     src={!! html_entity_decode($movie->getPoster()) !!} />
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="row">
                    <div class="col-md-12">
                        <h2><i class="material-icons">comment</i> Sinopsis</h2>
                        {{ $movie->getSummary()}}
                        <div style="height: 50%">
                            <iframe style="margin-top:10px; border: 10px double #ddd;" width="100%" height="100%"
                                    src="{{$movie->getTrailer()}}" frameborder="0"
                                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-5">
                    <div class="col-md-12">
                        <button type="button" class="pull-right btn btn-danger" data-toggle="modal"
                                data-target="#setTrailerModal">¿Trailer incorrecto?
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br/>

        <!-- Modal para setTrailer -->
        <div class="modal fade" id="setTrailerModal" tabindex="-1" role="dialog" aria-labelledby="setTrailerModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cambiar trailer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        A continuación pon el ID del trailer correcto de Youtube:
                        <input name="newIDtrailer" id="newIDtrailer" type="text" data-movieId="{{$movie->getId()}}"/>
                        <span data-target="#set-trailer-info" data-toggle="collapse" title="ayuda" class="clickable"
                              aria-expanded="true"><i class="material-icons">info</i></span>
                        <div class="margin-top-10 collapse in" id="set-trailer-info" aria-expanded="true" style="">
                            <div class="well">
                                <p>Por ejemplo para el video de Youtube <a target="_blank"
                                                                           href="https://www.youtube.com/watch?v=G1IbRujko-A">https://www.youtube.com/watch?v=G1IbRujko-A</a>
                                    el ID es <strong>G1IbRujko-A</strong></p>
                            </div>
                        </div>
                        <br/><br/><span class="alert-success" id="infoForNewIdTrailer"></span><br/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">¡Sácame de aquí!</button>
                        <button id="saveNewTrailer" type="button" class="btn btn-primary">Guardar trailer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin de modal para setTrailer -->

        <form method="post">
            <div class="row margin-top-20">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <h2><i class="material-icons">sms</i> Comentarios</h2>
                    <textarea rows="4" cols="50" name="movie_comments"
                              id="movie_comments_id">{{ $movie->getComments()}}</textarea>
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
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary"
                                       value="-1" checked>&nbsp;Sin votar
                            </label>
                            <label>
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary"
                                       value="0" {{$movie->isNoScore($user->getId())}}>&nbsp;No querer
                            </label>
                            <label>
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary"
                                       value="1" {{$movie->isWeakScore($user->getId())}}>&nbsp;Podría
                                verla
                            </label>
                            <label>
                                <input type="radio" name="user_{{$user->getId()}}" class="vote btn btn-secondary"
                                       value="2" {{$movie->isStrongScore($user->getId())}}>&nbsp;Quiero
                                verla!!
                            </label>
                        </fieldset>
                    @endforeach
                    <button type="submit" class="btn-dark btn margin-top-10">¡Enviar!</button>

                </div>
            </div>
        </form>
        <div class="row margin-top-30">
            <div class="col-md-12 col-sm-12 col-xs-12">
            </div>
        </div>
    </div>
@stop
