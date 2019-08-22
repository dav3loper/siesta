<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/home', function () {
    echo "bieeeen";
});

Route::get('/list', 'VoteController@listVotes');

Route::post('/movie-create', 'MovieController@create');

Route::get('/movie/{id}', 'MovieController@show');

Route::post('/movie/{id}', 'MovieController@vote');

