<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteSources;
use App\Models\SourceCategories;
use App\Models\UserFavorites;
use Illuminate\Http\Request;

class SourceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = SourceCategories::query();

        if($request->has('source_id')) {
            $query->where('source_id', $request->source_id);
        }

        return response()->json($query->get());
    }

    public function getUserFavoritesSources(Request $request)
    {

        $userFavorites = UserFavorites::where('user_id', \Auth::user()->id)->get();

        $ids = array_column($userFavorites->toArray(), 'category_id');

        $userFavoritesSources = SourceCategories::whereIn('id', $ids)->get();
        return response()->json($userFavoritesSources);
    }
}
