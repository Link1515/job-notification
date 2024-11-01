<?php

require_once __DIR__ . '/bootstrap.php';

use Link1515\JobNotification\DB;
use Link1515\JobNotification\Repositories\JobRepository;
use Link1515\JobNotification\Services\JobService;

$jobService = new JobService();
$ids        = $jobService->fetchRemoteJobsByKeyword('全端 前端 後端 軟體');

$jobRepository = new JobRepository(DB::getPDO(), 'jobs');
if ($jobRepository->count() === 0) {
    $jobRepository->insertJobs($ids);
} else {
    $newIds = $jobRepository->findNewIds($ids);
    $jobRepository->insertJobs($newIds);
}

// $result = $jobService->fetchJobDetails('8edun');
// echo json_encode($result);
