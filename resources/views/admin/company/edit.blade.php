@extends('layouts.app')

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.company.index') }}">会社概要</a></li>
                        <li class="breadcrumb-item active" aria-current="page">会社概要編集</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">会社概要編集</h1>

                <hr class="mb-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.company.update', $company) }}">
                    @csrf
                    @method('patch')
                    <div class="form-group row mb-3">
                        <label for="name" class="col-md-5 col-form-label text-md-left fw-bold">会社名</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $company->name) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="postal_code" class="col-md-5 col-form-label text-md-left fw-bold">郵便番号</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="address" class="col-md-5 col-form-label text-md-left fw-bold">所在地</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $company->address) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="representative" class="col-md-5 col-form-label text-md-left fw-bold">代表者</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="representative" name="representative" value="{{ old('representative', $company->representative) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="establishment_date" class="col-md-5 col-form-label text-md-left fw-bold">設立</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="establishment_date" name="establishment_date" value="{{ old('establishment_date', $company->establishment_date) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="capital" class="col-md-5 col-form-label text-md-left fw-bold">資本金</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="capital" name="capital" value="{{ old('capital', $company->capital) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="business" class="col-md-5 col-form-label text-md-left fw-bold">事業内容</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="business" name="business" value="{{ old('business', $company->business) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="number_of_employees" class="col-md-5 col-form-label text-md-left fw-bold">従業員数</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="number_of_employees" name="number_of_employees" value="{{ old('number_of_employees', $company->number_of_employees) }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-group d-flex justify-content-center mb-4">
                        <button type="submit" class="btn text-white shadow-sm w-50 nagoyameshi-btn">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection