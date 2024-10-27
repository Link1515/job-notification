<?php

require_once __DIR__ . '/vendor/autoload.php';

use Link1515\JobNotification\Services\JobService;

// $data = JobService::fetchTaipeiJobsByKeyword('php');
$data = JobService::fetchJobsByUrl('https://www.104.com.tw/jobs/search/api/jobs?jobsource=index_s&keyword=%E9%81%A0%E7%AB%AF&mode=s&order=15&page=1&pagesize=20');

print_r($data);
