<?php

require_once __DIR__ . '/vendor/autoload.php';

use Link1515\JobNotification\Services\JobService;

$data = JobService::fetchRemoteJobsByKeyword('全端 前端 後端 軟體');

echo count($data);
