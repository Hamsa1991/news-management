<?php
return [
    'guardian_api_url' => env('GUARDIAN_API_URL', 'https://content.guardianapis.com/search?api-key=b3dd7f2f-cbe9-40bc-939e-77b1ce0593be'),
    'news_api_url' => env('NEWS_API_URL', 'https://newsapi.org/v2/everything?q=tesla&sortBy=publishedAt&apiKey=9cd080ab99794b019511f8b63dd672c9'),
    'news_io_url' => env('NEWS_IO_URL', "https://newsdata.io/api/1/latest?apikey=pub_529805766d4765ed4acad045120f3429aa53f&country=US"),
    'newyork_times_url' => env('NEWYORKTIMES_API_URL', "https://api.nytimes.com/svc/search/v2/articlesearch.json?q=election&api-key=aQTkp8gKN0sypd6ho7IGXOkmINV2g4om&document_type=article"),
];
