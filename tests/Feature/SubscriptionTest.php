<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    
    //未ログインのユーザーは有料プラン登録ページにアクセスできない
    public function test_guest_cannot_access_subscription_create()
    {
        $user = User::factory()->create();

        $response = $this->get(route('subscription.create'));

        $response->assertRedirect(route('login'));
    }
    //ログイン済みの無料会員は有料プラン登録ページにアクセスできる
    public function test_free_member_can_access_subscription_create()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('subscription.create'));

        $response->assertStatus(302);
    }
    //ログイン済みの有料会員は有料プラン登録ページにアクセスできない
    public function test_paid_member_cannot_access_subscription_create()
    {
        $user = User::factory()->create();

        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        
        $response = $this->actingAs($user)->get(route('subscription.create'));
        
        $response->assertRedirect(route('subscription.edit'));
    }
    //ログイン済みの管理者は有料プラン登録ページにアクセスできない
    public function test_admin_cannot_access_subscription_create()
    {
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

         $response = $this->actingAs($admin, 'admin')->get(route('subscription.create'));
 
         $response->assertRedirect(route('admin.home'));
    }


    //未ログインのユーザーは有料プランに登録できない
    public function test_guest_cannot_access_subscription_store()
    {
        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->post(route('subscription.store'), $request_parameter);
        $response->assertRedirect(route('login'));
    }
    //ログイン済みの無料会員は有料プランに登録できる
    public function test_user_can_access_subscription_store() 
    {
        $user = User::factory()->create();

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        
        $response = $this->actingAS($user)->post(route('subscription.store'), $request_parameter);
    
        $response->assertRedirect(route('home'));
 
        $user->refresh();
    
        $this->assertTrue($user->subscribed('premium_plan'));
    }

    //ログイン済みの有料会員は有料プランに登録できない
    public function test_premium_user_cannot_access_subscription_store()
    {
        $user = User::factory()->create();

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        
        $response = $this->actingAS($user)->post(route('subscription.store'), $request_parameter);

        $response->assertRedirect(route('subscription.edit'));
    }
    //ログイン済みの管理者は有料プランに登録できない
    public function test_admin_cannot_access_subscription_store() 
    {
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('subscription.store'), $request_parameter);
        
        $response->assertRedirect(route('admin.home'));
    }


    //未ログインのユーザーはお支払い方法編集ページにアクセスできない
    public function test_guest_cannot_access_subscription_edit()
    {
        $response = $this->get(route('subscription.edit'));

        $response->assertRedirect(route('login'));
    }
    //ログイン済みの無料会員はお支払い方法編集ページにアクセスできない
    public function test_free_member_cannot_access_subscription_edit()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('subscription.edit'));

        $response->assertRedirect(route('subscription.create'));
    }
    //ログイン済みの有料会員はお支払い方法編集ページにアクセスできる
    public function test_paid_member_can_access_subscription_edit()
    {
        $user_subscription = User::factory()->create(); 

        $user_subscription->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');

        $response = $this->actingAs($user_subscription)->get(route('subscription.edit'));

        $response->assertStatus(200);
    }
    //ログイン済みの管理者はお支払い方法編集ページにアクセスできない
    public function test_admin_cannot_access_subscription_edit()
    {
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);
        $response = $this->actingAs($this->admin, 'admin')->get(route('subscription.edit'));

        $response->assertRedirect(route('admin.home'));
    }


    //未ログインのユーザーはお支払い方法を更新できない
    public function test_guest_cannot_access_subscription_update()
    {
        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->patch(route('subscription.update'), $request_parameter);
        $response->assertRedirect(route('login'));
    }
    //ログイン済みの無料会員はお支払い方法を更新できない
    public function test_user_cannot_access_subscription_update() 
    {
        $user = User::factory()->create();
        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->actingAs($user)->patch(route('subscription.update'), $request_parameter);
       
        $response->assertRedirect(route('subscription.create'));
    }
     //ログイン済みの有料会員はお支払い方法を更新できる
     public function test_membership_user_can_access_subscription_update() 
     {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $default_payment_method_id = $user->defaultPaymentMethod()->id;
        $request_parameter = [
            'paymentMethodId' => 'pm_card_mastercard'
        ];
        $this->actingAs($user)->patch(route('subscription.update'), $request_parameter);

        $user->refresh();
        $this->assertNotEquals($default_payment_method_id, $user->defaultPaymentMethod()->id);
    }
    //ログイン済みの管理者はお支払い方法を更新できない
    public function test_admin_cannot_access_subscription_update() 
    {
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->actingAs($admin, 'admin')->patch(route('subscription.update'), $request_parameter);
        $response->assertRedirect(route('admin.home'));
    }


    //未ログインのユーザーは有料プラン解約ページにアクセスできない
    public function test_guest_cannot_access_subscription_cancel()
    {
        $response = $this->get(route('subscription.cancel'));

        $response->assertRedirect(route('login'));
    }
    //ログイン済みの無料会員は有料プラン解約ページにアクセスできない
    public function test_free_member_cannot_access_subscription_cancel()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('subscription.cancel'));

        $response->assertRedirect(route('subscription.create'));
    }
    //ログイン済みの有料会員は有料プラン解約ページにアクセスできる
    public function test_paid_member_can_access_subscription_cancel()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $response = $this->actingAs($user)->get('subscription/cancel');
        $response->assertStatus(200);
    }

    //ログイン済みの管理者は有料プラン解約ページにアクセスできない
    public function test_admin_cannot_access_subscription_cancel()
    {
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('subscription.cancel'));

        $response->assertRedirect(route('admin.home'));
    }


    //未ログインのユーザーは有料プランを解約できない
    public function test_guest_cannot_access_subscription_delete()
    {
        $response = $this->delete(route('subscription.destroy'));
        
        $response->assertRedirect(route('login'));

    }
    //ログイン済みの無料会員は有料プランを解約できない
    public function test_user_cannot_access_subscription_delete() 
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('subscription.destroy'));
        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員は有料プランを解約できる
    public function test_membership_user_can_access_subscription_delete() 
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd')->create('pm_card_visa');
        $this->actingAs($user)->delete(route('subscription.destroy', $user));

        $this->assertFalse($user->subscribed('premium_plan'));
    }
    //ログイン済みの管理者は有料プランを解約できない
    public function test_admin_cannot_access_subscription_delete() 
    {
        $admin = Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('nagoyameshi'),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete('/subscription');

        $response->assertRedirect(route('admin.home'));
    }
}