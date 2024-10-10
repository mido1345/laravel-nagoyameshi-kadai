@extends('layouts.app')

@section('content')
<div class="container py-4 nagoyameshi-container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <h1 class="mb-4 text-center">パスワード再設定</h1>

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

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group row mb-3">
                    <label for="email" class="col-md-5 col-form-label text-md-left fw-bold pe-0">メールアドレス</label>

                    <div class="col-md-7">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="email" autofocus>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="password" class="col-md-5 col-form-label text-md-left fw-bold pe-0">新しいパスワード</label>

                    <div class="col-md-7">
                        <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="password-confirm" class="col-md-5 col-form-label text-md-left fw-bold pe-0">新しいパスワード（確認用）</label>

                    <div class="col-md-7">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group d-flex justify-content-center mb-4">
                    <button type="submit" class="btn text-white shadow-sm w-50 nagoyameshi-btn">再設定</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
