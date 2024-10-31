<?php

namespace Tests\Feature;

 use App\Models\Admin;
 use App\Models\User;
 use App\Models\Restaurant;
 use App\Models\Review;
 use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
     use RefreshDatabase;
 
     // 未ログインのユーザーは会員側のレビュー一覧ページにアクセスできない
     public function test_guest_cannot_access_reviews_index()
     {
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->get(route('restaurants.reviews.index', $restaurant));
 
         $response->assertRedirect(route('login'));
     }
 
     // ログイン済みの無料会員は会員側のレビュー一覧ページにアクセスできる
     public function test_free_user_can_access_reviews_index()
     {
         $user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->actingAs($user)->get(route('restaurants.reviews.index', $restaurant));
 
         $response->assertStatus(200);
     }
 
     // ログイン済みの有料会員は会員側のレビュー一覧ページにアクセスできる
     public function test_premium_user_can_access_reviews_index()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
 
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->actingAs($user)->get(route('restaurants.reviews.index', $restaurant));
 
         $response->assertStatus(200);
     }
 
     // ログイン済みの管理者は会員側のレビュー一覧ページにアクセスできない
     public function test_admin_cannot_access_reviews_index()
     {
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();
 
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->actingAs($admin, 'admin')->get(route('restaurants.reviews.index', $restaurant));
 
         $response->assertRedirect(route('admin.home'));
     }
 
     // 未ログインのユーザーは会員側のレビュー投稿ページにアクセスできない
     public function test_guest_cannot_access_reviews_create()
     {
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->get(route('restaurants.reviews.create', $restaurant));
 
         $response->assertRedirect(route('login'));
     }
 
     // ログイン済みの無料会員は会員側のレビュー投稿ページにアクセスできない
     public function test_free_user_cannot_access_reviews_create()
     {
         $user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->actingAs($user)->get(route('restaurants.reviews.create', $restaurant));
 
         $response->assertRedirect(route('subscription.create'));
     }
 
     // ログイン済みの有料会員は会員側のレビュー投稿ページにアクセスできる
     public function test_premium_user_can_access_reviews_create()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
 
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->actingAs($user)->get(route('restaurants.reviews.create', $restaurant));
 
         $response->assertStatus(200);
     }
 
     // ログイン済みの管理者は会員側のレビュー投稿ページにアクセスできない
     public function test_admin_cannot_access_reviews_create()
     {
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();
 
         $restaurant = Restaurant::factory()->create();
 
         $response = $this->actingAs($admin, 'admin')->get(route('restaurants.reviews.create', $restaurant));
 
         $response->assertRedirect(route('admin.home'));
     }
 
     // 未ログインのユーザーはレビューを投稿できない
     public function test_guest_cannot_access_reviews_store()
     {
         $restaurant = Restaurant::factory()->create();
 
         $review_data = [
             'score' => 1,
             'content' => 'テスト'
         ];
 
         $response = $this->post(route('restaurants.reviews.store', $restaurant), $review_data);
 
         $this->assertDatabaseMissing('reviews', $review_data);
         $response->assertRedirect(route('login'));
     }
 
     // ログイン済みの無料会員はレビューを投稿できない
     public function test_free_user_cannot_access_reviews_store()
     {
         $user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $review_data = [
             'score' => 1,
             'content' => 'テスト'
         ];
 
         $response = $this->actingAs($user)->post(route('restaurants.reviews.store', $restaurant), $review_data);
 
         $this->assertDatabaseMissing('reviews', $review_data);
         $response->assertRedirect(route('subscription.create'));
     }
 
     // ログイン済みの有料会員はレビューを投稿できる
     public function test_premium_user_can_access_reviews_store()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
 
         $restaurant = Restaurant::factory()->create();
 
         $review_data = [
             'score' => 1,
             'content' => 'テスト'
         ];
 
         $response = $this->actingAs($user)->post(route('restaurants.reviews.store', $restaurant), $review_data);
 
         $this->assertDatabaseHas('reviews', $review_data);
         $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
     }
 
     // ログイン済みの管理者はレビューを投稿できない
     public function test_admin_cannot_access_reviews_store()
     {
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();
 
         $restaurant = Restaurant::factory()->create();
 
         $review_data = [
             'score' => 1,
             'content' => 'テスト'
         ];
 
         $response = $this->actingAs($admin, 'admin')->post(route('restaurants.reviews.store', $restaurant), $review_data);
 
         $this->assertDatabaseMissing('reviews', $review_data);
         $response->assertRedirect(route('admin.home'));
     }
 
     // 未ログインのユーザーは会員側のレビュー編集ページにアクセスできない
     public function test_guest_cannot_access_reviews_edit()
     {
         $restaurant = Restaurant::factory()->create();
 
         $user = User::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->get(route('restaurants.reviews.edit', [$restaurant, $review]));
 
         $response->assertRedirect(route('login'));
     }
 
     // ログイン済みの無料会員は会員側のレビュー編集ページにアクセスできない
     public function test_free_user_cannot_access_reviews_edit()
     {
         $user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->actingAs($user)->get(route('restaurants.reviews.edit', [$restaurant, $review]));
 
         $response->assertRedirect(route('subscription.create'));
     }
 
     // ログイン済みの有料会員は会員側の他人のレビュー編集ページにアクセスできない
     public function test_premium_user_cannot_access_others_reviews_edit()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
         $other_user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $other_user->id
         ]);
 
         $response = $this->actingAs($user)->get(route('restaurants.reviews.edit', [$restaurant, $review]));
 
         $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
     }
 
     // ログイン済みの有料会員は会員側の自身のレビュー編集ページにアクセスできる
     public function test_premium_user_can_access_own_reviews_edit()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
 
         $restaurant = Restaurant::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->actingAs($user)->get(route('restaurants.reviews.edit', [$restaurant, $review]));
 
         $response->assertStatus(200);
     }
 
     // ログイン済みの管理者は会員側のレビュー編集ページにアクセスできない
     public function test_admin_cannot_access_reviews_edit()
     {
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();
 
         $restaurant = Restaurant::factory()->create();
 
         $user = User::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->actingAs($admin, 'admin')->get(route('restaurants.reviews.edit', [$restaurant, $review]));
 
         $response->assertRedirect(route('admin.home'));
     }
 
     // 未ログインのユーザーはレビューを更新できない
     public function test_guest_cannot_access_reviews_update()
     {
         $restaurant = Restaurant::factory()->create();
 
         $user = User::factory()->create();
 
         $old_review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $new_review_data = [
             'score' => 5,
             'content' => 'テスト更新'
         ];
 
         $response = $this->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $new_review_data);
 
         $this->assertDatabaseMissing('reviews', $new_review_data);
         $response->assertRedirect(route('login'));
     }
 
     // ログイン済みの無料会員はレビューを更新できない
     public function test_free_user_cannot_access_reviews_update()
     {
         $user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $old_review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $new_review_data = [
             'score' => 5,
             'content' => 'テスト更新'
         ];
 
         $response = $this->actingAs($user)->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $new_review_data);
 
         $this->assertDatabaseMissing('reviews', $new_review_data);
         $response->assertRedirect(route('subscription.create'));
     }
 
     // ログイン済みの有料会員は他人のレビューを更新できない
     public function test_premium_user_cannot_access_others_reviews_update()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
         $other_user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $old_review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $other_user->id
         ]);
 
         $new_review_data = [
             'score' => 5,
             'content' => 'テスト更新'
         ];
 
         $response = $this->actingAs($user)->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $new_review_data);
 
         $this->assertDatabaseMissing('reviews', $new_review_data);
         $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
     }
 
     // ログイン済みの有料会員は自身のレビューを更新できる
     public function test_premium_user_can_access_own_reviews_update()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
 
         $restaurant = Restaurant::factory()->create();
 
         $old_review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $new_review_data = [
             'score' => 5,
             'content' => 'テスト更新'
         ];
 
         $response = $this->actingAs($user)->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $new_review_data);
 
         $this->assertDatabaseHas('reviews', $new_review_data);
         $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
     }
 
     // ログイン済みの管理者はレビューを更新できない
     public function test_admin_cannot_access_reviews_update()
     {
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();
 
         $restaurant = Restaurant::factory()->create();
 
         $user = User::factory()->create();
 
         $old_review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $new_review_data = [
             'score' => 5,
             'content' => 'テスト更新'
         ];
 
         $response = $this->actingAs($admin, 'admin')->patch(route('restaurants.reviews.update', [$restaurant, $old_review]), $new_review_data);
 
         $this->assertDatabaseMissing('reviews', $new_review_data);
         $response->assertRedirect(route('admin.home'));
     }
 
     // 未ログインのユーザーはレビューを削除できない
     public function test_guest_cannot_access_reviews_destroy()
     {
         $restaurant = Restaurant::factory()->create();
 
         $user = User::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));
 
         $this->assertDatabaseHas('reviews', ['id' => $review->id]);
         $response->assertRedirect(route('login'));
     }
 
     // ログイン済みの無料会員はレビューを削除できない
     public function test_free_user_cannot_access_reviews_destroy()
     {
         $user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->actingAs($user)->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));
 
         $this->assertDatabaseHas('reviews', ['id' => $review->id]);
         $response->assertRedirect(route('subscription.create'));
     }
 
     // ログイン済みの有料会員は他人のレビューを削除できない
     public function test_premium_user_cannot_access_others_reviews_destroy()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
         $other_user = User::factory()->create();
 
         $restaurant = Restaurant::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $other_user->id
         ]);
 
         $response = $this->actingAs($user)->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));
 
         $this->assertDatabaseHas('reviews', ['id' => $review->id]);
         $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
     }
 
     // ログイン済みの有料会員は自身のレビューを削除できる
     public function test_premium_user_can_access_own_reviews_destroy()
     {
         $user = User::factory()->create();
         $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
 
         $restaurant = Restaurant::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->actingAs($user)->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));
 
         $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
         $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
     }
 
     // ログイン済みの管理者はレビューを削除できない
     public function test_admin_cannot_access_reviews_destroy()
     {
         $admin = new Admin();
         $admin->email = 'admin@example.com';
         $admin->password = Hash::make('nagoyameshi');
         $admin->save();
 
         $restaurant = Restaurant::factory()->create();
 
         $user = User::factory()->create();
 
         $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
         $response = $this->actingAs($admin, 'admin')->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));
 
         $this->assertDatabaseHas('reviews', ['id' => $review->id]);
         $response->assertRedirect(route('admin.home'));
     }
}