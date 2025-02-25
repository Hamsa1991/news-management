<?php

namespace App\Classes\TransformArticles;

/**
 * Class ArticleTransformerContext
 *
 * This class acts as a context for transforming articles using a specified transformer implementation.
 * It allows for setting different article transformers and applying them to a collection of articles.
 *
 * @package App\Classes\TransformArticles
 */
class ArticleTransformerContext
{
    private $articleTransformer; // The transformer to be used for article transformation

    /**
     * Set the article transformer.
     *
     * @param mixed $articleTransformer An instance of an article transformer that implements the required transform method.
     */
    public function setArticleTransformer($articleTransformer)
    {
        $this->articleTransformer = $articleTransformer;
    }

    /**
     * Transform a collection of articles using the set transformer.
     *
     * @param array $articles An array of articles to be transformed.
     * @return mixed The transformed articles as returned by the transformer.
     */
    public function transform($articles)
    {
        return $this->articleTransformer->transform($articles);
    }
}
