<?php

namespace App\Console\Commands;

use App\Classes\ArticleAggregator;
use App\Classes\ArticlePipeline;
use App\Classes\ArticleStorage;
use App\Classes\ArticleTransformer;
use App\Classes\ArticleValidator;
use App\Classes\FetchArticles\GuardianNewsApiClient;
use App\Classes\FetchArticles\NewsApiClient;
use App\Classes\FetchArticles\NewsIoClient;
use App\Classes\FetchArticles\NewyorkTimesApiClient;
use App\Classes\TransformArticles\GuardianNewsTransformer;
use App\Classes\TransformArticles\NewsApiTransformer;
use App\Classes\TransformArticles\NewsIoTransformer;
use App\Classes\TransformArticles\NewyorkTimesApiTransformer;
use App\Exceptions\ArticlesNotFoundException;
use App\Exceptions\StorageException;
use App\Exceptions\TransformationException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class fetchNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch News Articles from different apis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            // Create an array of api clients and  transformers
            $clients = [
//                new NewsApiClient(),
//                new GuardianNewsApiClient(),
//                new NewsIoClient(),
                new NewyorkTimesApiClient()
            ];
            $transformers = [
//                new NewsApiTransformer(),
//                new GuardianNewsTransformer(),
//                new NewsIoTransformer(),
                new NewyorkTimesApiTransformer()
            ];

            $articleAggregator = new ArticleAggregator($clients, $transformers);
            $articleValidator = new ArticleValidator();
            $articleStorage = new ArticleStorage();


            // Create a pipeline and add stages
            $articlesPipeline = new ArticlePipeline();
            $articlesPipeline->addStep($articleAggregator);
            $articlesPipeline->addStep($articleValidator);
            $articlesPipeline->addStep($articleStorage);

            //article pipeline execute
            $data = $articlesPipeline->process();
            if ($data == null) {
                Log::info("No new Articles fetched");
            } else {
                Log::info('Processed: ' . count($data) . ' articles.');
            }
        } catch (ArticlesNotFoundException $exception) {
            Log::error("No articles retrieved: " . $exception->getMessage());
            throw $exception;
        } catch (fetchArticlesException $exception) {
            Log::error("Error in fetching News api articles: " . $exception->getMessage());
            throw $exception;
        } catch (TransformationException $exception) {
            Log::error("Error in transforming articles: " . $exception->getMessage());
            throw $exception;
        } catch (StorageException $exception) {
            Log::error("Error in store articles: " . $exception->getMessage());
            throw $exception;
        } catch (\Exception $exception) {
            Log::error("Unexpected Error: " . $exception->getMessage());
            throw $exception;
        }
    }
}
