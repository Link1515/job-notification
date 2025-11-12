<?php

namespace Link1515\JobNotification\Repositories;

use Link1515\JobNotification\Repositories\Interface\JobRepository;
use PDO;

class SqliteJobRepository implements JobRepository
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
        $sql = "
            SELECT name
            FROM sqlite_master
            WHERE type = 'table'
                AND name = :table
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':table' => $this->tableName]);
        return $stmt->fetchColumn() !== false;
    }

    public function createJobsTable(): void
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$this->tableName}` (
                id TEXT NOT NULL,
                created_at DATETIME NOT NULL,
                CONSTRAINT job_pk PRIMARY KEY (id)
            );
        SQL;

        $this->pdo->exec($sql);
    }

    public function count(): int
    {
        $sql    = "SELECT COUNT(*) FROM `{$this->tableName}`";
        $result = $this->pdo->query($sql);
        return $result->fetchColumn();
    }

    public function findNewIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $sql          = "SELECT id FROM `{$this->tableName}` WHERE id IN ({$placeholders})";
        $stmt         = $this->pdo->prepare($sql);
        $stmt->execute($ids);
        $existedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $newIds = array_values(array_diff($ids, $existedIds));

        return $newIds;
    }

    public function insertJobs(array $ids): void
    {
        if (empty($ids)) {
            return;
        }
        $now = (new \DateTimeImmutable())->format('Y-m-d H:i:s');

        $values = [];
        $params = [];
        foreach ($ids as $id) {
            $values[] = '(?, ?)';
            $params[] = $id;
            $params[] = $now;
        }
        $sql  = "INSERT INTO `{$this->tableName}` (id, created_at) VALUES " . implode(', ', $values);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }
}
