<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\User;
use App\Models\Term;

class TermTest extends TestCase
{
    use RefreshDatabase;

    //indexアクション（利用規約ページ）
    // 未ログインのユーザーは管理者側の利用規約ページにアクセスできない
    public function test_guest_cannot_access_admin_terms_index()
    {
        $term = Term::factory()->create();

        $response = $this->get(route('admin.terms.index'));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側の利用規約ページにアクセスできない
    public function test_user_cannot_access_admin_terms_index()
    {
        $user = User::factory()->create();

        $term = Term::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.terms.index'));

        $response->assertRedirect(route('admin.login'));
    }

     // ログイン済みの管理者は管理者側の利用規約ページにアクセスできる
    public function test_admin_can_access_admin_terms_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $term = Term::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.index'));

        $response->assertStatus(500);
    }

    // editアクション（利用規約編集ページ）
    // 未ログインのユーザーは管理者側の利用規約編集ページにアクセスできない
    public function test_guest_cannot_access_admin_terms_edit()
    {
        $term = Term::factory()->create();

        $response = $this->get(route('admin.terms.edit', $term));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側の利用規約編集ページにアクセスできない
    public function test_user_cannot_access_admin_terms_edit()
    {
        $user = User::factory()->create();

        $term = Term::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.terms.edit', $term));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は管理者側の利用規約編集ページにアクセスできる
    public function test_admin_can_access_admin_terms_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $term = Term::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.edit', $term));

        $response->assertStatus(200);
    }

     //updateアクション（利用規約更新機能）
     // 未ログインのユーザーは利用規約を更新できない
    public function test_guest_cannot_access_terms_update()
    {
            $term = Term::factory()->create();

            // 更新しようとするデータ
            $updateData = [
                'content' => 'テスト更新',
            ];

            // 未ログインのユーザーが更新しようとした場合のレスポンス
            $response = $this->patch(route('admin.terms.update', $term), $updateData);

            $this->assertDatabaseMissing('terms', $updateData);

            $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは利用規約を更新できない
    public function test_user_cannot_access_admin_terms_update()
    {
            $user = User::factory()->create();
            $term = Term::factory()->create();

            $updateData = [
                'content' => 'テスト更新',
            ];

            $response = $this->actingAs($user)->patch(route('admin.terms.update', $term),$updateData);

            $this->assertDatabaseMissing('terms', $updateData);

            $response->assertRedirect(route('admin.login'));
            }

     // ログイン済みの管理者は利用規約を更新できる
    public function test_admin_can_access_admin_terms_update()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

            $term = Term::factory()->create();
            
            $updateData = [
                'content' => 'テスト更新',
            ];


            $response = $this->actingAs($admin, 'admin')->patch(route('admin.terms.update', $term), $updateData);

            $this->assertDatabaseHas('terms', $updateData);

            $response->assertRedirect(route('admin.terms.index'));
    }


}