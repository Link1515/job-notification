<?php

require_once __DIR__ . '/bootstrap.php';

use Link1515\JobNotification\DB;
use Link1515\JobNotification\entities\Job;
use Link1515\JobNotification\Repositories\JobRepository;
use Link1515\JobNotification\Services\JobService;

$data   = JobService::fetchRemoteJobsByKeyword('全端 前端 後端 軟體');
$jobObj = $data[0];

$jobRepository = new JobRepository(DB::getPDO(), 'jobs');

$job = (new Job())
    ->setId($jobObj['jobNo'])
    ->setName($jobObj['jobName'])
    ->setIndustry($jobObj['coIndustryDesc'])
    ->setCompany($jobObj['custName'])
    ->setCompanyLink($jobObj['link']['cust'])
    ->setAddress($jobObj['jobAddrNoDesc'] . $jobObj['jobAddress'])
    ->setLatitude($jobObj['lat'])
    ->setLongitude($jobObj['lon'])
    ->setLink($jobObj['link']['job'])
    ->setPostDate(new \DateTime($jobObj['appearDate']))
    ->setDescription('')
    ->setSalary('');

$jobRepository->insertJob($job);
