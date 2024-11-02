<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    // 予約一覧ページ
    public function test_guest_cannot_access_reservation_index()
    {
        

        $response = $this->get(route('reservations.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_can_access_reservation_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('reservations.index'));
        $response->assertRedirect(route('subscription.create'));
    }

    public function test_paid_user_can_access_reservation_index()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $this->actingAs($user);

        $response = $this->get(route('reservations.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_reservation_index()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('reservations.index'));
        $response->assertRedirect(route('admin.home'));
    }

    // 予約ページ
    public function test_guest_cannot_access_reservation_create()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.reservations.create', ['restaurant' => $restaurant->id]));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_can_access_reservation_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.reservations.create', ['restaurant' => $restaurant->id]));
        $response->assertRedirect(route('subscription.create'));
    }

    public function test_paid_user_can_access_reservation_create()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.reservations.create', ['restaurant' => $restaurant->id]));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_reservation_create()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $this->actingAs($admin, 'admin');

        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.reservations.create', ['restaurant' => $restaurant->id]));
        $response->assertRedirect(route('admin.home'));
    }

    // 予約機能
    public function test_guest_cannot_store_reservation()
    {
        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => now()->format('Y-m-d'),
            'reservation_time' => now()->format('H:i'),
            'number_of_people' => 2,
        ];

        $response = $this->post(route('restaurants.reservations.store', ['restaurant' => $restaurant->id]), $reservation);
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_store_reservation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => now()->format('Y-m-d'),
            'reservation_time' => now()->format('H:i'),
            'number_of_people' => 2,
        ];

        $response = $this->post(route('restaurants.reservations.store', ['restaurant' => $restaurant->id]), $reservation);
        $response->assertRedirect(route('subscription.create'));
    }

    public function test_paid_user_can_store_reservation()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => now()->format('Y-m-d'),
            'reservation_time' => now()->format('H:i'),
            'number_of_people' => 2,
        ];

        $response = $this->post(route('restaurants.reservations.store', ['restaurant' => $restaurant->id]), $reservation);
        $response->assertRedirect(route('reservations.index'));
    }

    public function test_admin_cannot_store_reservation()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $this->actingAs($admin, 'admin');

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => now()->format('Y-m-d'),
            'reservation_time' => now()->format('H:i'),
            'number_of_people' => 2,
        ];

        $response = $this->post(route('restaurants.reservations.store', ['restaurant' => $restaurant->id]), $reservation);
        $response->assertRedirect(route('admin.home'));
    }


    // 予約キャンセル機能
    public function test_guest_cannot_cancel_reservation()
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->delete(route('reservations.destroy', ['restaurant' => $restaurant->id, 'reservation' => $reservation->id]));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_cancel_reservation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->delete(route('reservations.destroy', ['restaurant' => $restaurant->id, 'reservation' => $reservation->id]));
        $response->assertRedirect(route('subscription.create'));
    }

    public function test_paid_user_cannot_cancel_other_reservation()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $otherUser->id
        ]);

        $response = $this->delete(route('reservations.destroy', ['restaurant' => $restaurant->id, 'reservation' => $reservation->id]));
        $response->assertRedirect(route('reservations.index'));
    }

    public function test_paid_user_can_cancel_own_reservation()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->delete(route('reservations.destroy', ['restaurant' => $restaurant->id, 'reservation' => $reservation->id]));
        $response->assertRedirect(route('reservations.index'));
    }

    public function test_admin_cannot_cancel_reservation()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $this->actingAs($admin, 'admin');

        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->delete(route('reservations.destroy', ['restaurant' => $restaurant->id, 'reservation' => $reservation->id]));
        $response->assertRedirect(route('admin.home'));
    }

}