<?php
namespace siesta\domain\extraction;

interface MovieScheduleExtractor
{
    /**
     * @param string $url
     * @return mixed
     */
    public function extract(string $url);
}