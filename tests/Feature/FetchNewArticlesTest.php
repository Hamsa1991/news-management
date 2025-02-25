<?php

namespace Tests\Feature;

use App\Classes\FetchArticles\GuardianNewsApiClient;
use App\Classes\FetchArticles\NewsApiClient;
use App\Classes\FetchArticles\NewsIoClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchNewArticlesTest extends TestCase
{
//    public function testGuardianNewsApiClientFetchArticles()
//    {
//        // Mock the HTTP response
//        Http::fake([
//            config('api.guardian_api_url') => Http::sequence()->push([
//                'response' => [
//                    'status' => 'ok',
//                    'results' => [
//                        [ 'title' => 'Germany elections live: AfD’s Weidel says she received congratulations from Elon Musk after significant far-right gains' ],
//                        [ 'title' => 'Deutsche Bank boss calls for ‘fundamental reforms’ from next government, as German business confidence stagnates – business live' ]
//                    ]
//                ]
//            ])
//        ]);
//
//        $client = new GuardianNewsApiClient();
//        $articles = $client->fetchArticles();
//
//        $this->assertCount(2, $articles);
//        $this->assertEquals('Germany elections live: AfD’s Weidel says she received congratulations from Elon Musk after significant far-right gains', $articles[0]['title']);
//    }

//    public function testNewsApiClientFetchArticles()
//    {
//        // Mock the HTTP response
//        Http::fake([
//            config('api.news_api_url') => Http::sequence()->push(
//                [
//                    'status' => 'ok',
//                    'articles' => [
//                        ['title' => 'El nuevo escándalo de Elon Musk: Una demanda, un hijo oculto y acusaciones de abandono'],
//                        ['title' => 'Elon Musk donne quarante-huit heures aux fonctionnaires américains pour justifier de leur activité et menace d’interrompre leur contrat en l’absence de réponse']
//                    ]
//                ]
//            )
//        ]);
//
//        $client = new NewsApiClient();
//        $articles = $client->fetchArticles();
//
//
//        $this->assertCount(2, $articles);
//        $this->assertEquals('El nuevo escándalo de Elon Musk: Una demanda, un hijo oculto y acusaciones de abandono', $articles[0]['title']);
//    }
//    public function testNewsIoApiClientFetchArticles()
//    {
//        // Mock the HTTP response
//        Http::fake([
//            config('api.news_io_url') => Http::sequence()->push(
//                [
//                    'status' => 'success',
//                    'results' => [
//                        [ 'title' => 'American Airlines flight from New York to New Delhi lands safely in Rome after security concern' ],
////                        [ 'title' => 'Battery mates Casey Mize, Dillon Dingler put a charge in Tigers’ spring victory' ]
//                    ]
//                ]
//            )
//        ]);
//
//        $client = new NewsIoClient();
//        $articles = $client->fetchArticles();
//
//        $this->assertCount(1, $articles);
//        $this->assertEquals('Bubba Wallace check-up leads to huge wreck involving Daniel Suárez, Cole Custer, Ty Gibbs', $articles[0]['title']);
//    }

    public function testGuardianNewsApiClientHandlesErrorResponse()
    {
        // Mock the HTTP error response
        Http::fake([
            config('api.guardian_api_url') => Http::sequence()->push([
                'response' => [
                    'status' => 'error',
                    'message' => 'An error occurred.'
                ]
            ])
        ]);

        $client = new GuardianNewsApiClient();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('An error occurred.');

        $client->fetchArticles();
    }
}
