<?php


namespace siesta\domain\extraction;


interface ScheduleExtractor
{

    public function extract(string $url): array;
}
