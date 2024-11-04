<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reservation;

class HomeController extends Controller
{
    public function index() {
        $total_users = User::count();
         $total_premium_users = DB::table('subscriptions')->where('stripe_status', 'active')->count();
         $total_free_users = $total_users - $total_premium_users;
         $total_restaurants = Restaurant::count();
         $total_reservations = Reservation::count();
         $sales_for_this_month = 300 * $total_premium_users;

        return view('admin.home', compact('total_users', 'total_premium_users', 'total_free_users', 'total_restaurants', 'total_reservations', 'sales_for_this_month'));
    }
}
