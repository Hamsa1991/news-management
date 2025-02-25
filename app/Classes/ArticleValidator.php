<?php


namespace App\Classes;


use App\Models\Article;

class ArticleValidator implements PipelineStep
{
    protected $existingTitles;

    public function __construct()
    {
        // Fetch existing titles from the database once at instantiation
        $this->existingTitles = Article::pluck('title', 'published_at')->toArray();
    }

    /**
     * Validates articles and filters out duplicates.
     *
     * @param array $articles
     * @return array
     */
    public function execute($articles)
    {
        $validatedArticles = [];

        foreach ($articles as $article) {

            $validatedArticle = $this->validate($article);
            if (!$this->isDuplicate($validatedArticle)) {
                $validatedArticles[] = $article;
            }
        }

        return $validatedArticles;
    }

    /**
     * Checks if the article is a duplicate.
     *
     * @param array $article
     * @return bool
     */
    private function isDuplicate(array $article)
    {
        if($article) {
            $key = $article['title'] . '|' . $article['published_at']; // Create a unique key for comparison
            return array_key_exists($key, $this->existingTitles);
            
//            return Article::where('title', $article['title'])
//                ->whereDate('published_at', $article['published_at'])
//                ->exists();
        }
        return true;
    }

    private function validate($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'url' => 'required|url',
            'image_url' => 'nullable|url',
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'authors' => 'nullable|string|max:255',
            'published_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return null; // Return null if validation fails
        }

        // Sanitize the validated data
        $data = $validator->validated();

        $data['title'] = strip_tags($data['title']);
        $data['description'] = strip_tags($data['description']);
        $data['content'] = strip_tags($data['content']);
        $data['url'] = filter_var($data['url'], FILTER_SANITIZE_URL);
        $data['image_url'] = isset($data['image_url']) ? filter_var($data['image_url'], FILTER_SANITIZE_URL) : null;
        $data['category'] = isset($data['category']) ? strip_tags($data['category']) : null;
        $data['source'] = isset($data['source']) ? strip_tags($data['source']) : null;
        $data['authors'] = isset($data['authors']) ? strip_tags($data['authors']) : null;

        return $data;
    }
}
