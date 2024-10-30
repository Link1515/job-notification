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
                name VARCHAR(255) NOT NULL,
                industry VARCHAR(255) NOT NULL,
                company VARCHAR(255) NOT NULL,
                companyLink VARCHAR(255) NOT NULL,
                address VARCHAR(255) NOT NULL,
                landmark VARCHAR(255),
                latitude DECIMAL(9, 6) NOT NULL,
                longitude DECIMAL(9, 6) NOT NULL,
                link VARCHAR(255) NOT NULL,
                postDate TIMESTAMP NOT NULL,
                description TEXT NOT NULL,
                salary VARCHAR(255) NOT NULL,
                CONSTRAINT job_pk PRIMARY KEY (id)
            ) ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci;
        SQL;

        $this->pdo->exec($sql);
    }

    public function insertJob(Job $job): bool
    {
        $result = $this->pdo->prepare(
            "INSERT INTO 
                        `{$this->tableName}` (id, name, industry, company, companyLink, address, landmark, latitude, longitude, link, postDate, description, salary) 
                    VALUES 
                        (:id, :name, :industry, :company, :companyLink, :address, :landmark, :latitude, :longitude, :link, :postDate, :description, :salary)"
        )->execute([
            'id'          => $job->id,
            'name'        => $job->name,
            'industry'    => $job->industry,
            'company'     => $job->company,
            'companyLink' => $job->companyLink,
            'address'     => $job->address,
            'landmark'    => $job->landmark,
            'latitude'    => $job->latitude,
            'longitude'   => $job->longitude,
            'link'        => $job->link,
            'postDate'    => $job->postDate->format('Y-m-d'),
            'description' => $job->description,
            'salary'      => $job->salary,
        ]);

        return $result;
    }

    public function insertJobs(array $jobs)
    {
        $sql = "INSERT INTO `{$this->tableName}` (id, name, industry, company, companyLink, address, landmark, latitude, longitude, link, postDate, description, salary) VALUES ";
        foreach ($jobs as $job) {
            $sql .= "('{$job->id}', '{$job->name}', '{$job->industry}', '{$job->company}', '{$job->companyLink}', '{$job->address}', '{$job->landmark}', {$job->latitude}, {$job->longitude}, '{$job->link}', {$job->postDate}, '{$job->description}', '{$job->salary}'), ";
        }
        $sql = substr($sql, 0, -2);

        $this->pdo->exec($sql);
    }
}
