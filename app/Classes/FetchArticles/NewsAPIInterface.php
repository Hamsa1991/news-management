<?php

namespace App\Classes\FetchArticles;

/**
 * Interface NewsAPIInterface
 *
 * This interface defines the contract for fetching articles from a news API.
 * Any class implementing this interface must provide an implementation of
 * the fetchArticles method.
 *
 * @package App\Classes\FetchArticles
 */
interface NewsAPIInterface
{
    /**
     * Fetch articles from the news API.
     *
     * @return mixed An array of articles fetched from the API.
     */
    public function fetchArticles();
}
