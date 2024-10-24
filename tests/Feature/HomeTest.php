<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     //未ログインのユーザーは会員側のトップページにアクセスできる
    public function test_example(): void
    {
        $response = $this->get(route('user.index'));

        $response->assertRedirect(route('login'));
    }

    //ログイン済みの一般ユーザーは会員側のトップページにアクセスできる
    public function test_user_can_access_home()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
    }

    //ログイン済みの管理者は会員側のトップページにアクセスできない
    public function test_admin_cannot_access_home()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        
        $response = $this->actingAs($admin, 'admin')->get(route('home'));

        $response->assertRedirect(route('admin.home'));
    }
}

