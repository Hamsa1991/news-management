<?php

namespace App\Classes\TransformArticles;

use App\Exceptions\ArticlesNotFoundException;
use Carbon\Carbon;

/**
 * Class NewsApiTransformer
 *
 * This class is responsible for transforming articles fetched from the news API
 * into a standardized format. It implements the ArticleTransformInterface to ensure
 * consistency in article data transformation.
 *
 * @package App\Classes\TransformArticles
 */
class NewsApiTransformer implements ArticleTransformInterface
{
    /**
     * Transform the fetched articles into a standardized format.
     *
     * @param array $articles An array of articles fetched from the news API.
     * @return array An array of transformed articles in a standardized format.
     */
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
                    'category' => null,
                    'source' => $article['source']['name'],
                    'authors' => $authors ?? null,
                ];
            }
        }
        return $result;
    }
}
