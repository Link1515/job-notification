<?php

require_once __DIR__ . '/bootstrap.php';

use Link1515\JobNotification\DB;
use Link1515\JobNotification\MainService;
use Link1515\JobNotification\Repositories\MysqlJobRepository;
use Link1515\JobNotification\Services\DiscordMessageService;
use Link1515\JobNotification\Services\JobService\RemoteJobService;

$keyword               = '全端 前端 後端 軟體';
$tableName             = 'remote_jobs';
$jobService            = new RemoteJobService();
$jobRepository         = new MysqlJobRepository(DB::getPDO(), $tableName);
$discordMessageService = new DiscordMessageService($_ENV['DISCORD_WEBHOOK']);

$mainService = new MainService(
    $keyword,
    $jobService,
    $jobRepository,
    $discordMessageService
);
$mainService->run();
