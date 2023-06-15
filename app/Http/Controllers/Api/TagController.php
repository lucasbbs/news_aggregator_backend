<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserTags;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $query = UserTags::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag_names' => 'required|array',
        ]);

        UserTags::where('user_id', \Auth::user()->id)->delete();

        foreach ($request->tag_names as $tag_name) {
            UserTags::create([
                'user_id' => \Auth::user()->id,
                'tag_name' => $tag_name,
            ]);
        }

        return response()->json([
            'message' => 'Tags saved successfully.',
        ]);
    }

    public function delete($id)
    {
        $tag = UserTags::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found.'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully.'
        ]);
    }
}