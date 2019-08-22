<?php

namespace App\Http\Controllers;

use App\Presentation\MovieDecorator;
use Illuminate\Http\Request;
use siesta\application\exception\WrongInputException;
use siesta\application\movie\usecases\ObtainMovieCommand;
use siesta\application\movie\usecases\ObtainMovieHandler;
use siesta\application\movie\usecases\StoreMovieCommand;
use siesta\application\movie\usecases\StoreMovieHandler;
use siesta\application\movie\usecases\VoteMovieCommand;
use siesta\application\movie\usecases\VoteMovieHandler;
use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\exception\MovieRecordException;

class MovieController extends SiestaController
{
    /**
     * @param Request $request
     * @throws WrongInputException
     * @throws MovieRecordException
     */
    public function create(Request $request): void
    {
        $command = StoreMovieCommand::buildFromJsonData(json_encode($request->input('data')));

        /** @var StoreMovieHandler $handler */
        $handler = app()->make(StoreMovieHandler::class);
        $handler->execute($command);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $command = new ObtainMovieCommand();
            $command->setId($id);

            /** @var ObtainMovieHandler $handler */
            $handler = app()->make(ObtainMovieHandler::class);
            $movie = $handler->execute($command);
        } catch (MovieNotFoundException $e) {
            return view('404');
        }

        //FIXME: sacar a estatica o a env los users
        return view('movies.index', ['movie' => new MovieDecorator($movie), 'users' => ['David', 'Sandra', 'Unai', 'Leticia', 'Mario']]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @throws \siesta\domain\exception\vote\VoteInvalidTypeException
     */
    public function vote(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                //FIXME: sacar a estatica o a env los users
                'user_0' => 'required',
                'user_1' => 'required',
                'user_2' => 'required',
                'user_3' => 'required',
                'user_4' => 'required',
            ]);
            $command = new VoteMovieCommand();
            $command->setId($id);
            $command->setIndividualVote($request->all());

            /** @var VoteMovieHandler $handler */
            $handler = app()->make(VoteMovieHandler::class);
            $handler->execute($command);

            return redirect('movie/' . ++$id);
        }
    }
}
