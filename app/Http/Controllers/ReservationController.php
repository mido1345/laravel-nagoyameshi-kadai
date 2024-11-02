<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reservation;

class ReservationController extends Controller
{
     //indexアクション（予約一覧ページ）
     public function index() 
     {
         $reservations = Reservation::where('user_id', auth()->id())
             ->orderBy('reserved_datetime', 'desc')
             ->paginate(15);
 
         return view('reservations.index', compact('reservations'));
     }
 
     //createアクション（予約ページ）
     public function create(Restaurant $restaurant) 
     {
         return view('reservations.create', compact('restaurant'));
     }
 
     //storeアクション（予約機能）
     public function store(Request $request, Restaurant $restaurant) 
     {
         $request->validate([
             'reservation_date' => 'required|date_format:Y-m-d',
             'reservation_time' => 'required|date_format:H:i',
             'number_of_people' => 'required|integer|min:1|max:50',
         ]);
 
         Reservation::create([
             'reserved_datetime' => $request->input('reservation_date'). ' ' . $request->input('reservation_time'),
             'number_of_people' => $request->input('number_of_people'),
             'restaurant_id' => $restaurant->id,
             'user_id' => auth()->id(),
         ]);
 
         return redirect()->route('reservations.index')->with('flash_message', '予約が完了しました。');
     }
 
     //destroyアクション（予約キャンセル機能）
     public function destroy(Restaurant $restaurant, Reservation $reservation) 
     {
         if ($reservation->user_id !== auth()->id()) {
             return redirect()->route('reservations.index')->with('error_message', '不正なアクセスです。');
         }
 
         $reservation->delete();
 
         return redirect()->route('reservations.index')->with('flash_message', '予約をキャンセルしました。');
     }
}
