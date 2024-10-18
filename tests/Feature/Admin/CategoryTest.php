<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Category;


class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

     //indexアクション（カテゴリ一覧ページ）
    public function test_guest_cannot_access_admin_users_categories_index()
    {
        $response = $this->get('/admin/categories');
        $response->assertRedirect('/admin/login');
    }

    public function test_user_cannot_access_admin_users_categories_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin/categories');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_admin_users_categories_index()
    {

        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $response = $this->actingAs($admin, 'admin')->get('/admin/categories');
        $response->assertStatus(200);
    }

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


     //storeアクション（カテゴリ更新機能）
     public function test_guest_cannot_store_categories()
    {
        $category = [
            'name' => 'テスト',
        ];
        $this->post(route('admin.categories.store'), $category);
        $this->assertDatabaseMissing('categories', $category);
    }

    public function test_user_cannot_store_categories()
    {
        $category = [
            'name' => 'テスト',
        ];
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('admin.categories.store'), $category);
        $this->assertDatabaseMissing('categories', $category);
    }
  
    public function test_admin_can_store_categories()
    {
        $category = [
            'name' => '更新テスト',
        ];
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), $category);
        $this->assertDatabaseHas('categories', $category);
    }

 
    //updateアクション（カテゴリ更新機能）
    public function test_guest_cannot_update_categories()
    {
        $category_old = Category::factory()->create();
        $category_new = [
            'name' => '更新テスト',
        ];
        $this->patch(route('admin.categories.update', $category_old), $category_new);
        $this->assertDatabaseMissing('categories', $category_new);
    }
   
    public function test_user_cannot_update_categories()
    {
        $category_old = Category::factory()->create();
        $category_new = [
            'name' => '更新テスト',
        ];
        $user = User::factory()->create();
        $this->actingAs($user)->patch(route('admin.categories.update', $category_old), $category_new);
        $this->assertDatabaseMissing('categories', $category_new);
        
    }
   
    public function test_admin_can_update_categories()
    {
        $category_old = Category::factory()->create();
        $category_new = [
            'name' => '更新テスト',
        ];
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $this->actingAs($admin, 'admin')->patch(route('admin.categories.update', $category_old), $category_new);
        $this->assertDatabaseHas('categories', $category_new);
    }


 
    //destroyアクション（カテゴリ削除機能）
    public function test_guest_cannot_destroy_categories()
    {
        $category = Category::factory()->create();
        $delete_id = [
            'id' => $category->id,
        ];
        $this->delete(route('admin.categories.destroy', $category));
        $this->assertDatabaseHas('categories', $delete_id);
    }
    
    public function test_user_cannot_destroy_categories()
    {
        $category = Category::factory()->create();
        $delete_id = [
            'id' => $category->id,
        ];
        $user = User::factory()->create();
        $this->actingAs($user)->delete(route('admin.categories.destroy', $category));
        $this->assertDatabaseHas('categories', $delete_id);
    }
    
    public function test_admin_can_destroy_categories()
    {
        $category = Category::factory()->create();
        $delete_id = [
            'id' => $category->id,
        ];
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $this->actingAs($admin, 'admin')->delete(route('admin.categories.destroy', $category));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}