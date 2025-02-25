<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';
    protected $fillable = ['name', 'status'];

    /**
     * Get the articles for the author.
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_authors');
    }
}
