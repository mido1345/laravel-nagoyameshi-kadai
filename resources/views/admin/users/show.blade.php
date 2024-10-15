@extends('layouts.app')

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">会員一覧</a></li>
                        <li class="breadcrumb-item active" aria-current="page">会員詳細</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">{{ $user->name }}</h1>

                <div class="container mb-4">
                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">ID</span>
                        </div>

                        <div class="col">
                            <span>{{ $user->id }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">氏名</span>
                        </div>

                        <div class="col">
                            <span>{{ $user->name }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">フリガナ</span>
                        </div>

                        <div class="col">
                            <span>{{ $user->kana }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">メールアドレス</span>
                        </div>

                        <div class="col">
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">郵便番号</span>
                        </div>

                        <div class="col">
                            <span>{{ substr($user->postal_code, 0, 3) . '-' . substr($user->postal_code, 3) }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">住所</span>
                        </div>

                        <div class="col">
                            <span>{{ $user->address }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">電話番号</span>
                        </div>

                        <div class="col">
                            <span>{{ $user->phone_number }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">誕生日</span>
                        </div>

                        <div class="col">
                            <span>
                                @if ($user->birthday !== null)
                                    {{ date('n月j日', strtotime($user->birthday)) }}
                                @else
                                    未設定
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">職業</span>
                        </div>

                        <div class="col">
                            <span>
                                @if ($user->occupation !== null)
                                    {{ $user->occupation }}
                                @else
                                    未設定
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-3">
                            <span class="fw-bold">会員種別</span>
                        </div>

                        <div class="col">
                            <span>
                                未設定
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection