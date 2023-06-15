<?php

namespace App\Services;

use App\Models\Sources;
use App\Models\UserFavorites;
use App\Models\UserSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use PHPUnit\TextUI\Configuration\Source;

// use Illuminate\Support\Facades\Http;

/**
 * Class NewYorkTimesApiService.
 * @see https://developer.nytimes.com/docs/articlesearch-product/1/overview
 * 
 * @method array getArticles(string $keyword, string $begin_date, string $end_date, string $category, string $source)
 * 
 * 
 */
class NewYorkTimesApiService
{
    protected $api_key;
    protected $api_url;
    protected $client;

    /**
     * NewYorkTimesApiService constructor.
     * @param $api_key
     * @param $api_url
     * @return void
     */

    public function __construct($api_key, $api_url)
    {
        $this->api_key = $api_key;
        $this->api_url = $api_url;
        $this->client = Http::withHeaders([
            'Accept' => 'application/json',
        ])->baseUrl($this->api_url);
    }

    public function getArticles($keyword, $begin_date, $end_date, $category, $source, $page, $sort, $search)
    {
        $sources = Sources::where('name', config('global.new_york_times'))->first();


        $userFavorites = [];
        $userSettings = null;

        if (auth()->check() && ($search === 'false' || empty($search))) {
            $userFavorites = UserFavorites::join('source_categories', 'user_favorites.category_id', '=', 'source_categories.id')
                ->where('source_categories.source_id', $sources->id)
                ->where('user_favorites.user_id', auth()->id())
                ->get();

            $userSettings = UserSettings::where('user_id', auth()->id())->where('source_id', $sources->id)->first();
        }

        $query = [
            'q' => $keyword,
            'fq' => []
        ];

        if (count($userFavorites) > 0) {
            $query['fq'][] = 'news_desk:("' . implode('", "', $userFavorites->pluck('slug')->toArray()) . '")';
        }


        if ($search === 'true') {
            $query['fq'][] = 'news_desk:("' . $category . '")';
        }

        
        // Add source filter
        if (!empty($userSettings->source)) {
            $query['fq'][] = 'source:("' . $userSettings->source . '")';
        }
            
        $response = $this->client->get(
            $this->api_url,
            [
                'api-key' => $this->api_key,
                'begin_date' => $begin_date,
                'end_date' => $end_date,
                'page' => $page,
                'sort' => isset($userSettings) ? $userSettings->sort : null,
                'fq' => implode('AND', $query['fq'])
            ]

        );

        // return response()->json($query);

        if (isset($response['status']) && $response['status'] == 'OK') {
            $mappedResponse = array_map(function ($article) {
                $imageUrl = null;
                $source = null;
                $section_name = null;
                if (isset($article['multimedia'][0]['url'])) {
                    $imageUrl = "https://www.nytimes.com/" . $article['multimedia'][0]['url'];
                }
                if (isset($article['source'])) {
                    $source = $article['source'];
                }
                if (isset($article['section_name'])) {
                    $section_name = $article['section_name'];
                }
                return [
                    'title' => $article['headline']['main'],
                    'description' => $article['abstract'],
                    'link' => $article['web_url'],
                    'date' => $article['pub_date'],
                    'source' => $source,
                    'category' => $section_name,
                    'image' => $imageUrl,
                ];
            }, $response->json()['response']['docs']);

            return response()->json(['status' => 'ok', 'data' => $mappedResponse]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'error' => $response->json()]);
        }
    }
}