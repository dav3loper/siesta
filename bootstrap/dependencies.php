<?php
//TODO: mover esto al appServiceProvider

$app = app();
/***************************************
 * USECASES
 **************************************/
$app->bind(\siesta\application\movie\usecases\StoreMovieHandler::class, \siesta\application\movie\usecases\StoreMovieHandler::class);
$app->bind(\siesta\domain\extraction\MovieExtractor::class, \App\UseCases\ExtractMovieList\SitgesWeb2018MovieExtractor::class);

/***************************************
 * HELPERS
 **************************************/
$app->bind(\siesta\infrastructure\movie\http\HtmlParser::class, \siesta\infrastructure\movie\http\SimpleDomHtmlParser::class);
$app->bind(\App\Helpers\FinderVideoService::class, function ($app) {
    $googleClient = new Google_Client();
    $googleClient->setApplicationName(env('GOOGLE_APP_NAME'));
    $googleClient->setDeveloperKey(env("GOOGLE_APP_KEY"));
    $videoService = new Google_Service_YouTube($googleClient);

    return new \App\Helpers\YoutubeFinderVideoService($videoService);
});

/***************************************
 * INFRASTRUCTURE
 **************************************/
$app->bind(\siesta\domain\movie\infrastructure\MovieRecorder::class, \siesta\infrastructure\movie\persistence\EloquentMovieRecorder::class);
$app->bind(\siesta\domain\movie\infrastructure\MovieProvider::class, \siesta\infrastructure\movie\persistence\EloquentMovieProvider::class);
$app->bind(\siesta\infrastructure\vote\persistence\ScoreTransformer::class, \siesta\infrastructure\vote\persistence\EloquentScoreTransformer::class);
$app->bind(\siesta\domain\vote\infrastructure\VoteRecorder::class, \siesta\infrastructure\vote\persistence\EloquentVoteRecorder::class);
$app->bind(\siesta\domain\vote\infrastructure\VoteProvider::class, \siesta\infrastructure\vote\persistence\EloquentVoteProvider::class);