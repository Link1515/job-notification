<?php

require_once __DIR__ . '/bootstrap.php';

use Link1515\JobNotification\DB;
use Link1515\JobNotification\entities\Job;
use Link1515\JobNotification\Repositories\JobRepository;
use Link1515\JobNotification\Services\JobService;

$jobService = new JobService();
$data       = $jobService->fetchRemoteJobsByKeyword('全端 前端 後端 軟體');
echo json_encode($data);

// $jobRepository = new JobRepository(DB::getPDO(), 'jobs');

// $result = $jobService->fetchJobDetails('8edun');
// echo json_encode($result);
