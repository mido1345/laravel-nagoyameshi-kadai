@extends('layouts.app')

@section('content')
    <div class="container nagoyameshi-container pb-5">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <nav class="my-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">店舗一覧</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $restaurant) }}">店舗詳細</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.reviews.index', $restaurant) }}">レビュー</a></li>
                        <li class="breadcrumb-item active" aria-current="page">レビュー投稿</li>
                    </ol>
                </nav>

                <h1 class="mb-2 text-center">{{ $restaurant->name }}</h1>
                <p class="text-center">
                    <span class="nagoyameshi-star-rating me-1" data-rate="{{ round($restaurant->reviews->avg('score') * 2) / 2 }}"></span>
                    {{ number_format(round($restaurant->reviews->avg('score'), 2), 2) }}（{{ $restaurant->reviews->count() }}件）
                </p>

                @if (session('flash_message'))
                    <div class="alert alert-info" role="alert">
                        <p class="mb-0">{{ session('flash_message') }}</p>
                    </div>
                @endif

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link link-dark" href="{{ route('restaurants.show', $restaurant) }}">トップ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark" href="{{ route('restaurants.reservations.create', $restaurant) }}">予約</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white nagoyameshi-bg" aria-current="page" href="{{ route('restaurants.reviews.index', $restaurant) }}">レビュー</a>
                    </li>
                </ul>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('restaurants.reviews.store', $restaurant) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-md-left fw-bold">評価</label>

                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="score1" type="radio" name="score" value="1">
                                <label class="form-check-label" for="score1">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="score2" type="radio" name="score" value="2">
                                <label class="form-check-label" for="score2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="score3" type="radio" name="score" value="3">
                                <label class="form-check-label" for="score3">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="score4" type="radio" name="score" value="4">
                                <label class="form-check-label" for="score4">4</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" id="score5" type="radio" name="score" value="5" checked>
                                <label class="form-check-label" for="score5">5</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label text-md-left fw-bold">感想</label>

                        <div>
                            <textarea class="form-control" id="content" name="content" cols="30" rows="5">{{ old('content') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-center mb-4">
                        <button type="submit" class="btn text-white shadow-sm w-50 nagoyameshi-btn">投稿</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection