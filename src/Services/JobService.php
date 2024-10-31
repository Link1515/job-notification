<?php

namespace Link1515\JobNotification\Services;

use Link1515\JobNotification\Utils\HttpUtils;
use Link1515\JobNotification\Utils\StringUtils;

class JobService
{
    public const LIST_URL    = 'https://www.104.com.tw/jobs/search/api/jobs';
    public const DETAILS_URL = 'https://www.104.com.tw/job/ajax/content';
    public const HEADERS     = ['Referer: https://www.104.com.tw'];

    public function fetchTaipeiJobsByKeyword(string $keyword): array
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

        $response = HttpUtils::getJson(self::LIST_URL, $queryParams, self::HEADERS);
        $jobs     = $response['data'];

        return $jobs;
    }

    public function fetchRemoteJobsByKeyword(string $keyword): array
    {
        $queryParams = [
            'jobsource'  => 'joblist_search',
            'keyword'    => $keyword,
            'mode'       => 's',
            'order'      => 16,
            'page'       => 1,
            'pagesize'   => 20,
            'remoteWork' => 1, // 完全遠端
            'searchJobs' => 1
        ];

        $response = HttpUtils::getJson(self::LIST_URL, $queryParams, self::HEADERS);
        $jobs     = $response['data'];

        $this->filterEngineerJobs($jobs);
        $this->filterRemoteJobs($jobs);

        return array_values($jobs);
    }

    public function fetchJobsByUrl(string $url): array
    {
        $response = HttpUtils::getJson($url, [], self::HEADERS);
        $jobs     = $response['data'];

        return $jobs;
    }

    private function filterEngineerJobs(array &$jobs): void
    {
        $needles = ['前端', '後端', '軟體', '工程師', 'frontend', 'backend', 'software', 'engineer', 'developer'];

        $jobs = array_filter($jobs, function ($job) use ($needles) {
            $jobName = strtolower($job['jobName']);
            return StringUtils::stringContainAny($jobName, $needles);
        });
    }

    private function filterRemoteJobs(array &$jobs): void
    {
        $needles = ['遠端', '遠距', '居家', 'remote', 'wfh'];
        $jobs    = array_filter($jobs, function ($job) use ($needles) {
            $jobName = strtolower($job['jobName']);
            return StringUtils::stringContainAny($jobName, $needles);
        });
    }

    public function fetchJobDetails(string $detailId)
    {
        $response = HttpUtils::getJson(self::DETAILS_URL . "/{$detailId}", [], self::HEADERS);
        $details  = $response['data'];

        return $details;
    }
}
