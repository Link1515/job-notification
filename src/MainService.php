<?php

namespace Link1515\JobNotification;

use Link1515\JobNotification\Repositories\JobRepository;
use Link1515\JobNotification\Services\DiscordMessageService;
use Link1515\JobNotification\Services\JobService\JobService;

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
        $jobIds = $this->jobService->fetchJobIdsByKeyword($this->keyword);

        if ($this->jobRepository->count() === 0) {
            $this->jobRepository->insertJobs($jobIds);
            return;
        }

        $newJobIds = $this->jobRepository->findNewIds($jobIds);
        $newJobIds = array_values($newJobIds);
        foreach ($newJobIds as $jobId) {
            $jobDetails = $this->jobService->fetchJobDetails($jobId);
            $jobLink    = $this->jobService->getJobDetailsLink($jobId);
            $this->discordMessageService->sendJobMessage($jobLink, $jobDetails);
        }
        $this->jobRepository->insertJobs($newJobIds);
    }
}
