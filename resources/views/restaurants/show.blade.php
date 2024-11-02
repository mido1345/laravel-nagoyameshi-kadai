@extends('layouts.app')

@section('content')
    <div class="container nagoyameshi-container pb-5">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <nav class="my-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">店舗一覧</a></li>
                        <li class="breadcrumb-item active" aria-current="page">店舗詳細</li>
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

                <ul class="nav nav-tabs mb-2">
                    <li class="nav-item">
                        <a class="nav-link active text-white nagoyameshi-bg" aria-current="page" href="{{ route('restaurants.show', $restaurant) }}">トップ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark" href="{{ route('restaurants.reservations.create', $restaurant) }}">予約</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark" href="{{ route('restaurants.reviews.index', $restaurant) }}">レビュー</a>
                    </li>
                </ul>

                <div class="mb-2">
                    @if ($restaurant->image !== '')
                        <img src="{{ asset('storage/restaurants/' . $restaurant->image) }}" class="w-100">
                    @else
                        <img src="{{ asset('/images/no_image.jpg') }}" class="w-100">
                    @endif
                </div>

                <div class="container">
                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">店舗名</span>
                        </div>

                        <div class="col">
                            <span>{{ $restaurant->name }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">説明</span>
                        </div>

                        <div class="col">
                            <span>{{ $restaurant->description }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">価格帯</span>
                        </div>

                        <div class="col">
                            <span>{{ number_format($restaurant->lowest_price) }}円～{{ number_format($restaurant->highest_price) }}円</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">郵便番号</span>
                        </div>

                        <div class="col">
                            <span>{{ substr($restaurant->postal_code, 0, 3) . '-' . substr($restaurant->postal_code, 3) }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">住所</span>
                        </div>

                        <div class="col">
                            <span>{{ $restaurant->address }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">営業時間</span>
                        </div>

                        <div class="col">
                            <span>{{ date('G:i', strtotime($restaurant->opening_time)) . '～' . date('G:i', strtotime($restaurant->closing_time)) }}</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">定休日</span>
                        </div>

                        <div class="col d-flex">
                            @if ($restaurant->regular_holidays()->exists())
                                @foreach ($restaurant->regular_holidays()->orderBy('regular_holiday_id', 'asc')->get() as $index => $regular_holiday)
                                    <div>
                                        @if ($index === 0)
                                            {{ $regular_holiday->day }}
                                        @else
                                            {{ '、' . $regular_holiday->day }}
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <span>年中無休</span>
                            @endif
                        </div>
                    </div>

                    <div class="row pb-2 mb-2 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">座席数</span>
                        </div>

                        <div class="col">
                            <span>{{ number_format($restaurant->seating_capacity) }}席</span>
                        </div>
                    </div>

                    <div class="row pb-2 mb-4 border-bottom">
                        <div class="col-2">
                            <span class="fw-bold">カテゴリ</span>
                        </div>

                        <div class="col d-flex">
                            @if ($restaurant->categories()->exists())
                                @foreach ($restaurant->categories as $index => $category)
                                    <div>
                                        @if ($index === 0)
                                            {{ $category->name }}
                                        @else
                                            {{ '、' . $category->name }}
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <span>未設定</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection