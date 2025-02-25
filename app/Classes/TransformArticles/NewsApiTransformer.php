<?php


namespace App\Classes\TransformArticles;


use App\Exceptions\ArticlesNotFoundException;
use Carbon\Carbon;

class NewsApiTransformer implements ArticleTransformInterface
{

    public function transform($articles)
    {
        $result = [];
        if ($articles) {
            foreach ($articles as $article) {
                // Split authors by commas
                $authors = explode(',', $article['author']);

                $result[] = [
                    'title' => $article['title'],
                    'description' => $article['description'] ?? null,
                    'content' => $article['content'] ?? null,
                    'url' => $article['url'],
                    'image_url' => $article['urlToImage'] ?? null,
                    'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sO', $article['publishedAt']),
                    'category' => null,///todo
                    'source' => $article['source']['name'],
                    'authors' => $authors ?? null,
                ];
            }
        }
        return $result;
    }
}
