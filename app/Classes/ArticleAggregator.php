<?php


namespace App\Classes;


use App\Classes\FetchArticles\NewsApiContext;
use App\Classes\TransformArticles\ArticleTransformerContext;
use App\Exceptions\ArticlesNotFoundException;
use App\Exceptions\fetchArticlesException;
use App\Exceptions\TransformationException;

class ArticleAggregator implements PipelineStep
{
    protected $apiClients;
    protected $transformers;
    protected $newsApiContext;
    protected $articleTransformerContext;

    public function __construct(array $apiClients, $transformers)
    {
        $this->apiClients = $apiClients;
        $this->transformers = $transformers;
        $this->newsApiContext = new NewsApiContext();
        $this->articleTransformerContext = new ArticleTransformerContext();
    }

    /**
     * Aggregate all articles from all configured news API clients, handling pagination.
     *
     * @return array
     */
    public function execute(array $allArticles)
    {

        for ($index = 0; $index < count($this->apiClients); $index++) {

            $this->newsApiContext->setNewsApiClient($this->apiClients[$index]);
            $this->articleTransformerContext->setArticleTransformer($this->transformers[$index]);

            try {
                //fetch articles
                $articles = $this->newsApiContext->fetchArticles($allArticles);
            } catch (\Exception $exception) {
                throw new fetchArticlesException("Error fetching news: " . $exception->getMessage() . ' file ' . $exception->getFile() . ' line ' . $exception->getLine());
            }
            try {
                //transform articles to unify formats for all articles before merging
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
