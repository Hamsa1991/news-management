<?php

namespace App\Classes\TransformArticles;

use Carbon\Carbon;

/**
 * Class NewyorkTimesApiTransformer
 *
 * This class transforms articles retrieved from the New York Times API into a standardized format.
 * It implements the ArticleTransformInterface, ensuring that the transform method is available
 * for converting article data.
 *
 * @package App\Classes\TransformArticles
 */
class NewyorkTimesApiTransformer implements ArticleTransformInterface
{
    /**
     * Transform the given articles into a standardized format.
     *
     * @param array $articles An array of articles fetched from the New York Times API.
     * @return array An array of transformed articles with standardized fields.
     */
    public function transform(array $articles)
    {
        $result = [];
        if ($articles) {
            foreach ($articles as $article) {
                $authors = explode(',', str_replace("By ", "", $article['byline']['original']));
                $result[] = [
                    'title' => $article['abstract'],
                    'description' => $article['lead_paragraph'],
                    'content' => $article['snippet'],
                    'url' => $article['web_url'],
                    'image_url' => null, // Currently set to null, can be updated if needed
                    'published_at' => Carbon::parse($article['pub_date'])->format("Y-m-d"),
                    'category' => $article['section_name'],
                    'source' => $article['source'],
                    'authors' => $authors,
                ];
            }
        }
        return $result;
    }
}
