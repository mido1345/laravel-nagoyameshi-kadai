@extends('layouts.app')

@section('content')
<div class="container py-4 nagoyameshi-container">
    <div class="row justify-content-center">
        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-7">
            <h1 class="mb-4 text-center">パスワード再設定</h1>

            <hr class="mb-4">

            <p>ご登録中のメールアドレスを入力してください。パスワード再設定用のURLをお送りします。</p>

            @if (session('status'))
                <div class="alert alert-info" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group mb-3">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="メールアドレス" autofocus>
                </div>

                <div class="form-group d-flex justify-content-center mb-4">
                    <button type="submit" class="btn text-white shadow-sm w-100 nagoyameshi-btn">送信</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
