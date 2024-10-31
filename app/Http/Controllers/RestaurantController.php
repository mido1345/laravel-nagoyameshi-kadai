<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Admin;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
       
        $keyword = $request->input('keyword');
        $category_id = $request->input('category_id');
        $price = $request->input('price');

        $sorts = [
        '掲載日が新しい順' => 'created_at desc',
        '価格が安い順' => 'lowest_price asc',
        '評価が高い順' => 'rating desc',
        ];

        $sorted = "created_at desc";
        $sort_query = [];

    if ($request->has('select_sort')) {
        $slices = explode(' ', $request->input('select_sort'));
        $sort_query[$slices[0]] = $slices[1];
        $sorted = $request->input('select_sort');
    }

    if ($keyword) {
        $restaurants = Restaurant::where('name', 'like', "%{$keyword}%")
            ->orWhere('address', 'like', "%{$keyword}%")
            ->orWhereHas('categories', function ($query) use ($keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->sortable($sort_query)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    } elseif ($category_id) {
        $restaurants = Restaurant::whereHas('categories', function ($query) use ($category_id) {
            $query->where('categories.id', $category_id);
        })->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
    } elseif ($price) {
        $restaurants = Restaurant::where('lowest_price', '<=', $price)->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
    } else {
        $restaurants = Restaurant::sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
    }

    $categories = Category::all();

    $total = $restaurants->total();


    return view('restaurants.index', compact('keyword', 'category_id', 'price', 'sorts', 'sorted', 'restaurants', 'categories', 'total'));
    }

    public function show(Restaurant $restaurant)
    {
        return view('restaurants.show', compact('restaurant'));
    }
}