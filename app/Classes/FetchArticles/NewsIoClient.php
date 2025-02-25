<?php

namespace App\Classes\FetchArticles;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class NewsIoClient
 *
 * This class is an implementation of the BaseNewsApiClient specifically for fetching news articles
 * from the News API provided by News IO. It handles the retrieval of articles and error handling.
 *
 * @package App\Classes\FetchArticles
 */
class NewsIoClient extends BaseNewsApiClient
{
    /**
     * NewsIoClient constructor.
     *
     * Initializes the client with the News IO API URL obtained from the configuration.
     */
    public function __construct()
    {
        $this->url = config('api.news_io_url');
    }

    /**
     * Handle the response from the News IO API.
     *
     * @throws \Exception If the API response indicates an error.
     * @return array An array of articles fetched from the News IO API.
     */
    protected function handleResponse()
    {
        $data = Http::get($this->url)->json();
        if ($data['status'] == "error") {
            throw new \Exception($data["results"]["message"]);
        }
        Log::info("Fetched: " . ($data['results'] ? count($data['results']) : 0) . " articles from News IO at: " . Carbon::now());
        return $data['results'] ?? [];
    }
}
