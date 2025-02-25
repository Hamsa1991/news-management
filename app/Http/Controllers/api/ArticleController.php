<?php

namespace App\Http\Controllers\api;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleFiltersRequest;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

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


    public function getArticlesByPreferences(Request $request)
    {
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
