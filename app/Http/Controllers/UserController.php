<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //indexアクション（会員情報ページ）
    public function index(){
        $user = Auth::user();
        
        return view('user.index', compact('user'));

    }

    //editアクション（会員情報編集ページ）
    public function edit(User $user){

        if (Auth::id() !== $user->id) {
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        
        }
        return view('user.edit', compact('user'));
    }
    
    //updateアクション（会員情報更新機能）
    public function update(Request $request, User $user)
    {
        if (Auth::id() !== $user->id) {
            return redirect()->route('user.index')->with('error_message', '不正なアクセスです。');
        }

        $request->validate([
        'name' => 'required', 'string', 'max:255',
        'kana' => 'required', 'string','regex:/^[ァ-ヶー]+$/u ', 'max:255',
        'email' => 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id),
        'postal_code' => 'required', 'digits:7',
        'address' => 'required', 'string', 'max:255',
        'phone_number' => 'required', 'digits_between:10, 11',
        'birthday' => 'nullable', 'digits:8',
        'occupation' => 'nullable', 'string', 'max:255',        
        ]);

        $user->name = $request->input('name');
        $user->kana = $request->input('kana');
        $user->email = $request->input('email');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->phone_number = $request->input('phone_number');   
        $user->birthday = $request->input('birthday');
        $user->occupation = $request->input('occupation');
        $user->save();

        return redirect()->route('user.index')->with('flash_message', '会員情報を編集しました。');
    }
}