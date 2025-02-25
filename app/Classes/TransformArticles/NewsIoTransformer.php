<?php


namespace App\Classes\TransformArticles;


use Carbon\Carbon;

class NewsIoTransformer implements ArticleTransformInterface
{

    public function transform(array $articles)
    {
        $result = [];
        if($articles) {
            foreach ($articles as $article) {
                $result[] = [
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'content' => $article['content'],
                    'url' => $article['link'],
                    'image_url' => $article['image_url'] ?? null,
                    'published_at' => Carbon::parse($article['pubDate']),
                    'category' => $article['category'][0],
                    'source' => $article['source_name'],
                    'authors' => $article['creator'],
                ];
            }
        }
        return $result;
    }
}
