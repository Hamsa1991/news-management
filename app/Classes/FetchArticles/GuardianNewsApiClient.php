<?php


namespace App\Classes\FetchArticles;


use App\Exceptions\fetchArticlesException;
use App\Exceptions\TransientErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuardianNewsApiClient extends BaseNewsApiClient
{
    private $pageSize = 20;

    public function __construct()
    {
        parent::__construct(config('api.guardian_api_url'));
    }

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
            $totalResults = $data['response']['pages'] ?? 0;

            // If number of articles fetched is less than the total, increment page
            $page++;
        } while (count($allArticles) < $totalResults && count($data['response']['results']) > 0);

        return $allArticles;

    }
}
