<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'status'];

    /**
     * Get the articles for the categpry.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
