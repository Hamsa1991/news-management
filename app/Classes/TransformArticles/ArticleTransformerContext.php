<?php


namespace App\Classes\TransformArticles;


class ArticleTransformerContext
{
    private $articleTransformer;

    public function setArticleTransformer($articleTransformer)
    {
        $this->articleTransformer = $articleTransformer;
    }

    public function transform($articles)
    {
        return $this->articleTransformer->transform($articles);
    }
}
