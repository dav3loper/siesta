<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;
use siesta\domain\extraction\ScheduleExtractor;

class ExtractSchedule extends Command
{
    private const PATH_TO_HTML = 'pathToHtml';
    private ScheduleExtractor $_extractor;

    public function __construct()
    {
        $this->description = 'Extract schedule from local html';
        $this->signature = 'extract:schedule {' . self::PATH_TO_HTML . '}';
        parent::__construct();
    }

    public function handle(): void
    {
        $path = $this->argument(self::PATH_TO_HTML);
        $this->_extractor = app()->makeWith(ScheduleExtractor::class, ['urlOrPath' => $path]);

        $projectionList = $this->_extractor->extract($path);
        $fd = fopen('output.csv', 'w+');
        foreach($projectionList as $projection){
            fputcsv($fd, $projection);
        }
    }
}
