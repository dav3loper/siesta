<?php
namespace siesta\infrastructure\movie\http;

interface HtmlParser
{
    /**
     * @param string $class
     * @return array
     */
    public function getElementsByClass(string $urlOrPath, string $class): array;
}