<?php

namespace App\Classes;

use App\Models\Article;
use Illuminate\Support\Facades\Validator;

/**
 * Class ArticleValidator
 *
 * This class is responsible for validating articles and filtering out duplicates.
 * It implements the PipelineStep interface, allowing it to be used in a pipeline for processing articles.
 *
 * @package App\Classes
 */
class ArticleValidator implements PipelineStep
{

    /**
     * Validates articles and filters out duplicates.
     *
     * @param array $articles An array of articles to validate.
     * @return array An array of validated articles with duplicates removed.
     */
    public function execute($articles)
    {
        $validatedArticles = [];

        foreach ($articles as $article) {
            $validatedArticle = $this->validate($article);
            if (!$this->isDuplicate($validatedArticle)) {
                $validatedArticles[] = $article;
            }
        }

        return $validatedArticles;
    }

    /**
     * Checks if the article is a duplicate based on its title and published date.
     *
     * @param array $article The article to check for duplicates.
     * @return bool True if the article is a duplicate, false otherwise.
     */
    private function isDuplicate($article)
    {
        if ($article) {
            return Article::where('title', $article['title'])
                ->whereDate('published_at', $article['published_at'])
                ->exists();
        }
        return true;
    }

    /**
     * Validates and sanitizes article data.
     *
     * @param array $data The article data to validate.
     * @return array|null The validated article data or null if validation fails.
     */
    private function validate($data)
    {
        $data['title'] = strip_tags($data['title']);
        $data['description'] = strip_tags($data['description']);
        $data['content'] = strip_tags($data['content']);
        $data['url'] = filter_var($data['url'], FILTER_SANITIZE_URL);

        if (!$data['title'] || !$data['url']) { // Don't store article if one of these values is null
            return null;
        }

        $data['image_url'] = isset($data['image_url']) ? filter_var($data['image_url'], FILTER_SANITIZE_URL) : null;

        if (!filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
            $data['image_url'] = null; // Set to null if it’s not a valid URL
        }

        $data['category'] = isset($data['category']) ? strip_tags($data['category']) : null;
        $data['source'] = isset($data['source']) ? strip_tags($data['source']) : null;

        if (isset($data['authors'])) {
            foreach ($data['authors'] as &$author) {
                $author = strip_tags($author);
            }
        }

        return $data;
    }
}
