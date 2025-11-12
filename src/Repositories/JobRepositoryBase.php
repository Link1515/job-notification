<?php

namespace Link1515\JobNotification\Repositories;

use Link1515\JobNotification\DB;
use Link1515\JobNotification\Repositories\Interface\JobRepository;

class JobRepositoryBase
{
    public static function getRepository(string $tableName): JobRepository
    {
        switch ($_ENV['DB_DRIVER']) {
            case 'mysql':
                return new MysqlJobRepository(DB::getPDO(), $tableName);
        }
        throw new \Exception('Unknown DB driver: ' . $_ENV['DB_DRIVER']);
    }
}
