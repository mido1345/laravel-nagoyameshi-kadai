<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //indexアクション（お気に入り一覧ページ）
    public function index() {
        $favorite_restaurants = Auth::user()->favorite_restaurants()->orderBy('restaurant_user.created_at', 'desc')->paginate(15);
        return view('favorites.index', compact('favorite_restaurants'));
    }

    //storeアクション（お気に入り追加機能）
    public function store($restaurant_id) {
        Auth::user()->favorite_restaurants()->attach($restaurant_id);

        return back()->with('flash_message', 'お気に入りに追加しました。');
    }

    //destroyアクション（お気に入り解除機能）
    public function destroy($restaurant_id) {
        Auth::user()->favorite_restaurants()->detach($restaurant_id);

        return back()->with('flash_message', 'お気に入りを解除しました。');
    }
}
