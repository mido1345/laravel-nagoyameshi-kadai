<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;

class RestaurantTest extends TestCase
{
     use RefreshDatabase;

     /**
     * A basic feature test example.
     */

     //indexアクション（店舗一覧ページ）
    public function test_guest_cannot_access_restaurant_index()
    {
        $response = $this->get('/admin/restaurants');
        $response->assertRedirect('/admin/login');
    }

    public function test_logged_in_user_cannot_access_restaurant_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin/restaurants');
        $response->assertRedirect('/admin/login');
    }

    public function test_logged_in_admin_can_access_restaurant_index()
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $response = $this->actingAs($admin, 'admin')->get('/admin/restaurants');
        $response->assertStatus(200);
    }


    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // showアクション（店舗詳細ページ）
    public function test_guest_cannot_access_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get('/admin/restaurants/{$restaurant->id}');
        $response->assertRedirect('/admin/login');
    }

    public function test_user_cannot_access_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin/restaurants/{$restaurant->id}');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_restaurant_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route("admin.restaurants.show", $restaurant));
        $response->assertStatus(200);
    }

    //createアクション（店舗登録ページ）
    public function test_guest_cannot_access_restaurant_create()
    {
        $response = $this->get('/admin/restaurants/create');
        $response->assertRedirect('/admin/login');
    }

    public function test_user_cannot_access_restaurant_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin/restaurants/create');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_restaurant_create()
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $response = $this->actingAs($admin, 'admin')->get('/admin/restaurants/create');
        $response->assertStatus(200);
    }

    //storeアクション（店舗登録機能）
    public function test_guest_cannot_store_restaurant()
    {
        $response = $this->get('/admin/restaurants/store');
        $response->assertRedirect('/admin/login');
    }

    public function test_user_cannot_store_restaurant()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin/restaurants/store');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_store_restaurant()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password');
        $admin->save();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50,
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store', $restaurant_data));
        $this->assertDatabaseHas('restaurants', $restaurant_data);
        $response->assertRedirect(route('admin.restaurants.index'));

    }

    //editアクション（店舗編集ページ）

    public function test_guest_cannot_access_restaurant_edit()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get('/admin/restaurants/edit' . $restaurant->id);
        $response->assertRedirect('/admin/login');
    }

    public function test_user_cannot_access_restaurant_edit()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route("admin.restaurants.edit", $restaurant));
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_restaurant_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route("admin.restaurants.edit", $restaurant));
        $response->assertStatus(200);
    }

    //updateアクション（店舗更新機能）
    public function test_guest_cannot_update_restaurant()
    {
        $restaurant_old = Restaurant::factory()->create();
        $restaurant_new = [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '11:00:00',
            'closing_time' => '21:00:00',
            'seating_capacity' => 60,
        ]; 
        $response = $this->patch(route("admin.restaurants.update", $restaurant_old), $restaurant_new);
        $this->assertDatabaseMissing('restaurants',$restaurant_new);
        $response->assertRedirect('/admin/login');
    }

    public function test_user_cannot_update_restaurant()
    {
        $user = User::factory()->create();
        $restaurant_old = Restaurant::factory()->create();
        $restaurant_new = [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '11:00:00',
            'closing_time' => '21:00:00',
            'seating_capacity' => 60,
        ]; 

        $response = $this->actingAs($user)->patch(route("admin.restaurants.update", $restaurant_old), $restaurant_new);
        $this->assertDatabaseMissing('restaurants',$restaurant_new);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_update_restaurant()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password');
        $admin->save();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50,
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store', $restaurant_data));
        $this->assertDatabaseHas('restaurants', $restaurant_data);
        $response->assertRedirect(route('admin.restaurants.index'));

    }

    //destroyアクション（店舗削除機能）
    public function test_guest_cannot_destroy_restaurant()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->delete(route("admin.restaurants.destroy", $restaurant));
        $this->assertDatabaseHas('restaurants', ['id' => $restaurant->id]);
        $response->assertRedirect('/admin/login');
    }

    public function test_user_cannot_destroy_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user)->delete(route("admin.restaurants.destroy", $restaurant));
        $this->assertDatabaseHas('restaurants', ['id' => $restaurant->id]);
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_destroy_restaurant()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password');
        $admin->save();

        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->delete(route("admin.restaurants.destroy", $restaurant));
        $this->assertDatabaseMissing('restaurants', ['id' => $restaurant->id]);
        $response->assertRedirect(route('admin.restaurants.index'));

    }


}
