<?php

namespace Link1515\JobNotification\Repositories\Interface;

interface JobRepository {
    public function tableExists(): bool;
    public function createJobsTable(): void;
    public function count(): int;

    public function findNewIds(array $ids): array;
    public function insertJobs(array $ids): void;
}