<?php

namespace App\Classes\TransformArticles;

/**
 * Interface ArticleTransformInterface
 *
 * This interface defines a contract for transforming articles. Any class that implements
 * this interface must provide the logic for transforming an array of articles.
 *
 * @package App\Classes\TransformArticles
 */
interface ArticleTransformInterface
{
    /**
     * Transform the given array of articles.
     *
     * @param array $articles The articles to be transformed.
     * @return mixed The transformed articles.
     */
    public function transform(array $articles);
}
