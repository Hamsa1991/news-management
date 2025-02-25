<?php

namespace App\Classes\FetchArticles;

/**
 * Class NewsApiContext
 *
 * This class serves as a context for managing the news API client. It allows the setting of a specific
 * news API client and facilitates the fetching of articles through that client.
 *
 * @package App\Classes\FetchArticles
 */
class NewsApiContext
{
    private $newsApiClient; // The instance of the news API client being used

    /**
     * Set the news API client.
     *
     * This method allows you to set a specific instance of a news API client, enabling the
     * context to use that client for fetching articles.
     *
     * @param mixed $newsApiClient An instance of a news API client that implements a fetchArticles method.
     */
    public function setNewsApiClient($newsApiClient)
    {
        $this->newsApiClient = $newsApiClient;
    }

    /**
     * Fetch articles using the set news API client.
     *
     * @param mixed $articles An input for the fetchArticles method. Use case depends on the specific client implementation.
     * @return mixed The result of the fetchArticles call, typically an array of articles.
     */
    public function fetchArticles($articles)
    {
        return $this->newsApiClient->fetchArticles($articles);
    }
}
