<?php


namespace App\Classes\FetchArticles;


class NewsApiContext
{
    private $newsApiClient;


    public function setNewsApiClient($newsApiClient)
    {
        $this->newsApiClient = $newsApiClient;
    }

    public function fetchArticles($articles)
    {
        return $this->newsApiClient->fetchArticles($articles);
    }
}
