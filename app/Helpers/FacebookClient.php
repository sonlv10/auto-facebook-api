<?php

namespace App\Helpers;

use Sunra\PhpSimple\HtmlDomParser;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;



class FacebookClient
{
    protected $client;

    public function __construct(array $data) {
        $jar = CookieJar::fromArray($data['cookies'], 'facebook.com');
        $this->client = $client = new Client([
            'cookies' => $jar,
            'headers' => [
                'User-Agent' => $data['userAgent'],
                'Sec-Fetch-User' => '?1',
            ]]);
    }

    public function getPageContent($path) {
        try {
            $pageUrl = config('facebook.mbasic_domain') . $path;

            $response = $this->client->request('GET', $pageUrl);
            $resultshtml = $response->getBody()->getContents();
            $this->storeDataXml($resultshtml, $path);
            return $resultshtml;
        } catch (RequestException $e) {
            return
                array(
                    'status' => false,
                    'message' => $e->getMessage()
                );
        }
    }

    public function storeDataXml($responseDataXml, $path)
    {
        $result = [
            'file_path' => null,
            'url' => null
        ];
        $filePath = $logPath = 'logs/facebook/' . now()->format('ymd'). '/' . $path . '.html';
        $uploadFile = Storage::put($filePath, $responseDataXml);
        if ($uploadFile) {
            $url = Storage::url($filePath);
            $result = [
                'file_path' => $filePath,
                'xml_link' => $url
            ];
        }
        return $result;

    }
}