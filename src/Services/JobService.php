<?php

namespace Link1515\JobNotification\Services;

use Link1515\JobNotification\Utils\HttpUtils;
use Link1515\JobNotification\Utils\StringUtils;

class JobService
{
    public const LIST_URL    = 'https://www.104.com.tw/jobs/search/api/jobs';
    public const DETAILS_URL = 'https://www.104.com.tw/job/ajax/content';
    public const HEADERS     = ['Referer: https://www.104.com.tw'];

    public function fetchJobsByUrl(string $url): array
    {
        $response = HttpUtils::getJson($url, [], self::HEADERS);
        $jobs     = $response['data'];
        $this->filterEngineerJobs($jobs);

        return $jobs;
    }

    public function fetchJobIdsByUrl(string $url): array
    {
        $jobs   = $this->fetchJobsByUrl($url);
        $jobIds = $this->getJobIds($jobs);

        return $jobIds;
    }

    protected function getJobIds(array $jobs): array
    {
        $jobIds = [];
        foreach ($jobs as $job) {
            $jobUrl    = $job['link']['job'];
            $jobUrlArr = explode('/', $jobUrl);
            $jobId     = array_pop($jobUrlArr);
            $jobIds[]  = $jobId;
        }
        return $jobIds;
    }

    protected function fetchJobDetails(string $jobId)
    {
        $response = HttpUtils::getJson(self::DETAILS_URL . "/{$jobId}", [], self::HEADERS);
        $details  = $response['data'];

        return $details;
    }

    protected function filterEngineerJobs(array &$jobs): void
    {
        $needles = ['前端', '後端', '軟體', '工程師', 'frontend', 'backend', 'software', 'engineer', 'developer'];

        $jobs = array_filter($jobs, function ($job) use ($needles) {
            $jobName = strtolower($job['jobName']);
            return StringUtils::stringContainAny($jobName, $needles);
        });
    }
}
