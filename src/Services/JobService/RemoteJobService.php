<?php

namespace Link1515\JobNotification\Services\JobService;

use Link1515\JobNotification\Utils\StringUtils;

class RemoteJobService extends JobService
{
    public function fetchJobIdsByKeyword(string $keyword): array
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
        $url = self::LIST_URL . '?' . http_build_query($queryParams) ;

        $jobs = $this->fetchJobsByUrl($url);
        $this->filterRemoteJobs($jobs);
        $jobIds = $this->getJobIds($jobs);

        return $jobIds;
    }

    private function filterRemoteJobs(array &$jobs): void
    {
        $needles = ['遠端', '遠距', '居家', 'remote', 'wfh'];
        $jobs    = array_filter($jobs, function ($job) use ($needles) {
            $jobName = strtolower($job['jobName']);
            return StringUtils::stringContainAny($jobName, $needles);
        });
    }
}
