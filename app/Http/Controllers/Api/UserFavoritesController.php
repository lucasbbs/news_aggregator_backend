<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserFavorites;
use App\Models\FavoriteSources;

class UserFavoritesController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'source_id' => 'required|exists:sources,id',
        ]);

        UserFavorites::join('source_categories', 'user_favorites.category_id', '=', 'source_categories.id')
            ->where('source_categories.source_id', $request->source_id)
            ->where('user_favorites.user_id', auth()->id())
            ->delete();

            
        if (isset($request->category_id)) {
            UserFavorites::create([
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
            ]);
        }
        
        return response()->json([
            'message' => 'Settings saved successfully.',
        ]);
    }
}