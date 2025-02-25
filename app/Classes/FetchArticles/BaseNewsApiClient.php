<?php

namespace App\Classes\FetchArticles;

use App\Exceptions\TransientErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Class BaseNewsApiClient
 *
 * This abstract class serves as a base client for fetching news articles from a news API.
 * It implements a retry mechanism to handle transient errors like network timeouts.
 * Subclasses should implement the `handleResponse` method to provide specific response handling logic.
 *
 * @package App\Classes\FetchArticles
 */
abstract class BaseNewsApiClient implements NewsAPIInterface
{
    protected $url;

    const MAX_RETRIES = 5; // Maximum number of retry attempts
    const BASE_DELAY = 100; // Base delay in milliseconds for exponential backoff

    /**
     * BaseNewsApiClient constructor.
     *
     * @param string $url The endpoint URL of the news API.
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Handle the response from the news API.
     *
     * @return mixed The processed result from the API response.
     */
    abstract protected function handleResponse();

    /**
     * Fetch articles from the news API with retry mechanism.
     *
     * @throws \Exception If fetching the articles fails after the maximum number of attempts.
     * @return mixed The articles fetched from the API.
     */
    public function fetchArticles()
    {
        $attempt = 0;

        while ($attempt < self::MAX_RETRIES) {
            try {
                // Simulate a transient error (50% chance)
                if (mt_rand(0, 1)) {
                    throw new TransientErrorException("Network timeout occurred.");
                }

                return $this->handleResponse();

            } catch (TransientErrorException $e) {
                $attempt++;
                if ($attempt >= self::MAX_RETRIES) {
                    throw new \Exception("Failed to fetch news after $attempt attempts.");
                }
                // Exponential backoff delay logic
                $delay = self::BASE_DELAY * (2 ** $attempt);
                usleep($delay * 1000); // Convert milliseconds to microseconds
            }
        }

        throw new \Exception("Failed to fetch news");
    }
}
