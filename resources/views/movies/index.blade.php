<?php

/** @var \App\Presentation\MovieDecorator $movie */
/** @var array $users */
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
        <div class="row margin-top-20">
            <div class="col-md-6 col-sm-6 col-xs-6"></div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <h2><i class="material-icons">thumb_up</i> Votaciones</h2>
                <form method="post">
                    {{ csrf_field() }}
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Falta algo, mozo
                        </div>
                    @endif
                    <?php for($i = 0; $i < count($users); $i++): ?>
                    <span><strong><?php echo $users[$i]?></strong></span>
                    <fieldset class="margin-left-15">
                        <label>
                            <input type="radio" name="user_<?= $i ?>" class="vote btn btn-secondary" value="0" checked>&nbsp;No querer
                        </label>
                        <label>
                            <input type="radio" name="user_<?= $i ?>" class="vote btn btn-secondary" value="1" {{$movie->isWeakScore($i)}}>&nbsp;Podría verla
                        </label>
                        <label>
                            <input type="radio" name="user_<?= $i ?>" class="vote btn btn-secondary" value="2" {{$movie->isStrongScore($i)}}>&nbsp;Quiero verla!!
                        </label>
                    </fieldset>
                    <?php endfor; ?>
                    <button type="submit" class="margin-top-10">Enviar voto!</button>
                </form>
            </div>
        </div>
        <div class="row margin-top-30">
            <div class="col-md-12 col-sm-12 col-xs-12">

            </div>
        </div>


    </div>
@stop
