<?php


namespace App\UseCases\ExtractSchedule;

use DiDom\Element;
use siesta\domain\extraction\ScheduleExtractor;
use siesta\infrastructure\movie\http\HtmlParser;

class SitgesWeb2023ScheduleExtractor implements ScheduleExtractor
{


    private const PROJECTION_CLASS = 'c-event-item';
    private HtmlParser $_htmlParser;

    public function __construct(HtmlParser $htmlParser)
    {
        $this->_htmlParser = $htmlParser;
    }

    public function extract(string $url): array
    {
        $elements = $this->_htmlParser->getElementsByClass($url, self::PROJECTION_CLASS);
        /** @var Element $element */
        $projectionList = [];
        foreach($elements as $element){
            $dataTime = $element->first('div.data-time');
            $day = $dataTime->first('div.day')->text();
            $time = $dataTime->first('div.time')->text();
            $titlesDom = $element->find('li.movie-title');
            $titles = [];
            foreach($titlesDom as $title){
                $titles[] = $title->text();
            }
            $venue = $element->first('div.location')->text();
            $duration = $element->first('span.duration-text')->text();
            $sectionsDom = $element->find('li.section-title');
            $sections = [];
            foreach($sectionsDom as $section){
                $sections[] = $section->text();
            }

            $projectionList[] = [
                $day,
                $time,
                implode("\n", $titles),
                $venue,
                $duration,
                implode("\n", $sections)
            ];
        }

        return $projectionList;

    }
}
