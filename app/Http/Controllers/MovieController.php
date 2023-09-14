<?php

namespace App\Http\Controllers;

use App\Presentation\MovieDecorator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use siesta\application\exception\WrongInputException;
use siesta\application\movie\usecases\GetNextMovieToVoteCommand;
use siesta\application\movie\usecases\GetNextMovieToVoteHandler;
use siesta\application\movie\usecases\ObtainMovieCommand;
use siesta\application\movie\usecases\ObtainMovieHandler;
use siesta\application\movie\usecases\StoreMovieCommand;
use siesta\application\movie\usecases\StoreMovieHandler;
use siesta\application\movie\usecases\UpdateAliasCommand;
use siesta\application\movie\usecases\UpdateAliasHandler;
use siesta\application\movie\usecases\UpdateTrailerCommand;
use siesta\application\movie\usecases\UpdateTrailerHandler;
use siesta\application\movie\usecases\VoteMovieCommand;
use siesta\application\movie\usecases\VoteMovieHandler;
use siesta\domain\exception\MovieNotForVoteException;
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
            $command->setUserId(Auth::user()->id);

            /** @var ObtainMovieHandler $handler */
            $handler = app()->make(ObtainMovieHandler::class);
            $response = $handler->execute($command);
        } catch (MovieNotForVoteException $e) {
            return view('movies.congrats');
        } catch (MovieNotFoundException $e) {
            return view('404');
        }

        return view('movies.index', ['movie' => new MovieDecorator(
            $response->getMovie()), 'users' => $response->getUserList(), 'remaining' => $response->getRemaining()
            ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @throws \siesta\domain\exception\vote\VoteInvalidTypeException
     */
    public function vote(Request $request, $id)
    {
        if ($request->isMethod('post')) {
//            $request->validate([
//                //FIXME: sacar a estatica o a env los users
//                'user_0' => 'required',
//                'user_1' => 'required',
//                'user_2' => 'required',
//                'user_3' => 'required',
//                'user_4' => 'required',
//            ]);
            $command = new VoteMovieCommand();
            $command->setId($id);
            $command->setIndividualVote($request->all());
            $command->setComments($request->input('movie_comments', ''));

            /** @var VoteMovieHandler $handler */
            $handler = app()->make(VoteMovieHandler::class);
            $handler->execute($command);


            $command = new GetNextMovieToVoteCommand(
                $id,
                Auth::user()->id
            );
            /** @var GetNextMovieToVoteHandler $handler */
            $handler = app()->make(GetNextMovieToVoteHandler::class);
            $movie = $handler->execute($command);
            if($movie === null){
                return view('movies.congrats');

            }
            return redirect('movie/' . $movie->getId());
        }
    }

    public function updateTrailer(Request $request, $id)
    {
        if ($request->isMethod('put')) {

            $command = new UpdateTrailerCommand();
            $command->setId($id);
            $jsonData = json_decode($request->getContent());
            $command->setTrailerId($jsonData->trailer_id);

            /** @var UpdateTrailerHandler $handler */
            $handler = app()->make(UpdateTrailerHandler::class);
            $handler->execute($command);

            return json_encode(['status' => 'El trailer ha sido modificado']);
        }

        return json_encode(['status' => 'Ha habido un error al guardarlo']);
    }

    public function updateAlias(Request $request, $id)
    {
        if ($request->isMethod('put')) {

            $command = new UpdateAliasCommand();
            $command->setId($id);
            $jsonData = json_decode($request->getContent());
            $command->setAlias($jsonData->alias);

            /** @var UpdateTrailerHandler $handler */
            $handler = app()->make(UpdateAliasHandler::class);
            $handler->execute($command);

            return json_encode(['status' => 'Alias modificado']);
        }

        return json_encode(['status' => 'Ha habido un error al guardarlo']);
    }

    public function nextToVote(Request $request, $id)
    {
        if (!$request->isMethod('get')) {
            return '';
        }
        $command = new GetNextMovieToVoteCommand(
            $id,
            Auth::user()->id
        );

        /** @var GetNextMovieToVoteHandler $handler */
        $handler = app()->make(GetNextMovieToVoteHandler::class);
        $movie = $handler->execute($command);
        if($movie === null){
            return view('movies.congrats');

        }
        return redirect('movie/' . $movie->getId());


    }
}
