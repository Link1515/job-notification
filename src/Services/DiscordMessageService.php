<?php

namespace Link1515\JobNotification\Services;

use Link1515\JobNotification\Utils\HttpUtils;

class DiscordMessageService
{
    private string $webhook;

    public function __construct(string $webhook)
    {
        $this->webhook = $webhook;
    }

    public function sendJobMessage(string $jobLink, array $job): void
    {
        $postDate    = $job['header']['appearDate'];
        $jobName     = $job['header']['jobName'];
        $companyName = $job['header']['custName'];
        $companyLink = $job['header']['custUrl'];
        $employees   = $job['employees'];
        $industry    = $job['industry'];
        $address     = $job['jobDetail']['addressRegion'];
        $longitude   = $job['jobDetail']['longitude'];
        $latitude    = $job['jobDetail']['latitude'];
        $workPeriod  = $job['jobDetail']['workPeriod']['note'];
        $description = $job['jobDetail']['jobDescription'];
        $salary      = $job['jobDetail']['salary'];
        $benefits    = $job['welfare']['welfare'];

        // discord webhook api 限制只能 10 個 embed，文字佔 1 個，最多只能 9 張照片
        $images      = \array_slice($job['environmentPic']['environmentPic'], 0, 9);
        $imageEmbeds = [];
        foreach ($images as $image) {
            $imageEmbeds[] = [
                'url'   => $jobLink,
                'image' => [
                    'url' => $image['link'],
                ]
            ];
        }

        $message = <<<Message
        發布日期: {$postDate}
        產業類別: {$industry}
        公司名稱: [{$companyName}]({$companyLink})
        人數: {$employees}
        地址: [{$address}](https://www.google.com/maps?q={$latitude},{$longitude})
        工作時段: {$workPeriod}

        ### 職務描述: 
        {$description}    

        ### 薪資: {$salary}

        ### 福利: 
        {$benefits}
        Message;

        $this->sendEmbedMessage([
            [
                'title'       => $jobName,
                'description' => $message,
                'url'         => $jobLink,
                'color'       => 16032353,
            ],
            ...$imageEmbeds
        ]);
    }

    public function sendMessage(string $message): void
    {
        HttpUtils::postJson($this->webhook, [
            'content' => $message,
        ]);
    }

    /**
     * Embed structure: https://discord.com/developers/docs/resources/message#embed-object-embed-structure
     * @return void
     */
    public function sendEmbedMessage(array $options): void
    {
        HttpUtils::postJson($this->webhook, [
            'embeds' => $options,
        ]);
    }
}
