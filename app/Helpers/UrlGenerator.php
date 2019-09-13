<?php
namespace App\Helpers;

class UrlGenerator
{
    /**
     * @param string $videoId
     * @return string
     */
    public static function getYoutubeUrl(string $videoId): string
    {
        return "http://www.youtube.com/watch?v=$videoId";
    }

    /**
     * @param int $id
     * @return string
     */
    public static function getShowMovie($id): string
    {
        return env('APP_URL') . '/movie/' . $id;
    }

    /**
     * @param $filmFestivalId
     * @return string
     */
    public static function getListUrl($filmFestivalId)
    {
        return sprintf('film-festival/%s/list', $filmFestivalId);
    }
}