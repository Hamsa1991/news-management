<?php


namespace App\Classes\FetchArticles;


use Illuminate\Support\Facades\Http;

class NewsIoClient extends BaseNewsApiClient
{
    public function __construct()
    {
        $this->url = config('api.news_io_url');
    }

    protected function handleResponse()
    {
        $data = Http::get($this->url)->json();
        if ($data['status'] == "error") {
            throw new \Exception($data["results"]["message"]);
        }
        return $data['results'] ?? [];
    }
}
