<?php

require_once __DIR__ . '/vendor/autoload.php';

use Link1515\JobNotification\Services\JobService;

$data = JobService::fetchTaipeiJobsByKeyword('php');
