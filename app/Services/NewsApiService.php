<?php

namespace App\Services;

use App\Models\UserTags;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * Class NewsApiService.
 */
class NewsApiService
{
    protected $api_key;
    protected $api_url;
    protected $client;

    /**
     * NewsApiService constructor.
     */

    public function __construct($api_key, $api_url)
    {
        $this->api_key = $api_key;
        $this->api_url = $api_url;
        $this->client = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Api-Key' => $this->api_key,
        ])->baseUrl($this->api_url);
    }

    public function getArticles($keyword, $beginDate, $endDate, $page, $search)
    {
        $tags = collect();
        if (auth()->check() &&  ($search === 'false' || empty($search))) {
            $tags = UserTags::where('user_id', auth()->user()->id)->get();
        }

        $q = [];
        if (!empty($keyword)) {
            $q[] = $keyword;
        }

        if ($tags->count() > 0) {
            foreach ($tags as $tag) {
                $q[] = $tag->tag_name;
            }
        }

        $query = http_build_query([
            'q' => implode(' AND ', $q),
            'pageSize' => 10,
            'sortBy' => 'publishedAt',
            'from' => Carbon::now()->subDays(30),
            'to' => Carbon::now(),
            'page' => $page,
            'language' => 'en',
        ]);

        $response = $this->client->get("everything?$query");

        if ($response['status'] == 'ok') {
            $mappedResponse = collect($response['articles'])->map(function ($article) {
                return [
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'link' => $article['url'],
                    'date' => $article['publishedAt'],
                    'source' => $article['source']['name'],
                    'author' => $article['author'],
                    'image' => $article['urlToImage'],
                ];
            });
            return response()->json(['message' => 'success', 'status' => 'ok', 'data' => $mappedResponse]);
        } else {
            return response()->json(['message' => 'Something went wrong', 'status' => 'error', 'error' => $response['message']]);
        }
    }
}