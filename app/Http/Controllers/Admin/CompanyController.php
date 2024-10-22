<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    //indexアクション（会社概要ページ）
    public function index()
    {
        $company = Company::first();
        return view('admin.company.index', compact('company'));
    }

    //editアクション（会社概要編集ページ）
    public function edit(Company $company)
    {
        return view('admin.company.edit', compact('company'));
    }

    //updateアクション（会社概要更新機能）
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required',
            'representative' => 'required',
            'establishment_date' => 'required',
            'capital' => 'required',
            'business' => 'required',
            'number_of_employees' => 'required',
        ]);

        $company->name = $request->input('name');
        $company->postal_code = $request->input('postal_code');
        $company->address = $request->input('address');
        $company->representative = $request->input('representative');
        $company->establishment_date = $request->input('establishment_date');
        $company->capital = $request->input('capital');
        $company->business = $request->input('business');
        $company->number_of_employees = $request->input('number_of_employees');
        $company->update();

        return redirect()->route('admin.company.index')
        ->with('flash_message', '会社概要を編集しました。');
    }
}


