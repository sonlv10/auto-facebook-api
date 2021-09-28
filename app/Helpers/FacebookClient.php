<?php

namespace App\Helpers;

use Sunra\PhpSimple\HtmlDomParser;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;



class FacebookClient
{
    protected $client;

    public function __construct(array $data = []) {
        $dataClient = [];
        if (!empty($data['cookies'])) {
            $dataClient['cookies'] = CookieJar::fromArray($data['cookies'], 'facebook.com');
        }
        if (!empty($data['headers'])) {
            $dataClient['headers'] = $data['headers'];
        }
        $this->client = new Client($dataClient);
    }

    public function callAPI($method, $endpoint, $data = []) {
        try {
            $response = $this->client->request($method, $endpoint, $data);
        } catch (RequestException $e) {
            return
                array(
                    'status' => false,
                    'message' => $e->getMessage()
                );
        }

        $responseDataXml = $response->getBody()->getContents();

        $this->storeDataXml($responseDataXml);
        return $responseDataXml;
    }

    public function callGraphApi($method, $endpoint, $data = null) {
        try {
            $response = $this->client->request($method, $endpoint, [
                'body' => $method !== 'GET' ? $data : null
            ]);
        } catch (RequestException $e) {
            return
                array(
                    'status' => false,
                    'message' => $e->getMessage()
                );
        }
        $result = array('statusCode' => '', 'wsData' => '');

        if (!$response) {
            return $result;
        }

        $result['statusCode'] = $response->getStatusCode();
        $result['data'] = json_decode($response->getBody(), true);


        return $result;
    }

    public function storeDataXml($responseDataXml, $path = 'test')
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