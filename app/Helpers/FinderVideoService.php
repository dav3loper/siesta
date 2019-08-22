<?php
namespace App\Helpers;

interface FinderVideoService
{

    /**
     * @param string $text
     * @return string
     */
    public function findVideoByText(string $text): string;
}