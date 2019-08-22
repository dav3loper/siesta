<?php
namespace App\Http\Controllers;

use App\Presentation\VoteListDecorator;
use siesta\application\movie\usecases\ObtainMoviesMostVotedHandler;

class VoteController extends SiestaController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listVotes()
    {
        $handler = app()->make(ObtainMoviesMostVotedHandler::class);
        $voteList = $handler->execute();

        return view('votes.list', ['decorator' => new VoteListDecorator($voteList)]);
    }
}