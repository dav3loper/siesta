<?php

namespace App\Http\Controllers;

use App\Presentation\FilmFestivalListDecorator;
use siesta\application\home\DashboardUserHandler;

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
        $filmFestivalList = $handler->execute();

        return view('home', ['decorator' => new FilmFestivalListDecorator($filmFestivalList)]);
    }
}
