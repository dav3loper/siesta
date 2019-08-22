<?php
namespace App\UseCases\ExtractMovieList;

use App\Helpers\FinderVideoService;
use siesta\domain\exception\MovieNotForVoteException;
use siesta\domain\extraction\MovieExtractor;
use siesta\domain\movie\Movie;
use siesta\infrastructure\movie\http\HtmlParser;

//TODO: tests a esta clase

/**
 * Class SitgesWebMovieExtractor
 * @package App\UseCases\ExtractMovieList
 */
class SitgesWeb2018MovieExtractor implements MovieExtractor
{
    private const MOVIE_ELEMENT_CLASS = 'masonry_withfilter';
    private const NON_VOTABLE_MOVIES = [
        'Brigadoon',
        'Sessió Especial Curts',
        'Anima\'t Cortos',
        'Oficial Fantàstic Competició Curts',
        'Official Fantàstic Competition Shorts',
        'Noves Visions - Small format',
        'Serial Sitges',
        'Panorama - Sitges Documenta',
        'Noves Visions - Pequeño formato',
        'Panorama - Sitges Documenta',
        'Noves Visions - Sitges Documenta',
        'Sitges Clàssics',
        'Anima\'t Cortos'
    ];
    /** @var HtmlParser */
    private $_htmlParser;
    /** @var FinderVideoService */
    private $_finderVideoService;

    /**
     * SitgesWeb2018MovieExtractor constructor.
     * @param HtmlParser $htmlParser
     * @param FinderVideoService $finderVideoService
     */
    public function __construct(HtmlParser $htmlParser, FinderVideoService $finderVideoService)
    {
        $this->_htmlParser = $htmlParser;
        $this->_finderVideoService = $finderVideoService;
    }

    /**
     * @param string $url
     * @return Movie[]
     */
    public function extract(string $url): array
    {
        $elements = $this->_htmlParser->getElementsByClass($url, self::MOVIE_ELEMENT_CLASS);
        $movieList = [];
        foreach ($elements as $domMovie) {
            try {
                $this->_checkForVotableMovie($domMovie);
                [$title, $link] = $this->_getTitleFromMovieElement($domMovie);
                $movie = new Movie();
                $movie->setTitle($title);
                $movie->setTrailerId($this->_getTrailer($title));
                $movie->setDuration($this->_getDuration($link));
                $movie->setPoster($this->_getPosterFromMovieElement($domMovie, $title));
                $movie->setSummary($this->_getSummary($link));
                $movieList[] = $movie;
            } catch (MovieNotForVoteException $e) {
                continue;
            }
        }

        return $movieList;
    }

    /**
     * @param \DiDom\Element $movie
     * @throws MovieNotForVoteException
     */
    private function _checkForVotableMovie(\DiDom\Element $movie): void
    {
        if (preg_match('/<p>' . implode('|', self::NON_VOTABLE_MOVIES) . '<\/p>/', $movie->html())) {
            throw new MovieNotForVoteException();
        }
    }

    /**
     * @param \DiDom\Element $movie
     * @return mixed
     */
    private function _getTitleFromMovieElement(\DiDom\Element $movie) //TODO: sacar a clase dominio
    {
        preg_match('/<h3><a href="(?<link>.*)">(?<title>.*)<\/a>/', $movie, $results);

        return [$results['title'], $results['link']];
    }

    /**
     * @param $title
     * @return string
     */
    private function _getTrailer($title): string
    {
        return $this->_finderVideoService->findVideoByText("\"$title\" official trailer");
    }

    /**
     * @param string $link
     * @return int
     * @throws MovieNotForVoteException
     */
    private function _getDuration(string $link): int
    {
        $rawTextList = $this->_htmlParser->getElementsByClass($link, 'fa-hourglass-start');
        $rawText = current($rawTextList);

        $duration = intval(trim($rawText->nextSibling()->text()));
        if ($duration != 0 && $duration < 60) {
            throw new MovieNotForVoteException();
        }

        return $duration;
    }

    /**
     * @param \DiDom\Element $movie
     * @param string $title
     * @return string
     */
    private function _getPosterFromMovieElement(\DiDom\Element $movie, $title) //TODO: sacar a clase dominio
    : string
    {
        $externalPoster = $this->_tryWithCineMaterial($title);
        if (!empty($externalPoster)) {
            return $externalPoster;
        }
        if (preg_match('/background-image: url\(\'(.*)\'\)/', $movie->html(), $results)) {
            return $results[1];
        }

        return '';
    }

    /**
     * @param string $title
     * @return string
     */
    private function _tryWithCineMaterial($title): string
    {
        $cineMaterialSerch = file_get_contents('https://www.cinematerial.com/search?q=' . urlencode($title));
        if (preg_match('#<img\ssrc="(https://cdn.cinematerial.com/p/30x/[^"]*)"#', $cineMaterialSerch, $matches)) {
            $image = str_replace('30x', '500x', $matches[1]);
            preg_match('/style="color:\s#8C8C8C;">([^<]*)<\/span>/', $cineMaterialSerch, $yearMatches);
            if (\in_array(trim($yearMatches[1]), ['2017', '2018'], true)) {
                return $image;
            }
        }

        return '';
    }

    /**
     * @param string $link
     * @return string
     */
    private function _getSummary(string $link): string
    {
        $rawTextList = $this->_htmlParser->getElementsByClass($link, 'section_sinopsi');
        $rawText = current($rawTextList);
        if (!$rawText) {
            return '';
        }

        return trim(str_replace('Sinopsis', '', $rawText->text()));
    }
}