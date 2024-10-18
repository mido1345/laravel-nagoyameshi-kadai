<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        // 管理者のみアクセスできるようにミドルウェアを設定
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {

        // if (!Auth::guard('admin')->check()) {
        //     abort(403);
        // }

        $keyword = $request->input('keyword');

        if ($keyword !== null) {
            $users = User::where('name', 'like', "%{$keyword}%")
                ->orWhere('kana', 'like', "%{$keyword}%")
                ->paginate(15);
        } else {
            $users = User::paginate(15);
        }

        $total = $users->total();

        return view('admin.users.index', compact('users', 'keyword', 'total'));

    }

    public function show($id)
    {

        // if (!Auth::guard('admin')->check()) {
        //     abort(403);
        // }
    $user = User::findOrFail($id);

    return view('admin.users.show', compact('user'));
    }
}
