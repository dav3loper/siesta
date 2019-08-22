<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use siesta\application\exception\WrongInputException;
use siesta\application\movie\usecases\StoreMovieCommand;
use siesta\application\movie\usecases\StoreMovieHandler;
use siesta\domain\exception\MovieRecordException;
use siesta\domain\extraction\MovieExtractor;

class InsertSitgesFilms extends Command
{
    private const PATH_TO_HTML = 'pathToHtml';

    /** @var StoreMovieHandler */
    private $_useCase;
    /** @var MovieExtractor */
    private $_extractor;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->description = 'Insert sitges film from local html';
        $this->signature = 'insert:films {' . self::PATH_TO_HTML . '}';
        $this->_useCase = app()->make(StoreMovieHandler::class);

        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws WrongInputException
     * @throws MovieRecordException
     */
    public function handle(): void
    {
        $path = $this->argument(self::PATH_TO_HTML);
        $this->_extractor = app()->makeWith(MovieExtractor::class, ['urlOrPath' => $path]);

        $movieList = $this->_extractor->extract($path);
        $statusBar = $this->output->createProgressBar(count($movieList));
        $statusBar->display();
        foreach ($movieList as $movie) {
            $command = StoreMovieCommand::buildFromMovie($movie);
            $this->_useCase->execute($command);

            $statusBar->advance();
        }
        $statusBar->finish();
    }
}
