<?php

namespace App\Classes\FetchArticles;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class NewyorkTimesApiClient
 *
 * This class is an implementation of the BaseNewsApiClient specifically for fetching news articles
 * from the New York Times API. It handles the retrieval of articles and error management.
 *
 * @package App\Classes\FetchArticles
 */
class NewyorkTimesApiClient extends BaseNewsApiClient
{
    private $pageSize = 10; // Number of articles to fetch per page

    /**
     * NewyorkTimesApiClient constructor.
     *
     * Initializes the client with the New York Times API URL obtained from the configuration.
     */
    public function __construct()
    {
        parent::__construct(config('api.newyork_times_url'));
    }

    /**
     * Handle the response from the New York Times API.
     *
     * @throws \Exception If the API response indicates an error or on failure to fetch articles.
     * @return array An array of articles fetched from the New York Times API.
     */
    protected function handleResponse()
    {
        $allArticles = [];
        $from_date = Carbon::yesterday();
        $page = 1; // Starting page

        $response = Http::get($this->url . "&pub_date=" . $from_date->format('Y-m-d') . "&page=$page&limit={$this->pageSize}");
        $data = $response->json();

        // Handle the response and check for errors
        if ($data['status'] == 'error') {
            throw new \Exception($data['message']);
        }

        // Merge articles from this response into the main collection
        $allArticles = array_merge($allArticles, $data['response']['docs'] ?? []);

        Log::info("Fetched: " . ($allArticles ? count($allArticles) : 0) . " articles from New York Times at: " . Carbon::now());
        return $allArticles;
    }
}
