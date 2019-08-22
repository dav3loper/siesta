<?php
namespace siesta\domain\extraction;

use siesta\domain\movie\Movie;

interface MovieExtractor
{
    /**
     * @param string $url
     * @return Movie[]
     */
    public function extract(string $url): array;
}