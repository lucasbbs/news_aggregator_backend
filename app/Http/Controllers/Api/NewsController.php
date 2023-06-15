<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GuardianApiService;
use App\Services\NewsApiService;
use App\Services\NewYorkTimesApiService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $nyTimes;

    protected $newsApi;

    protected $guardian;

    /**
     * NewsController constructor.
     */

    public function __construct(NewYorkTimesApiService $nyTimes, GuardianApiService $guardian, NewsApiService $newsApi)
    {
        $this->newsApi = $newsApi;
        $this->nyTimes = $nyTimes;
        $this->guardian = $guardian;
    }
    public function index(Request $request)
    {
        $timesNews = $this->nyTimes->getArticles($request->keyword, $request->begin_date, $request->end_date, $request->category, $request->source, $request->page, $request->sort, $request->search);
        $guardianNews = $this->guardian->getArticles($request->keyword, $request->begin_date, $request->end_date, $request->category, $request->page, $request->sort, $request->search);
        $newsApiNews = $this->newsApi->getArticles($request->keyword, $request->begin_date, $request->end_date, $request->page, $request->search);
        return response()->json([
            'NYT' => $timesNews,
            'GUA' => $guardianNews,
            'NEW' => $newsApiNews,
        ]);
    }

    public function getNewYorkTimesNews(Request $request)
    {
        return $this->nyTimes->getArticles($request->keyword, $request->begin_date, $request->end_date, $request->category, $request->source, $request->page, $request->sort, $request->search);
    }

    public function getTheGuardianNews(Request $request)
    {
        return $this->guardian->getArticles($request->keyword, $request->begin_date, $request->end_date, $request->category, $request->page, $request->sort, $request->search);
    }

    public function getNewsApiNews(Request $request)
    {
        return $this->newsApi->getArticles($request->keyword, $request->begin_date, $request->end_date, $request->page, $request->search);
    }
}
