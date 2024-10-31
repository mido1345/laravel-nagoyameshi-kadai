<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Restaurant;

class ReviewController extends Controller
{
    //indexアクション（レビュー一覧ページ）
    public function index(Restaurant $restaurant) {
        if (Auth::user()->subscribed('premium_plan')) {
            $reviews = Review::where('restaurant_id', $restaurant->id)->orderBy('created_at', 'desc')->paginate(5);
        } else {
            $reviews = Review::where('restaurant_id', $restaurant->id)->orderBy('created_at', 'desc')->take(3)->get();
        }

        return view('reviews.index', compact('restaurant', 'reviews'));
    }

    public function create(Restaurant $restaurant) {
        return view('reviews.create', compact('restaurant'));
    }

    public function store(Request $request, Restaurant $restaurant) {
        $request->validate([
            'score' => 'required|numeric|between:1,5',
            'content' => 'required',
        ]);

        $review = new Review();
        $review->score = $request->input('score');
        $review->content = $request->input('content');
        $review->restaurant_id = $restaurant->id;
        $review->user_id = $request->user()->id;
        $review->save();

        return redirect()->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを投稿しました。');
    }

    public function edit(Restaurant $restaurant, Review $review) {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        }

        return view('reviews.edit', compact('restaurant', 'review'));
    }

    public function update(Request $request, Restaurant $restaurant, Review $review) {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        }

        $request->validate([
            'score' => 'required|numeric|between:1,5',
            'content' => 'required',
        ]);

        $review->score = $request->input('score');
        $review->content = $request->input('content');
        $review->save();

        return redirect()->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを編集しました。');
    }

    public function destroy(Restaurant $restaurant, Review $review) {
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
        }

        $review->delete();

        return redirect()->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを削除しました。');
    }
}