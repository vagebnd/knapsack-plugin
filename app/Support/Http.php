<?php

namespace Skeleton\Support;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Http
{
    private $options = [];
    private $baseUrl = '';

    public function baseUrl(string $url)
    {
        $this->baseUrl = $url;
        return $this;
    }

    public function withToken($token, $type = 'Bearer')
    {
        $this->options['headers']['Authorization'] = trim($type.' '.$token);
        return $this;
    }

    public function get(string $url, $query = [])
    {
        return $this->send('GET', $url, [
            'query' => $query,
        ]);
    }

    public function post(string $url, $data = [])
    {
        return $this->send('POST', $url, [
            'json' => $data,
        ]);
    }

    private function send(string $method, string $url, array $options = [])
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            $url = ltrim(rtrim($this->baseUrl, '/').'/'.ltrim($url, '/'), '/');
        }

        return $this->createClient()->request($method, $url, array_merge_recursive($this->options, $options));
    }

    private function createClient()
    {
        return new Client([
            'cookies' => true,
        ]);
    }
}
