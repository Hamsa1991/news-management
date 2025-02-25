<?php


namespace App\Classes\TransformArticles;


use Carbon\Carbon;
use function Carbon\Traits\carbonize;

class NewyorkTimesApiTransformer implements ArticleTransformInterface
{


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
                    'image_url' => null,
                    'published_at' => Carbon::parse($article['pub_date']),
                    'category' => $article['section_name'],
                    'source' => $article['source'],
                    'authors' => $authors,
                ];
            }
        }
        return $result;
    }
}
