<?php

namespace Link1515\JobNotification\Services;

use Link1515\JobNotification\Utils\HttpUtils;

class JobService
{
    public const URL     = 'https://www.104.com.tw/jobs/search/api/jobs';
    public const HEADERS = ['Referer: https://www.104.com.tw/jobs/search/?'];

    public static function fetchTaipeiJobsByKeyword(string $keyword): array
    {
        $queryParams = [
            'area'      => '6001001000,6001002000',
            'jobsource' => 'joblist_search',
            'keyword'   => $keyword,
            'mode'      => 's',
            'order'     => 15,
            'page'      => 1,
            'pagesize'  => 20
        ];

        $response = HttpUtils::getJson(self::URL, $queryParams, self::HEADERS);

        return $response;
    }
}
