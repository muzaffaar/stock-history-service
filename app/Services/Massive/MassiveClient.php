<?php

namespace App\Services\Massive;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MassiveClient
{
    protected PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::acceptJson()
            ->baseUrl(config('history.base_url'))
            ->timeout(30)
            ->retry(3, 1000);
    }

    public function get(string $uri, array $query = []): array
    {
        $query['apiKey'] = config('history.api_key');

        return $this->http
            ->get($uri, $query)
            ->throw()
            ->json();
    }

    public function getNext(string $nextUrl): array
    {
        $parts = parse_url($nextUrl);

        parse_str($parts['query'] ?? '', $query);

        $query['apiKey'] = config('history.api_key');

        return $this->http
            ->get($parts['path'], $query)
            ->throw()
            ->json();
    }
}
