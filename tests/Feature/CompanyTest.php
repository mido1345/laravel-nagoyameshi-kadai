<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use App\Models\Company;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    //未ログインのユーザーは会員側の会社概要ページにアクセスできる
    public function test_guest_can_access_company_index()
    {
        $company = Company::factory()->create();

        $response = $this->get(route('company.index'));

        $response->assertStatus(200);
    }

    // ログイン済みの一般ユーザーは会員側の会社概要ページにアクセスできる
    public function test_user_can_access_company_index()
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        $response = $this->actingAs($user)->get(route('company.index'));

        $response->assertStatus(200);
    }

    //ログイン済みの管理者は会員側の会社概要ページにアクセスできない
    public function test_admin_cannot_access_company_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $company = Company::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('company.index'));

        $response->assertRedirect(route('admin.home'));
    }
}
