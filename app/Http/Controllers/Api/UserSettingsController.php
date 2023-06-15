<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
    public function index()
    {
        $userSettings = UserSettings::where('user_id', \Auth::user()->id)->get();
        return response()->json($userSettings);
    }

    public function store(Request $request)
    {
        $userSettings = UserSettings::where('user_id', \Auth::user()->id)
            ->where('source_id', $request->source_id)
            ->first();
        if (!$userSettings) {
            $userSettings = new UserSettings();
            $userSettings->user_id = \Auth::user()->id;
            $userSettings->source_id = $request->source_id;
        }

        $userSettings->sort = $request->sort;
        $userSettings->source = $request->source;
        $userSettings->save();

        return response()->json($userSettings);
    }
}