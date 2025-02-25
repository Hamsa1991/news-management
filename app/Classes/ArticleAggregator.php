<?php

namespace App\Classes;

use App\Classes\FetchArticles\NewsApiContext;
use App\Classes\TransformArticles\ArticleTransformerContext;
use App\Exceptions\ArticlesNotFoundException;
use App\Exceptions\fetchArticlesException;
use App\Exceptions\TransformationException;

/**
 * Class ArticleAggregator
 *
 * This class aggregates articles from multiple news API clients and transforms them into a unified format.
 * It manages the context for both fetching and transforming articles, handling any exceptions during the process.
 *
 * @package App\Classes
 */
class ArticleAggregator implements PipelineStep
{
    protected $apiClients;                 // Array of news API client instances
    protected $transformers;               // Array of article transformer instances
    protected $newsApiContext;             // Context for news API client
    protected $articleTransformerContext;  // Context for article transformer

    /**
     * ArticleAggregator constructor.
     *
     * @param array $apiClients An array of configured news API clients.
     * @param array $transformers An array of article transformers.
     */
    public function __construct(array $apiClients, $transformers)
    {
        $this->apiClients = $apiClients;
        $this->transformers = $transformers;
        $this->newsApiContext = new NewsApiContext();
        $this->articleTransformerContext = new ArticleTransformerContext();
    }

    /**
     * Aggregate all articles from configured news API clients, handling pagination.
     *
     * @param array $allArticles Array to hold all aggregated articles.
     * @throws ArticlesNotFoundException If no articles are fetched.
     * @throws fetchArticlesException If there is an error fetching articles from the API.
     * @throws TransformationException If there is an error transforming the articles.
     * @return array The merged array of articles from all sources.
     */
    public function execute(array $allArticles)
    {
        for ($index = 0; $index < count($this->apiClients); $index++) {
            $this->newsApiContext->setNewsApiClient($this->apiClients[$index]);
            $this->articleTransformerContext->setArticleTransformer($this->transformers[$index]);

            try {
                // Fetch articles
                $articles = $this->newsApiContext->fetchArticles($allArticles);
            } catch (\Exception $exception) {
                throw new fetchArticlesException("Error fetching news: " . $exception->getMessage() . ' file ' . $exception->getFile() . ' line ' . $exception->getLine());
            }

            try {
                // Transform articles to unify formats for all articles before merging
                $transformedArticles = $this->articleTransformerContext->transform($articles);
            } catch (\Exception $exception) {
                throw new TransformationException("Error transforming news data: " . $exception->getMessage() . ' file ' . $exception->getFile() . ' line ' . $exception->getLine());
            }

            if (isset($transformedArticles)) {
                $allArticles = array_merge($allArticles, $transformedArticles);
            }
        }

        if (empty($allArticles)) {
            throw new ArticlesNotFoundException("No news data fetched.");
        }

        return $allArticles;
    }
}
