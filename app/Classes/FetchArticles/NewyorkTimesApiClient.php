<?php


namespace App\Classes\FetchArticles;


use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewyorkTimesApiClient extends BaseNewsApiClient
{
    private $pageSize = 10;

    public function __construct()
    {
        parent::__construct(config('api.newyork_times_url'));
    }

    protected function handleResponse()
    {
        $allArticles = [];
        $from_date = Carbon::yesterday();
        $page = 1; // Starting page

//        do {
            $response = Http::get($this->url . "&pub_date=" . $from_date->format('Y-m-d') . "&page=$page&limit={$this->pageSize}");

            $data = $response->json();

            // Handle the response and check for errors

            if ($data['status'] == 'error') {
                throw new \Exception($data['message']);
            }

            // Merge articles from this response into the main collection
            $allArticles = array_merge($allArticles, $data['response']['docs'] ?? []);

            // Check if there are any more articles available
    //            $totalResults = $data['response']['meta']['hits'] ?? 0;
    //            $page++;

//        } while (count($allArticles) < $totalResults);

        return $allArticles;

    }
}
