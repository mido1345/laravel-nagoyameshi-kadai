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
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('login')); // ログインページへのリダイレクトを確認
    }

    // ログイン済みの一般ユーザーが会員一覧ページにアクセスできない
    public function test_regular_user_can_not_access_admin_users_index()
    {
        $user = factory(User::class)->create(); // 一般ユーザーを作成
        $this->actingAs($user); // 一般ユーザーとしてログイン

        $response = $this->get(route('admin.users.index'));
        $response->assertForbidden();
    }

    // ログイン済みの管理者が会員一覧ページにアクセスできる
    public function test_admin_can_access_admin_users_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->get(route('admin.users.index'));

         // 管理者として会員一覧ページにアクセス
        $response->assertStatus(200);  // 200 OKが返されることを確認
    }

    // 未ログインユーザーが会員詳細ページにアクセスできない
    public function test_guest_can_not_access_admin_users_show()
    {
        //テスト用のユーザーを作成
        $user = factory(User::class)->create(); 

        $response = $this->get(route('admin.users.show', $user));

        //ログインページへのリダイレクトを確認
        $response->assertRedirect(route('login')); 
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
