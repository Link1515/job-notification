<?php

namespace Link1515\JobNotification\Repositories;

use Link1515\JobNotification\Entities\Job;
use PDO;

class JobRepository
{
    private PDO $pdo;
    private string $tableName;

    public function __construct(PDO $pdo, string $tableName)
    {
        $this->pdo       = $pdo;
        $this->tableName = $tableName;

        if (!$this->tableExists()) {
            $this->createJobsTable();
        }
    }

    public function tableExists(): bool
    {
        $sql    = "SHOW TABLES LIKE '{$this->tableName}'";
        $result = $this->pdo->query($sql);
        return $result->rowCount() > 0;
    }

    public function createJobsTable(): void
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
                id VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL,
                CONSTRAINT job_pk PRIMARY KEY (id)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        SQL;

        $this->pdo->exec($sql);
    }

    public function insertJobs(array $jobs)
    {
        $sql = "INSERT INTO `{$this->tableName}` (id, created_at) VALUES ";
        $now = new \DateTime();
        /** @var Job $job */
        foreach ($jobs as $job) {
            $sql .= "('{$job->id}', '{$now->format('Y-m-d H:i:s')}'), ";
        }
        $sql = substr($sql, 0, -2);

        $this->pdo->exec($sql);
    }
}
