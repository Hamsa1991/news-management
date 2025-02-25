<?php

namespace App\Classes\FetchArticles;

use App\Exceptions\fetchArticlesException;
use App\Exceptions\TransientErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class GuardianNewsApiClient
 *
 * This class is an implementation of the BaseNewsApiClient specifically for fetching news articles
 * from the Guardian API. It handles the retrieval of articles, including pagination and error handling.
 *
 * @package App\Classes\FetchArticles
 */
class GuardianNewsApiClient extends BaseNewsApiClient
{
    private $pageSize = 20; // Number of articles to fetch per page

    /**
     * GuardianNewsApiClient constructor.
     *
     * Initializes the client with the Guardian API URL obtained from the configuration.
     */
    public function __construct()
    {
        parent::__construct(config('api.guardian_api_url'));
    }

    /**
     * Handle the response from the Guardian API.
     *
     * @throws \Exception If the API response indicates an error or on failure to fetch articles.
     * @return array An array of articles fetched from the Guardian API.
     */
    protected function handleResponse()
    {
        $allArticles = [];
        $from_date = Carbon::yesterday();
        $page = 1;
        do {
            $response = Http::get($this->url . "&from-date=" . $from_date->format('Y-m-d') . "&page=$page&page-size={$this->pageSize}");
            $data = $response->json();

            if ($data['response']['status'] == "error") {
                throw new \Exception($data["response"]["message"]);
            }

            $allArticles = array_merge($allArticles, $data['response']['results']);
            $totalResults = $data['response']['pages'] - $this->pageSize ?? 0;

            // If number of articles fetched is less than the total, increment page
            $page++;
        } while (count($allArticles) < $totalResults && count($data['response']['results']) > 0);

        Log::info("Fetched: " . ($allArticles ? count($allArticles) : 0) . " articles from the Guardian at: " . Carbon::now());
        return $allArticles;
    }
}
