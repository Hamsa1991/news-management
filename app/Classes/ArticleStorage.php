<?php

namespace App\Classes;

use App\Builder\ArticleBuilder;
use App\Exceptions\StorageException;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;

/**
 * Class ArticleStorage
 *
 * Handles the storage of articles by utilizing the ArticleBuilder.
 * It processes a list of articles, validates, and saves them to the database.
 *
 * @package App\Classes
 */
class ArticleStorage implements PipelineStep
{
    /**
     * @var ArticleBuilder The builder instance for creating articles.
     */
    protected $articleBuilder;

    /**
     * ArticleStorage constructor.
     *
     * @param ArticleBuilder $articleBuilder An instance of ArticleBuilder.
     */
    public function __construct()
    {
        $this->articleBuilder = new ArticleBuilder();
    }

    /**
     * Execute the storage process for an array of articles.
     *
     * @param array $articles An array of articles to be stored.
     * @throws StorageException If there is an issue while saving an article.
     */
    public function execute(array $articles): void
    {
        foreach ($articles as $article) {
            try {
                $this->storeArticle($article);
            } catch (\Exception $e) {
                throw new StorageException("Unable to save article data: " . $e->getMessage());
            }
        }
    }

    /**
     * Store a single article by setting its attributes and saving it.
     *
     * @param array $article An associative array representing an article's data.
     */
    protected function storeArticle(array $article): void
    {
        $this->articleBuilder->reset();
        $this->articleBuilder->setTitle($article['title'])
            ->setDescription($article['description'])
            ->setContent($article['content'])
            ->setUrl($article['url'])
            ->setImageUrl($article['image_url'])
            ->setPublishedDate($article['published_at'])
            ->save();

        $sourceId = $this->handleSource($article['source'] ?? null);
        if ($sourceId) {
            $this->articleBuilder->setSource($sourceId);
        }

        $categoryId = $this->handleCategory($article['category'] ?? null);
        if ($categoryId) {
            $this->articleBuilder->setCategory($categoryId);
        }

        if (isset($article['authors'])) {
            $this->attachAuthors($article['authors']);
        }

        $this->articleBuilder->save();
    }

    /**
     * Handle the source of an article, creating it if it does not exist.
     *
     * @param string|null $sourceName The name of the source.
     * @return int|null The ID of the source or null if no source was provided.
     */
    protected function handleSource(?string $sourceName): ?int
    {
        if ($sourceName) {
            $source = Source::firstOrCreate(['name' => trim($sourceName)]);
            return $source->id;
        }
        return null;
    }

    /**
     * Handle the category of an article, creating it if it does not exist.
     *
     * @param string|null $categoryName The name of the category.
     * @return int|null The ID of the category or null if no category was provided.
     */
    protected function handleCategory(?string $categoryName): ?int
    {
        if ($categoryName) {
            $category = Category::firstOrCreate(['name' => trim($categoryName)]);
            return $category->id;
        }
        return null;
    }

    /**
     * Attach authors to the article.
     *
     * @param array $authors An array of author names.
     */
    protected function attachAuthors(array $authors): void
    {
        foreach ($authors as $authorName) {
            $authorName = trim($authorName);
            if ($authorName) {
                $author = Author::firstOrCreate(['name' => $authorName]);
                $this->articleBuilder->attachAuthor($author->id);
            }
        }
    }
}
