@extends('layouts.app')

@section('content')
    <div class="container nagoyameshi-container pb-5">
        <div class="row justify-content-center">
            <nav class="my-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
                    <li class="breadcrumb-item active" aria-current="page">店舗一覧</li>
                </ol>
            </nav>

            <div class="col-xl-3 col-lg-4 col-md-12">
                <form method="GET" action="{{ route('restaurants.index') }}" class="w-100 mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="店舗名・エリア・カテゴリ" name="keyword" value="{{ $keyword }}">
                        <button type="submit" class="btn text-white shadow-sm nagoyameshi-btn">検索</button>
                    </div>
                </form>

                <div class="card mb-3">
                    <div class="card-header">
                        カテゴリから探す
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('restaurants.index') }}" class="w-100">
                            <div class="form-group mb-3">
                                <select class="form-control form-select" name="category_id" required>
                                    <option value="" hidden>選択してください</option>
                                    @foreach ($categories as $category)
                                        @if ($category->id == $category_id)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn text-white shadow-sm w-100 nagoyameshi-btn">検索</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        予算から探す
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('restaurants.index') }}" class="w-100">
                            <div class="form-group mb-3">
                                <select class="form-control form-select" name="price" required>
                                    <option value="" hidden>選択してください</option>
                                    @for ($i = 0; $i < 20; $i++)
                                        {{ $each_price = 500 + (500 * $i) }}
                                        @if ($each_price == $price)
                                            <option value="{{ $each_price }}" selected>{{ number_format($each_price) }}円</option>
                                        @else
                                            <option value="{{ $each_price }}">{{ number_format($each_price) }}円</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn text-white shadow-sm w-100 nagoyameshi-btn">検索</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex justify-content-between flex-wrap">
                    <p class="fs-5 mb-3">
                        {{ number_format($total) }}件の店舗が見つかりました
                        <span class="fs-6">
                            @if ($total > 15)
                                （{{ 15 * $restaurants->currentPage() - 14 }}～{{ 15 * $restaurants->currentPage() }}件）
                            @endif
                        </span>
                    </p>
                    <form method="GET" action="{{ route('restaurants.index') }}" class="mb-3 nagoyameshi-sort-box">
                        @if ($keyword)
                            <input type="hidden" name="keyword" value="{{ $keyword }}">
                        @endif
                        @if ($category_id)
                            <input type="hidden" name="category_id" value="{{ $category_id }}">
                        @endif
                        @if ($price)
                            <input type="hidden" name="price" value="{{ $price }}">
                        @endif
                        <select class="form-select form-select-sm" name="select_sort" aria-label=".form-select-sm example" onChange="this.form.submit();">
                            @foreach ($sorts as $key => $value)
                                @if ($sorted === $value)
                                    <option value="{{ $value }}" selected>{{ $key }}</option>
                                @else
                                    <option value="{{ $value }}">{{ $key }}</option>
                                @endif
                            @endforeach
                        </select>
                    </form>
                </div>

                @foreach ($restaurants as $restaurant)
                    <div class="mb-3">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="link-dark nagoyameshi-card-link">
                            <div class="card h-100">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        @if ($restaurant->image !== '')
                                            <img src="{{ asset('storage/restaurants/' . $restaurant->image) }}" class="card-img-top nagoyameshi-horizontal-card-image">
                                        @else
                                            <img src="{{ asset('/images/no_image.jpg') }}" class="card-img-top nagoyameshi-horizontal-card-image" alt="画像なし">
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h3 class="card-title">{{ $restaurant->name }}</h3>
                                            <div class="col d-flex text-secondary">
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
                                                    <span>カテゴリ未設定</span>
                                                @endif
                                            </div>
                                            <hr class="my-2">
                                            <p class="mb-1">
                                                <span class="nagoyameshi-star-rating me-1" data-rate="{{ round($restaurant->reviews->avg('score') * 2) / 2 }}"></span>
                                                {{ number_format(round($restaurant->reviews->avg('score'), 2), 2) }}（{{ $restaurant->reviews->count() }}件）
                                            </p>
                                            <div class="mb-1">
                                                <span>{{ number_format($restaurant->lowest_price) }}円～{{ number_format($restaurant->highest_price) }}円</span>
                                            </div>
                                            <p class="card-text">{{ mb_substr($restaurant->description, 0, 75) }}@if (mb_strlen($restaurant->description) > 75)...@endif</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center">
                    {{ $restaurants->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection