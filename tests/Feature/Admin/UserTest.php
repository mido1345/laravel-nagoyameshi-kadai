<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // 未ログインユーザーが会員一覧ページにアクセスできない
    public function test_guest_can_not_access_admin_users_index()
    {
        $response = $this->get('/admin/users');
        $response->assertRedirect('/admin/login'); // ログインページへのリダイレクトを確認
    }

    // ログイン済みの一般ユーザーが会員一覧ページにアクセスできない
    public function test_regular_user_can_not_access_admin_users_index()
    {
        $user = User::factory()->create(); // 一般ユーザーを作成
        $response = $this->actingAs($user)->get('/admin/users'); // 一般ユーザーとしてログイン

        $response->assertRedirect('/admin/login');
    }

    // ログイン済みの管理者が会員一覧ページにアクセスできる
    public function test_admin_can_access_admin_users_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get('/admin/users');

         // 管理者として会員一覧ページにアクセス
        $response->assertStatus(200);  // 200 OKが返されることを確認
    }

    // 未ログインユーザーが会員詳細ページにアクセスできない
    public function test_guest_can_not_access_admin_users_show()
    {
        $user = User::factory()->create(); // テスト用のユーザーを作成

        $response = $this->get("/admin/users/{$user->id}");
        $response->assertRedirect('/admin/login'); // ログインページへのリダイレクトを確認
    }

    // ログイン済みの一般ユーザーが会員詳細ページにアクセスできない
    public function test_regular_user_can_not_access_admin_users_show()
    {
        $user = User::factory()->create(); // 一般ユーザーを作成

        $response = $this->actingAs($user)->get("/admin/users/{$user->id}");
        $response->assertRedirect('/admin/login');
    }

    // ログイン済みの管理者が会員詳細ページにアクセスできる
    public function test_admin_can_access_admin_users_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        // テスト用のユーザーを作成
        $user = User::factory()->create();

        // 管理者としてテスト用に作成した会員詳細ページにアクセス
        $response = $this->actingAs($admin, 'admin')->get("/admin/users/{$user->id}");
        $response->assertStatus(200); // 200 OKが返されることを確認
    }
}
