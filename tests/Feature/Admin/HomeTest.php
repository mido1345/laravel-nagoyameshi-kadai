<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Reservaton;
use App\Models\Rrestaurant;
use App\Models\User;
use App\Models\Admin;

class HomeTest extends TestCase
{
    use RefreshDatabase;
    
    //未ログインのユーザーは管理者側のトップページにアクセスできない
    public function test_guest_cannot_access_admin_home()
    {
        $response = $this->get(route('admin.home'));

        $response->assertRedirect(route('admin.login'));
    }

    //ログイン済みの一般ユーザーは管理者側のトップページにアクセスできない
    public function test_user_cannot_access_admin_home()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.home'));

        $response->assertRedirect(route('admin.login'));
    }

    //ログイン済みの管理者は管理者側のトップページにアクセスできる
    public function test_admin_can_access_admin_home()
    {
        $adminUser = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.home'));

        $response->assertStatus(200);
    }

}
