<?php

namespace Link1515\JobNotification\Tests\Services\JobService;

use Link1515\JobNotification\Services\JobService\JobService;
use PHPUnit\Framework\TestCase;

class JobServiceTest extends TestCase
{
    private JobService $jobService;

    public function testFetchJobIdsByKeyword()
    {
        $jobServiceMock = $this->getMockBuilder(JobService::class)
            ->onlyMethods(['fetchJobsByUrl'])
            ->getMock();

        $jobs = [
            [
                'link' => [
                    'job' => 'https://www.104.com.tw/job/aaaaa',
                ]
            ],
            [
                'link' => [
                    'job' => 'https://www.104.com.tw/job/bbbbb',
                ]
            ],
            [
                'link' => [
                    'job' => 'https://www.104.com.tw/job/ccccc',
                ]
            ],
        ];

        $keyword = 'test';

        $jobServiceMock->expects($this->once())
            ->method('fetchJobsByUrl')
            ->with($this->stringContains($keyword))
            ->willReturn($jobs);

        $expectedJobIds = ['aaaaa', 'bbbbb', 'ccccc'];
        $actualJobIds   = $jobServiceMock->fetchJobIdsByKeyword($keyword);

        $this->assertEquals($expectedJobIds, $actualJobIds);
    }
}
