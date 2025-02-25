<?php

namespace App\Services;

use App\Http\Requests\ArticleFiltersRequest;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class NewsService
 *
 * This service class handles operations related to fetching news articles,
 * including applying various filters like keyword, category, source, author,
 * and publication date. It also manages user preferences for personalized article retrieval.
 *
 * @package App\Services
 */
class NewsService
{
    /**
     * Get articles based on the provided filters.
     *
     * @param ArticleFiltersRequest $request The request object containing filter parameters.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the filtered articles.
     */
    public function getArticles(ArticleFiltersRequest $request)
    {
        $keyword = $request->keyword;
        $category = $request->category;
        $source = $request->source;
        $author = $request->author; // New author filter
        $startDate = $request->startDate;

        $articles = Article::with(['category', 'source', 'authors'])
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            })
            ->when($category, function ($query) use ($category) {
                return $query->where('category_id', $category);
            })
            ->when($source, function ($query) use ($source) {
                return $query->where('source_id', $source);
            })
            ->when($author, function ($query) use ($author) {
                return $query->whereHas('authors', function ($q) use ($author) {
                    $q->where('authors.id', $author);
                });
            })
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('publishedAt', '>=', $startDate);
            })
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $articles,
        ], 200);
    }

    /**
     * Get articles based on user preferences.
     *
     * @param Request $request The incoming request object.
     * @return \Illuminate\Http\JsonResponse A JSON response with user-specific articles or an error.
     */
    public function getArticlesByPreferences(Request $request)
    {
        // This function requires authentication
        // Get user by request
        /* @var User $user */
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        //assume there is authentication then retrieve the user
        //then retrieve its preferences


    }
}
