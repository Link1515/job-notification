<?php

namespace Link1515\JobNotification;

use Link1515\JobNotification\Repositories\JobRepository;
use Link1515\JobNotification\Services\DiscordMessageService;
use Link1515\JobNotification\Services\JobService\JobService;
use Link1515\JobNotification\Utils\LogUtils;

class MainService
{
    public function __construct(
        private readonly string $keyword,
        private readonly JobService $jobService,
        private readonly JobRepository $jobRepository,
        private readonly DiscordMessageService $discordMessageService,
    ) {
    }

    public function run(): void
    {
        LogUtils::log('Fetching jobs...');
        $jobIds = $this->jobService->fetchJobIdsByKeyword($this->keyword);

        if ($this->jobRepository->count() === 0) {
            LogUtils::log('Initializing jobs table...');
            $this->jobRepository->insertJobs($jobIds);
            return;
        }

        $newJobIds = $this->jobRepository->findNewIds($jobIds);
        if (count($newJobIds) === 0) {
            LogUtils::log('No new jobs found.');
            return;
        }

        LogUtils::log('New jobs found: ' . count($newJobIds) . ', and will be sent to Discord.');
        foreach ($newJobIds as $jobId) {
            $jobDetails = $this->jobService->fetchJobDetails($jobId);
            $jobLink    = $this->jobService->getJobDetailsLink($jobId);
            $this->discordMessageService->sendJobMessage($jobLink, $jobDetails);
        }
        $this->jobRepository->insertJobs($newJobIds);
    }
}
