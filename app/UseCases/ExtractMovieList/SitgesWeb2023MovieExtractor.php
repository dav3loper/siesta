<?php
namespace App\UseCases\ExtractMovieList;

use App\Helpers\FinderVideoService;
use DiDom\Element;
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
class SitgesWeb2023MovieExtractor implements MovieExtractor
{
    private const MOVIE_ELEMENT_CLASS = 'c-movie-item';
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
    private const SITGES_HOST = 'http://sitgesfilmfestival.com';
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
        unset($elements[0]);
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
        $sectionTitle = $movie->first('li.section-title')->firstChild()->text();
        if (preg_match('/' . implode('|', self::NON_VOTABLE_MOVIES) . '/', $sectionTitle)) {

            throw new MovieNotForVoteException();
        }
    }

    /**
     * @param \DiDom\Element $movie
     * @return mixed
     */
    private function _getTitleFromMovieElement(\DiDom\Element $movie) //TODO: sacar a clase dominio
    {
        $title = $movie->first('h4.movie-title')->text();
        $link = $movie->first('a.js_link')->getAttribute('href');

        return [$title, self::SITGES_HOST.$link];
    }

    /**
     * @param $title
     * @return string
     */
    private function _getTrailer($title): string
    {
        $videoByText = $this->_finderVideoService->findVideoByText("\"$title\" official trailer");
        return $videoByText;
    }

    /**
     * @param string $link
     * @return int
     * @throws MovieNotForVoteException
     */
    private function _getDuration(string $link): int
    {
        $rawTextList = $this->_htmlParser->getElementsByClass($link, 'field--name-field-duration');
        /** @var Element $rawText */
        $rawText = current($rawTextList);

        if (!$rawText) {
            return 999;
        }

        $duration = intval(trim($rawText->first('div.field__item')->text()));
        if ($duration != 0 && $duration < 60) {
            throw new MovieNotForVoteException();
        }
        if(!$duration || !is_int($duration)){
            return 999;
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
        $url = $movie->first('img')->getAttribute('src');
        $url = preg_replace('#/limit_[^/]*/#', '/limit_height_1080_width_1920/', $url);
        return $url;
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
            if (\in_array(trim($yearMatches[1]), ['2023', '2022'], true)) {
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
        $rawTextList = $this->_htmlParser->getElementsByClass($link, 'field--name-field-multi-synopsis');
        /** @var Element $rawText */
        $rawText = current($rawTextList);
        if (!$rawText) {
            return 'Sin sinopsis';
        }

        return trim($rawText->first('div.field__item')->text());
    }

    /**
     * @throws MovieAlreadyExtractedException
     */
    private function _checkForMovieExtracted(?string $title): void
    {
        try {
            $movie = $this->_movieProvider->getMovieByTitle($title);
            if($movie->getTrailerId() != 'notrailer') {
                throw new MovieAlreadyExtractedException();
            }
        }catch (MovieNotFoundException $e){
        }

    }
}
