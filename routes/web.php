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
    return redirect('/home');
});


//Route::post('/movie-create', 'MovieController@create');


// Rutas de login y registro
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


// Authorized routs
Route::group(['middleware' => ['auth']], function () {

    Route::get('/film-festival/{id}/list', 'VoteController@listVotes');

    // votaciones
    Route::get('/movie/{id}', 'MovieController@show');
    Route::post('/movie/{id}', 'MovieController@vote');
    Route::get('/movie/next/{id}', 'MovieController@nextToVote');
});

Route::put('/movie/{id}/trailer', 'MovieController@updateTrailer');
