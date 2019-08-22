<?php
namespace siesta\infrastructure\movie\http;

use DiDom\Document;

class SimpleDomHtmlParser implements HtmlParser
{

    /**
     * @param string $urlOrPath
     * @param string $class
     * @return \DiDom\Element[]|\DOMElement[]
     * @throws \InvalidArgumentException
     */
    public function getElementsByClass(string $urlOrPath, string $class): array
    {
        $document = new Document($urlOrPath, true);

        return $document->find(".$class");
    }

}