<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = "articles";

    protected $fillable = ['title', 'image_url', 'category_id', 'source_id', 'description', 'published_at', 'url', 'content'];

    /**
     * Get the category.
     */
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the source.
     */
    public function Source()
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * Get the authors.
     */
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'article_authors');
    }
}
