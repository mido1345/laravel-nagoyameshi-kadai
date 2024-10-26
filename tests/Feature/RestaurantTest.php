<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

      //未ログインユーザーは会員側の店舗一覧ページにアクセスできる
    public function test_guest_can_access_user_restaurants_index()
    {
        $response = $this->get(route('restaurants.index'));

        $response->assertStatus(200);
    }

      // ログイン済みの一般ユーザーは会員側の店舗一覧ページにアクセスできる
    public function test_user_can_access_restaurants_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.index'));

        $response->assertStatus(200);
    }

      // ログイン済みの管理者は会員側の店舗一覧ページにアクセスできない
    public function test_admin_cannot_user_restaurants_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.index'));

        $response->assertRedirect(route('admin.home'));
    }

     //未ログインのユーザーは会員側の店舗詳細ページにアクセスできる
    public function test_guest_can_access_user_show_page():void
    {
        $restaurants = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.show',$restaurants));

        $response->assertStatus(200);
    }

    //ログイン済みの一般ユーザーは会員側の店舗詳細ページにアクセスできる
    public function test_regular_can_access_user_show_page():void
    {
        $user = USer::factory()->create();

        $restaurants = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.show',$restaurants));
    
        $response->assertStatus(200);
    }

    //ログイン済みの管理者は会員側の店舗詳細ページにアクセスできない
    public function test_admin_cannot_access_user_show_page():void
    {
        $admin = new Admin();
        $admin->email = 'admin2@gmail.com';
        $admin->password = Hash::make('password');
        $admin->save();

        $restaurants = Restaurant::factory()->create();

        $response = $this->actingAs($admin,'admin')->get(route('restaurants.show',$restaurants));
         $response->assertRedirect(route('admin.home'));
    }
    
}