<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermController extends Controller
{
    //indexアクション（利用規約ページ）
    public function index()
    {
        $company = Term::orderBy('created_at')->first();
        return view('admin.terms.index', compact('term'));
    }
    
    //editアクション（利用規約編集ページ）
    public function edit(Request $request, Term $term)
    {
        $terms = Term::first();
        return view('admin.terms.edit', compact('term'));
    }

    //updateアクション（利用規約更新機能）
    public function update(Request $request, Term $term)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $term->content = $request->input('content');
        $term->update();

        return redirect()->route('admin.terms.index')
         ->with('flash_message', '利用規約を編集しました。');
    }

}
