<?php
namespace App\Http\Controllers;

use App\Presentation\VoteListDecorator;
use siesta\application\movie\usecases\ObtainMoviesMostVotedHandler;

class VoteController extends SiestaController
{
    /**
     * @param $filmFestivalId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listVotes($filmFestivalId)
    {
        $handler = app()->make(ObtainMoviesMostVotedHandler::class);
        $voteList = $handler->execute($filmFestivalId);

        return view('votes.list', ['decorator' => new VoteListDecorator($voteList)]);
    }
}