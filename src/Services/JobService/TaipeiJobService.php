<?php

namespace Link1515\JobNotification\Services\JobService;

class TaipeiJobService extends JobService
{
    public function fetchJobIdsByKeyword(string $keyword): array
    {
        $url    = $this->getApiUrl($keyword);
        $jobIds = $this->fetchJobIdsByUrl($url);

        return $jobIds;
    }

    private function getApiUrl(string $keyword): string
    {
        $queryParams = [
            'area'      => '6001001000,6001002000',
            'jobsource' => 'joblist_search',
            'keyword'   => $keyword,
            'mode'      => 's',
            'ro'        => 1, // 全職
            'order'     => 16, // 最新更新
            'page'      => 1,
            'pagesize'  => 20
        ];
        $url = self::LIST_API_URL . '?' . http_build_query($queryParams) ;

        return $url;
    }
}
