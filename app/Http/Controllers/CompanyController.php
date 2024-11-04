<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index() {
        $company = Company::first();
        // dd($company);
        return view('company.index', compact('company'));
     }
}
