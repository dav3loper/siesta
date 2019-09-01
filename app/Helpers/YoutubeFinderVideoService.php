<?php
namespace App\Helpers;

use Google_Service_YouTube;
use Google_Service_YouTube_SearchResult;

class YoutubeFinderVideoService implements FinderVideoService
{

    private const PART_TO_FIND = 'snippet';
    private const QUERY_PARAM = 'q';
    private const TYPE = 'type';
    private const ALLOWED_TYPE = 'video';

    /** @var Google_Service_YouTube */
    private $_service;

    /**
     * YoutubeFinderVideoService constructor.
     * @param Google_Service_YouTube $service
     */
    public function __construct(Google_Service_YouTube $service)
    {
        $this->_service = $service;
    }

    /**
     * @param string $text
     * @return string
     */
    public function findVideoByText(string $text): string
    {
        try {
            $firstVideo = $this->_getFirstVideoByText($text);

            return $firstVideo->getId()->getVideoId();
        } catch (\Exception $e) {
            return 'notrailer';
        }
    }

    /**
     * @param string $text
     * @return Google_Service_YouTube_SearchResult
     * @throws \Exception
     */
    private function _getFirstVideoByText(string $text): Google_Service_YouTube_SearchResult
    {
        $videos = $this->_service->search->listSearch(self::PART_TO_FIND, [self::QUERY_PARAM => $text, self::TYPE => self::ALLOWED_TYPE]);

        if (!$videos->getItems()) {
            throw new \Exception('No video found');
        }

        return current($videos->getItems());
    }
}