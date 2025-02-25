<?php

namespace App\Classes\FetchArticles;

use App\Classes\TransformArticles\ArticleTransformInterface;
use App\Exceptions\fetchArticlesException;
use App\Exceptions\TransientErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class NewsApiClient
 *
 * This class is an implementation of the BaseNewsApiClient specifically for fetching news articles
 * from a generic news API. It handles retrieval with pagination and error handling.
 *
 * @package App\Classes\FetchArticles
 */
class NewsApiClient extends BaseNewsApiClient
{
    private $pageSize = 20; // Number of articles to fetch per page
    private $limit = 100;    // Maximum limit for articles, required for API developer mode

    /**
     * NewsApiClient constructor.
     *
     * Initializes the client with the news API URL obtained from the configuration.
     */
    public function __construct()
    {
        parent::__construct(config('api.news_api_url'));
    }

    /**
     * Handle the response from the news API.
     *
     * @throws \Exception If the API response indicates an error or on failure to fetch articles.
     * @return array An array of articles fetched from the news API.
     */
    protected function handleResponse()
    {
        $allArticles = [];
        $page = 1;
        $from_date = Carbon::yesterday();

        do {
            $response = Http::get($this->url . "&from=" . $from_date->format('Y-m-d') . "&page=$page&pageSize={$this->pageSize}");
            $data = $response->json();

            if ($data['status'] == "error") {
                throw new \Exception($data["message"]);
            }

            $allArticles = array_merge($allArticles, $data['articles']);
            $totalResults = $this->limit - $this->pageSize ?? 0;

            // If number of articles fetched is less than the total, increment page
            $page++;
        } while (count($allArticles) < $totalResults && count($data['articles']) > 0);

        Log::info("Fetched: " . ($allArticles ? count($allArticles) : 0) . " articles from News API at: " . Carbon::now());
        return $allArticles;
    }
}
