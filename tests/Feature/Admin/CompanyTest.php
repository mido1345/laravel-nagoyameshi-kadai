<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    //indexアクション（会社概要ページ）
    // 未ログインのユーザーは管理者側の会社概要ページにアクセスできない
    public function test_guest_cannot_access_admin_company_index()
    {
        $company = Company::factory()->create();

        $response = $this->get(route('admin.company.index'));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側の会社概要ページにアクセスできない
    public function test_user_cannot_access_admin_company_index()
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.company.index'));

        $response->assertRedirect(route('admin.login'));
    }

     // ログイン済みの管理者は管理者側の会社概要ページにアクセスできる
    public function test_admin_can_access_admin_company_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        Company::factory()->create(['id' => 1]);
        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.index'));

        $response->assertStatus(200);
    }

    // editアクション（会社概要編集ページ）
    // 未ログインのユーザーは管理者側の会社概要編集ページにアクセスできない
    public function test_guest_cannot_access_admin_company_edit()
    {
        $company = Company::factory()->create();

        $response = $this->get(route('admin.company.edit', $company));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側の会社概要編集ページにアクセスできない
    public function test_user_cannot_access_admin_company_edit()
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.company.edit', $company));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は管理者側の会社概要編集ページにアクセスできる
    public function test_admin_can_access_admin_company_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $company = Company::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.edit', $company));

        $response->assertStatus(200);
    }

    // updateアクション（会社概要更新機能）
     // 未ログインのユーザーは会社概要を更新できない
    public function test_guest_cannot_access_company_update()
    {
            $company = Company::factory()->create();

            $updateData = [
                'name' => 'テスト更新',
                'postal_code' => '0000001',
                'address' => 'テスト',
                'representative' => 'テスト',
                'establishment_date' => 'テスト',
                'capital' => 'テスト',
                'business' => 'テスト',
                'number_of_employees' => 'テスト',
            ];

            $response = $this->patch(route('admin.company.update', $company), $updateData);

            $this->assertDatabaseMissing('companies', $updateData);

            $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは会社概要を更新できない
    public function test_user_cannot_access_admin_company_update()
    {
            $user = User::factory()->create();

            $old_company = Company::factory()->create();

            $new_company = [
                'name' => 'テスト更新',
                'postal_code' => '0000001',
                'address' => 'テスト',
                'representative' => 'テスト',
                'establishment_date' => 'テスト',
                'capital' => 'テスト',
                'business' => 'テスト',
                'number_of_employees' => 'テスト',
            ];

            $response = $this->actingAs($user)->patch(route('admin.company.update',$old_company),$new_company);
            
            $this->assertDatabaseMissing('companies', $new_company);

            $response->assertRedirect(route('admin.login'));
            }

     // ログイン済みの管理者は会社概要を更新できる
    public function test_admin_can_access_admin_company_update()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $company = Company::factory()->create();
 
            $new_company = [
                'name' => 'テスト更新',
                'postal_code' => '0000001',
                'address' => 'テスト',
                'representative' => 'テスト',
                'establishment_date' => 'テスト',
                'capital' => 'テスト',
                'business' => 'テスト',
                'number_of_employees' => 'テスト',
            ];

            $old_company = Company::factory()->create();
            $new_company = Company::factory()->make();

            $data = $new_company->toArray();
            
            $response = $this->actingAs($admin, 'admin')->patch(route('admin.company.update', $old_company), $data);

            $response->assertRedirect(route('admin.company.index'));

        }
}