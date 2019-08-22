<?php
/** @var \App\Presentation\VoteListDecorator $decorator */

?>

@extends('base')
@section('content')
    <div class="container">
        <div class="row margin-top-30">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h2><i class="material-icons">star_border</i> Ranking de votación <i class="material-icons">star_border</i></h2>
            </div>
        </div>
        <div class="row margin-top-20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Busca peliculas">

                <table id="myTable">
                    <tr class="header">
                        <th style="width:60%;">Pelicula</th>
                        <th style="width:40%;">Votacion</th>
                    </tr>
                    <?php for($i = 0; $i < $decorator->getTotalSize(); $i++, $decorator->next()):?>
                    <tr class="{{$decorator->getCurrentMovieColor()}}">
                        <td><a href="{{$decorator->getCurrentMovieLink()}}" target="_blank">{{$decorator->getCurrentMovieName()}}</a></td>
                        <td>{{$decorator->getCurrentVote()}}</td>
                    </tr>
                    <?php endfor;?>
                </table>
            </div>
        </div>
    </div>
@stop