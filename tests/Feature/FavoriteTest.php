<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Restaurant;
use Tests\TestCase;

class FavoriteTest extends TestCase
{

    use RefreshDatabase;


    //indexアクション（お気に入り一覧ページ）
    //未ログインのユーザーは会員側のお気に入り一覧ページにアクセスできない
    public function test_guest_cannot_access_favorites_index()
    {
        $response = $this->get(route('favorites.index'));

        $response->assertRedirect(route('login'));
    }

    //ログイン済みの無料会員は会員側のお気に入り一覧ページにアクセスできない
    public function test_free_user_cannot_access_favorites_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('favorites.index'));

        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員は会員側のお気に入り一覧ページにアクセスできる
    public function test_premium_user_can_access_favorites_index()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');

        $response = $this->actingAs($user)->get(route('favorites.index'));

        $response->assertStatus(200);
    }

    //ログイン済みの管理者は会員側のお気に入り一覧ページにアクセスできない
    public function test_admin_cannot_access_favorites_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get(route('favorites.index'));

        $response->assertRedirect(route('admin.home'));
    }

    //storeアクション（お気に入り追加機能）
   //未ログインのユーザーはお気に入りに追加できない
   public function test_guest_cannot_access_favorites_store()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', ['restaurant_id' => $restaurant->id]);
        $response->assertRedirect(route('login'));
    }

    //ログイン済みの無料会員はお気に入りに追加できない
    public function test_free_user_cannot_access_favorites_store()
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', ['restaurant_id' => $restaurant->id, 'user_id' => $user->id]);
        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員はお気に入りに追加できる
    public function test_premium_user_can_access_favorites_store()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', ['restaurant_id' => $restaurant->id, 'user_id' => $user->id]);
        $response->assertStatus(302);
    }

    // ログイン済みの管理者はお気に入りに追加できない
    public function test_admin_cannot_access_favorites_store()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('favorites.store', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', ['restaurant_id' => $restaurant->id]);
        $response->assertRedirect(route('admin.home'));
    }

    //destroyアクション（お気に入り解除機能）
    //未ログインのユーザーはお気に入りを解除できない
    public function test_guest_cannot_access_favorites_destroy()
    {
        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $user->favorite_restaurants()->attach($restaurant->id);

        $response = $this->delete(route('favorites.destroy', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', ['restaurant_id' => $restaurant->id, 'user_id' => $user->id]);
        $response->assertRedirect(route('login'));
    }

    // ログイン済みの無料会員はお気に入りを解除できない
    public function test_free_user_cannot_access_favorites_destroy()
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();

        $user->favorite_restaurants()->attach($restaurant->id);

        $response = $this->actingAs($user)->delete(route('favorites.destroy', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', ['restaurant_id' => $restaurant->id, 'user_id' => $user->id]);
        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員はお気に入りを解除できる
    public function test_premium_user_can_access_favorites_destroy()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');

        $restaurant = Restaurant::factory()->create();

        $user->favorite_restaurants()->attach($restaurant->id);

        $response = $this->actingAs($user)->delete(route('favorites.destroy', $restaurant->id));

        $this->assertDatabaseMissing('restaurant_user', ['restaurant_id' => $restaurant->id, 'user_id' => $user->id]);
        $response->assertStatus(302);
    }

    //ログイン済みの管理者はお気に入りを解除できない
    public function test_admin_cannot_access_favorites_destroy()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();

        $user = User::factory()->create();

        $user->favorite_restaurants()->attach($restaurant->id);

        $response = $this->actingAs($admin, 'admin')->delete(route('favorites.destroy', $restaurant->id));

        $this->assertDatabaseHas('restaurant_user', ['restaurant_id' => $restaurant->id, 'user_id' => $user->id]);
        $response->assertRedirect(route('admin.home'));
    }
    }