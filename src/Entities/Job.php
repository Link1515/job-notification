<?php

namespace Link1515\JobNotification\entities;

class Job
{
    public function __construct(public string $id, public \DateTime $createdAt)
    {
    }
}
