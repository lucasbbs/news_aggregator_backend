<?php

namespace App\Services;

use App\Models\Sources;
use App\Models\UserFavorites;
use App\Models\UserSettings;
use Illuminate\Support\Facades\Http;


/**
 * Class GuardianApiService.
 */
class GuardianApiService
{
    protected $api_key;
    protected $api_url;
    protected $client;

    /**
     * GuardianApiService constructor.
     */

    public function __construct($api_key, $api_url)
    {
        $this->api_key = $api_key;
        $this->api_url = $api_url;
        $this->client = Http::withHeaders([
            'Accept' => 'application/json',
            'api-key' => $this->api_key,
        ])->baseUrl($this->api_url);
    }

    public function getArticles($keyword, $begin_date, $end_date, $category,$page, $sort, $search)
    {
        $sources = Sources::where('name', config('global.guardian'))->first();

        $userFavorites = [];
        $userSettings = null;

        if (auth()->check() && ($search === 'false' || empty($search))) {
            $userFavorites = UserFavorites::join('source_categories', 'user_favorites.category_id', '=', 'source_categories.id')
                ->where('source_categories.source_id', $sources->id)
                ->where('user_favorites.user_id', auth()->id())
                ->get();

            $userSettings = UserSettings::where('user_id', auth()->id())->where('source_id', $sources->id)->first();
        }

        if (count($userFavorites) > 0 &&  ($search === 'false' || empty($search))) {
            $category = implode(',', $userFavorites->pluck('slug')->toArray());
        }

        $query = http_build_query([
            'q' => $keyword,
            'from-date' => $begin_date,
            'to-date' => $end_date,
            'section' => $category,
            'page' => $page,
            'order-by' => $userSettings ? $userSettings->sort : $sort,
        ]);
        
        $response = $this->client->get("$this->api_url/search?$query");
        
        $news = $response->json();
        
        if (isset($news['response']['status']) && $news['response']['status'] === 'ok') {
            $mappedResponse = array_map(function ($article) {
                return [
                    'title' => $article['webTitle'],
                    'description' => $article['webTitle'],
                    'link' => $article['webUrl'],
                    'date' => $article['webPublicationDate'],
                    'category' => $article['sectionName'],
                ];
            }, $news['response']['results']);

            return response()->json(['status' => 'ok', 'data' => $mappedResponse]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'error' => $response->json()]);
        }
    }
}