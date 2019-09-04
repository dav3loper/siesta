<?php

namespace App\Http\Controllers;

use App\Presentation\FilmFestivalListDecorator;
use Illuminate\Support\Facades\Auth;
use siesta\application\home\usecases\DashboardUserHandler;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $handler = app()->make(DashboardUserHandler::class);
        $dashBoardResponse = $handler->execute(Auth::user()->id);

        return view('home', ['decorator' => new FilmFestivalListDecorator($dashBoardResponse)]);
    }
}
