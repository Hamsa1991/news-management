<?php

namespace App\Classes\TransformArticles;

use Carbon\Carbon;

/**
 * Class NewsIoTransformer
 *
 * This class implements the ArticleTransformInterface for transforming articles
 * fetched from the News API into a standardized format. It maps input article data
 * to a specific output structure suitable for further processing or storage.
 *
 * @package App\Classes\TransformArticles
 */
class NewsIoTransformer implements ArticleTransformInterface
{

    /**
     * Transform the given array of articles into a standardized format.
     *
     * @param array $articles An array of articles to transform.
     * @return array An array of transformed articles with standardized structure.
     */
    public function transform(array $articles)
    {
        $result = [];
        if ($articles) {
            foreach ($articles as $article) {
                $result[] = [
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'content' => $article['content'],
                    'url' => $article['link'],
                    'image_url' => $article['image_url'] ?? null,
                    'published_at' => Carbon::parse($article['pubDate'])->format("Y-m-d"),
                    'category' => $article['category'][0],
                    'source' => $article['source_name'],
                    'authors' => $article['creator'],
                ];
            }
        }
        return $result;
    }
}
