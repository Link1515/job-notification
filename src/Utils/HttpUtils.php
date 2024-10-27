<?php

namespace Link1515\JobNotification\Utils;

class HttpUtils
{
    public static function get($url, $queryParams = [], $headers = []): string
    {
        $ch  = curl_init();
        $url = empty($queryParams) ?
            $url :
            $url . '?' . http_build_query($queryParams) ;

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        return $response;
    }

    public static function getJson($url, $queryParams = [], $headers = []): array
    {
        $response = self::get($url, $queryParams, $headers);

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON error: ' . json_last_error_msg());
        }

        return $data;
    }
}
