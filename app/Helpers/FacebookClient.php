<?php

namespace App\Helpers;

use Sunra\PhpSimple\HtmlDomParser;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;




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
        $result = array('success' => false);
        try {
            $response = $this->client->request($method, $endpoint, $data);
        } catch (RequestException $e) {
            $result['message'] = $e->getMessage();
            return $result;
        } catch (RequestException $e) {
            $result['message'] = $e->getMessage();
            return $result;
        } catch (ClientException $e) {
            $result['message'] = $e->getMessage();
            return $result;
        } catch (ConnectException $e) {
            $result['message'] = $e->getMessage();
            return $result;
        }

        $responseDataXml = $response->getBody()->getContents();

        $this->storeDataXml($responseDataXml);
        return [
            'success' => true,
            'data' => $responseDataXml
        ];
    }

    public function callGraphApi($method, $endpoint, $data = null) {

        $result = array('success' => false, 'statusCode' => '', 'data' => '');
        try {
            $response = $this->client->request($method, $endpoint, [
                'body' => $method !== 'GET' ? $data : null
            ]);
        } catch (RequestException $e) {
            $result['message'] = $e->getMessage();
            return $result;
        } catch (ClientException $e) {
            $result['message'] = $e->getMessage();
            return $result;
        } catch (ConnectException $e) {
            $result['message'] = $e->getMessage();
            return $result;
        }

        if (!$response) {
            return $result;
        }

        $result['success'] = true;
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