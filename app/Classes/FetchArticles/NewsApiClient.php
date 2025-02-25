<?php


namespace App\Classes\FetchArticles;


use App\Classes\TransformArticles\ArticleTransformInterface;
use App\Exceptions\fetchArticlesException;
use App\Exceptions\TransientErrorException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsApiClient extends BaseNewsApiClient
{
    private $pageSize = 20;
    private $limit = 100;//this limit is required for the api developer mode

    public function __construct()
    {
        parent::__construct(config('api.news_api_url'));
    }

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

        return $allArticles;

    }
}
