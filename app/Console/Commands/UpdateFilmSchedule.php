<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use siesta\application\movie\usecases\UpdateScheduleHandler;

class UpdateFilmSchedule extends Command
{

    /** @var UpdateScheduleHandler */
    private $_useCase;

    public function __construct()
    {
        $this->description = 'Insert schedule of a film';
        $this->signature = 'update:schedule';
        $this->_useCase = app()->make(UpdateScheduleHandler::class);
    }

    public function handle(): void
    {

    }
}