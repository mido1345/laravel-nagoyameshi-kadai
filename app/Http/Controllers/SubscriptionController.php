<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    //createアクション（有料プラン登録ページ）
    public function create(){

        $user = Auth::user();

if($user->subscribed('premium_plan')){
    return to_route('subscription.edit');
}
    $intent = Auth::user()->createSetupIntent();

    return view('subscription.create', compact('intent'));

       }

       //storeアクション（有料プラン登録機能）
       public function store(Request $request){
       
        $request->user()->newSubscription(
            'premium_plan', 'price_1QEoEpLe5NnKPu47SEAazsHd'
        )->create($request->paymentMethodId);
     return to_route('user.index')->with('flash_message', '有料プランの登録が完了しました。');
       }
    
       //editアクション（お支払い方法編集ページ）
       public function edit(){

        $user = Auth::user();
        $intent = Auth::user()->createSetupIntent();
    
     return view('subscription.edit', compact('user', 'intent'));
    
       }
    
       //updateアクション（お支払い方法更新機能）
       public function update(Request $request){
    
        $request->user()->updateDefaultPaymentMethod($request->paymentMethodId);

        return to_route('user.index')->with('flash_message', 'お支払方法を変更しました。');
       }
    
       //cancelアクション（有料プラン解約ページ）
       public function cancel(){
        return view('subscription.cancel');
       }

       //destroyアクション（有料プラン解約機能）
       public function destroy(Request $request){    
        
        $request->user()->subscription('premium_plan')->cancelNow();
      
        return to_route('user.index')->with('flash_message', '有料プランを解約しました。');
       }
    }