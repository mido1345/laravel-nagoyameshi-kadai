<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Subscribed;
use App\Models\NotSubscribed;
use App\Models\Review;
use App\Models\Restaurant;


class ReviewTest extends TestCase
{
    use RefreshDatabase;

        public function test_guest_cannot_access_review_index():void
{
    $restaurant = Restaurant::factory()->create();
    $response = $this->get(route('restaurants.reviews.index',$restaurant));
    $response->assertRedirect(route('login'));
}


public function test_user_can_access_review_index():void
{
    $restaurant = Restaurant::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('restaurants.reviews.index',$restaurant));
    $response->assertStatus(200);
}


public function test_membership_user_can_access_review_index():void
{
    $restaurant = Restaurant::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('restaurants.reviews.index',$restaurant));
    $response->assertStatus(200);
}


public function test_admin_cannot_access_review_index():void
{
    $admin = Admin::create([
        'email' => 'admin@example.com',
        'password' => Hash::make('nagoyameshi'),
    ]);

    $restaurant = Restaurant::factory()->create();

    $response = $this->actingAs($admin,'admin')->get(route('restaurants.reviews.index',$restaurant));

    $response->assertRedirect(route('admin.home'));
}


public function test_guest_cannot_access_review_create():void
{
    $restaurant = Restaurant::factory()->create();
    $response = $this->get(route('restaurants.reviews.create',$restaurant));
    $response->assertRedirect(route('login'));
}


public function test_user_cannot_access_review_create():void
{
    $restaurant = Restaurant::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('restaurants.reviews.create',$restaurant));
    $response->assertRedirect(route('subscription.create'));
}


public function test_membership_user_can_access_review_create():void
{
    $restaurant = Restaurant::factory()->create();
    $user = User::factory()->create();

    $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');

    $response = $this->actingAs($user)->get(route('restaurants.reviews.create',$restaurant));
    $response->assertStatus(200);
}


public function test_admin_cannot_access_review_create():void
{
    $admin = Admin::create([
        'email' => 'admin@example.com',
        'password' => Hash::make('nagoyameshi'),
    ]);

    $restaurant = Restaurant::factory()->create();

    $response = $this->actingAs($admin,'admin')->get(route('restaurants.reviews.create',$restaurant));

    $response->assertRedirect(route('admin.home'));
}


public function test_guest_cannot_posting_review_store():void
{
    $restaurant = Restaurant::factory()->create();
    $review = [
        'score' => 'test',
        'content' => 'test',
    ];

    $response = $this->post(route('restaurants.reviews.store',$restaurant));
    $response->assertRedirect(route('login'));
}


public function test_user_cannot_posting_review_store():void
{
    $user = User::factory()->create();
    $restaurant = Restaurant::factory()->create();
    $review = [
        'score' => 'test',
        'content' => 'test',
    ];

    $response = $this->actingAs($user)->post(route('restaurants.reviews.store',$restaurant));
    $response->assertRedirect(route('subscription.create'));
}


public function test_membership_user_can_posting_review_store():void
{
    $user = User::factory()->create();

    $restaurant = Restaurant::factory()->create();
    $review = [
        'score' => 'test',
        'content' => 'test',
    ];

    $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');

    $response = $this->actingAs($user)->get(route('restaurants.reviews.create',$restaurant));
    $response->assertStatus(200);
}


public function test_admin_cannot_posting_review_store():void
{
    $admin = Admin::create([
        'email' => 'admin@example.com',
        'password' => Hash::make('nagoyameshi'),
    ]);

    $restaurant = Restaurant::factory()->create();
    $review = [
        'score' => 'test',
        'content' => 'test',
    ];

    $response = $this->actingAs($admin,'admin')->get(route('restaurants.reviews.create',$restaurant));

    $response->assertRedirect(route('admin.home'));
}


public function test_guest_cannot_access_review_edit():void
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


public function test_user_cannot_access_review_edit():void
{
     $restaurant = Restaurant::factory()->create();
 
     $user = User::factory()->create();
 
     $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
      $response = $this->actingAs($user)->get(route('restaurants.reviews.edit', [$restaurant, $review]));
      $response->assertRedirect(route('subscription.create'));
}


public function test_membership_user_cannot_posting_review_edit():void
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


public function test_membership_user_can_access_review_edit():void
{
    $user = User::factory()->create();
    $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');

    $restaurant = Restaurant::factory()->create();
    $review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)->get(route('restaurants.reviews.edit',[$restaurant, $review]));
    $response->assertStatus(200);
}


public function test_admin_cannot_review_edit_psge():void
{
    $admin = Admin::create([
        'email' => 'admin@example.com',
        'password' => Hash::make('nagoyameshi'),
    ]);

    $restaurant = Restaurant::factory()->create();
 
    $user = User::factory()->create();
 
    $review = Review::factory()->create([
             'restaurant_id' => $restaurant->id,
             'user_id' => $user->id
         ]);
 
    $response = $this->actingAs($admin, 'admin')->get(route('restaurants.reviews.edit', [$restaurant, $review]));
 
    $response->assertRedirect(route('admin.home'));
}


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
 
 
public function test_user_cannot_access_reviews_update()
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
 
 
public function test_membership_user_cannot_access_others_reviews_update()
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
 

 public function test_membership_user_can_access_reviews_update()
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
 

public function test_admin_cannot_access_reviews_update()
 {
    $admin = Admin::create([
        'email' => 'admin@example.com',
        'password' => Hash::make('nagoyameshi'),
    ]);
 
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


public function test_guest_cannot_delete_reviews()
 {
    $restaurant = Restaurant::factory()->create();
    $review = [
            'score' => '5',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];

    $response = $this->delete(route('restaurants.reviews.destroy', [$restaurant, 10]));

    $response->assertRedirect('login');
     }


public function test_user_cannot_delete_reviews()
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


public function test_membership_user_can_delete_reviews()
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


public function test_admin_cannot_delete_reviews()
 {
    $admin = Admin::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('nagoyameshi'),
    ]);

    $user = User::factory()->create();
    $restaurant = Restaurant::factory()->create();
    $review = Review::factory()->create([
        'restaurant_id' => $restaurant->id,
        'user_id' => $user->id
    ]);
    $this->actingAs($admin, 'admin');

    $response = $this->delete(route('restaurants.reviews.destroy', [$restaurant, $review]));
    $response->assertRedirect(route('admin.home'));

     }
}


