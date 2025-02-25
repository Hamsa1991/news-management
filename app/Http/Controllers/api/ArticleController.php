<?php

namespace App\Http\Controllers\api;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleFiltersRequest;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class ArticleController
 *
 * This controller handles requests related to articles, providing endpoints for fetching articles
 * based on various filters or user preferences. It utilizes the NewsService for business logic
 * regarding article retrieval.
 *
 * @package App\Http\Controllers\api
 */
class ArticleController extends Controller
{
    protected $newsService;

    /**
     * ArticleController constructor.
     *
     * @param NewsService $newsService An instance of NewsService used to fetch articles.
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Fetch articles based on the provided filters.
     *
     * @param ArticleFiltersRequest $request The request containing the article filter criteria.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the fetched articles or an error message.
     */
    public function getArticles(ArticleFiltersRequest $request)
    {
        try {
            $articles = $this->newsService->getArticles($request);
            return response()->json($articles);
        } catch (\Exception $e) {
            Log::error('Error fetching articles', [
                'file' => $e->getFile(),
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not fetch articles. Please try again later.',
            ], 500);
        }
    }

    /**
     * Fetch articles based on user preferences.
     *
     * @param Request $request The request containing user preference data.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the fetched articles or an error message.
     */
    public function getArticlesByPreferences(Request $request)
    {
        //this method is authorised, since the authentication is not required
        try {
            $articles = $this->newsService->getArticlesByPreferences($request);
            return response()->json($articles);
        } catch (\Exception $e) {
            Log::error('Error fetching articles', [
                'file' => $e->getFile(),
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not fetch articles. Please try again later.',
            ], 500);
        }
    }
}
