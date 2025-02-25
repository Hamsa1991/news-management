<?php


namespace App\Classes\TransformArticles;


use App\Exceptions\ArticlesNotFoundException;
use Carbon\Carbon;

class GuardianNewsTransformer implements ArticleTransformInterface
{

    public function transform($articles)
    {
        $result = [];
        if($articles) {
            foreach ($articles as $article) {
                $result[] = [
                    'title' => $article['webTitle'],
                    'description' => null,
                    'content' => null,
                    'url' => $article['webUrl'],
                    'image_url' => $article['urlToImage'] ?? null,
                    'published_at' => Carbon::parse($article['webPublicationDate']),
                    'category' => $article['sectionName'],
                    'source' => "The Guardian",
                    'authors' => null,
                ];
            }
        }
        return $result;
    }
}
