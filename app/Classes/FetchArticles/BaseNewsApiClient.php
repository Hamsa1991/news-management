<?php


namespace App\Classes\FetchArticles;


use App\Exceptions\TransientErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

abstract class BaseNewsApiClient implements NewsAPIInterface
{
    protected $url;
    const MAX_RETRIES = 5; // Maximum attempts
    const BASE_DELAY = 100; // Base delay in milliseconds

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    abstract protected function handleResponse();

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
