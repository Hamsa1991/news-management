<?php

namespace App\Classes\TransformArticles;

use App\Exceptions\ArticlesNotFoundException;
use Carbon\Carbon;

/**
 * Class GuardianNewsTransformer
 *
 * This class is responsible for transforming articles fetched from the Guardian API
 * into a standardized format. It implements the ArticleTransformInterface for integration.
 *
 * @package App\Classes\TransformArticles
 */
class GuardianNewsTransformer implements ArticleTransformInterface
{
    /**
     * Transform the given articles into a standardized format.
     *
     * @param array|null $articles An array of articles fetched from the Guardian API.
     * @return array An array of transformed articles.
     */
    public function transform($articles)
    {
        $result = [];
        if ($articles) {
            foreach ($articles as $article) {
                $result[] = [
                    'title' => $article['webTitle'],
                    'description' => null, // No description provided
                    'content' => null, // No content provided
                    'url' => $article['webUrl'],
                    'image_url' => $article['urlToImage'] ?? null, // Use null if image URL is not available
                    'published_at' => Carbon::parse($article['webPublicationDate'])->format("Y-m-d"),
                    'category' => $article['sectionName'],
                    'source' => "The Guardian", // Source identifier
                    'authors' => null, // No authors provided
                ];
            }
        }
        return $result;
    }
}
