<?php
namespace App\UseCases\ExtractMovieList;

use App\Helpers\FinderVideoService;
use siesta\domain\exception\MovieAlreadyExtractedException;
use siesta\domain\exception\MovieNotForVoteException;
use siesta\domain\exception\MovieNotFoundException;
use siesta\domain\extraction\MovieExtractor;
use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\movie\Movie;
use siesta\infrastructure\movie\http\HtmlParser;

//TODO: tests a esta clase

/**
 * Class SitgesWebMovieExtractor
 * @package App\UseCases\ExtractMovieList
 */
class SitgesWeb2019MovieExtractor implements MovieExtractor
{
    private const MOVIE_ELEMENT_CLASS = 'masonry_withfilter';
    private const NON_VOTABLE_MOVIES = [
        'Brigadoon',
        'Sessió Especial Curts',
        'Anima\'t Cortos',
        'Oficial Fantàstic Competició Curts',
        'Oficial Fantàstic Competición Cortos',
        'Official Fantàstic Competition Shorts',
        'Noves Visions - Small format',
        'Serial Sitges',
        'Panorama - Sitges Documenta',
        'Noves Visions - Pequeño formato',
        'Panorama - Sitges Documenta',
        'Noves Visions - Sitges Documenta',
        'Sitges Clàssics',
        'Sitges Classics',
        'Anima\'t Cortos'
    ];
    private HtmlParser $_htmlParser;
    private FinderVideoService $_finderVideoService;
    private MovieProvider $_movieProvider;

    public function __construct(HtmlParser $htmlParser, FinderVideoService $finderVideoService, MovieProvider $movieProvider)
    {
        $this->_htmlParser = $htmlParser;
        $this->_finderVideoService = $finderVideoService;
        $this->_movieProvider = $movieProvider;
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
                $this->_checkForMovieExtracted($title);
                $movie = new Movie();
                $movie->setTitle($title);
                $movie->setTrailerId($this->_getTrailer($title));
                $movie->setDuration($this->_getDuration($link));
                $movie->setPoster($this->_getPosterFromMovieElement($domMovie, $title));
                $movie->setSummary($this->_getSummary($link));
                $movie->setLink($link);
                $movieList[] = $movie;
            } catch (MovieNotForVoteException|MovieAlreadyExtractedException $e) {
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
        if (!$rawText) {
            return 999;
        }

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
        $title = str_replace(['1', '2', '3', '4', '5', '6', '7', '8', '9'], ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'], $title);
        $cineMaterialSerch = file_get_contents('https://www.cinematerial.com/search?q=' . urlencode($title));
        if (preg_match('#<img\ssrc="?(https://cdn.cinematerial.com/p/60x/[^" ]*)[" ]#', $cineMaterialSerch, $matches)) {
            $image = str_replace('60x', '500x', $matches[1]);
            preg_match('/style="color:\s#8C8C8C;">([^<]*)<\/span>/', $cineMaterialSerch, $yearMatches);
            if (\in_array(trim($yearMatches[1]), ['2021', '2020'], true)) {
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
            return 'Sin sinopsis';
        }

        return trim(str_replace('Sinopsis', '', $rawText->text()));
    }

    /**
     * @throws MovieAlreadyExtractedException
     */
    private function _checkForMovieExtracted(?string $title): void
    {
        try {
            $this->_movieProvider->getMovieByTitle($title);
            throw new MovieAlreadyExtractedException();
        }catch (MovieNotFoundException $e){
        }

    }
}
