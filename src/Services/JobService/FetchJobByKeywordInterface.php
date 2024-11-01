<?php

namespace Link1515\JobNotification\Services\JobService;

interface FetchJobByKeywordInterface
{
    public function fetchJobIdsByKeyword(string $keyword);
}
