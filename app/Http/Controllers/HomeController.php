<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(){
        $categories = Category::all();

        $new_restaurants = Restaurant::orderBy('created_at', 'desc')->take(6)->get();
       
        $highly_rated_restaurants = Restaurant::take(6)->get();

        return view('home', compact('highly_rated_restaurants', 'categories', 'new_restaurants'));
    }
}
