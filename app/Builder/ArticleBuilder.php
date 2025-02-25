<?php

namespace App\Builder;

use App\Models\Article;

/**
 * Class ArticleBuilder
 *
 * A builder class for creating and configuring Article instances.
 *
 * @package App\Builder
 */
class ArticleBuilder
{
    /**
     * @var Article $article The article instance being built.
     */
    protected $article;

    /**
     * ArticleBuilder constructor.
     * Initializes a new Article instance.
     */
    public function __construct()
    {
        $this->article = new Article();
    }

    /**
     * Set the title of the article.
     *
     * @param string $title Title of the article.
     * @return $this
     */
    public function setTitle($title)
    {
        $this->article->title = $title;
        return $this;
    }

    /**
     * Set the description of the article.
     *
     * @param string $description Description of the article.
     * @return $this
     */
    public function setDescription($description)
    {
        $this->article->description = $description;
        return $this;
    }

    /**
     * Set the content of the article.
     *
     * @param string $content Content of the article.
     * @return $this
     */
    public function setContent($content)
    {
        $this->article->content = $content;
        return $this;
    }

    /**
     * Set the URL of the article.
     *
     * @param string $url URL of the article.
     * @return $this
     */
    public function setUrl($url)
    {
        $this->article->url = $url;
        return $this;
    }

    /**
     * Set the image URL for the article.
     *
     * @param string $url Image URL of the article.
     * @return $this
     */
    public function setImageUrl($url)
    {
        $this->article->image_url = $url;
        return $this;
    }

    /**
     * Set the published date of the article.
     *
     * @param \DateTime|string $publishedDate Published date of the article.
     * @return $this
     */
    public function setPublishedDate($publishedDate)
    {
        $this->article->published_at = $publishedDate;
        return $this;
    }

    /**
     * Attach an author to the article.
     *
     * @param mixed $author The author to attach.
     * @return $this
     */
    public function attachAuthor($author)
    {
        $this->article->authors()->attach($author);
        return $this;
    }

    /**
     * Set the category of the article.
     *
     * @param int $category_id The category ID to assign.
     * @return $this
     */
    public function setCategory($category_id)
    {
        $this->article->category_id = $category_id;
        return $this;
    }

    /**
     * Set the source of the article.
     *
     * @param int $source_id The source ID to assign.
     * @return $this
     */
    public function setSource($source_id)
    {
        $this->article->source_id = $source_id;
        return $this;
    }

    /**
     * Save the article instance to the database.
     *
     * @return Article The saved Article instance.
     */
    public function save()
    {
        $this->article->save();
        // Return the created article instance
        return $this->article;
    }

    /**
     * Reset the current article instance for a new build.
     *
     * @return void
     */
    public function reset()
    {
        $this->article = new Article();
    }
}
