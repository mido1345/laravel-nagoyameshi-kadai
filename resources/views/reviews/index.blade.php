@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('/js/review-modal.js') }}"></script>
@endpush

@section('content')
<!-- レビューの削除用モーダル -->
<div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-labelledby="deleteReviewModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteReviewModalLabel">レビューを削除してもよろしいですか？</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
            </div>
            <div class="modal-footer">
                <form action="" method="post" name="deleteReviewForm">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn text-white shadow-sm nagoyameshi-btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="container nagoyameshi-container pb-5">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <nav class="my-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">店舗一覧</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $restaurant) }}">店舗詳細</a></li>
                        <li class="breadcrumb-item active" aria-current="page">レビュー</li>
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

                @if (session('error_message'))
                    <div class="alert alert-danger" role="alert">
                        <p class="mb-0">{{ session('error_message') }}</p>
                    </div>
                @endif

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link link-dark" href="{{ route('restaurants.show', $restaurant) }}">トップ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark" href="#">予約</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white nagoyameshi-bg" aria-current="page" href="{{ route('restaurants.reviews.index', $restaurant) }}">レビュー</a>
                    </li>
                </ul>

                @foreach ($reviews as $review)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <div>
                                {{ $review->user->name }}さん
                            </div>
                            @if ($review->user_id === Auth::id())
                                <div>
                                    <a href="{{ route('restaurants.reviews.edit', [$restaurant, $review]) }}" class="me-2">編集</a>
                                    <a href="#" class="link-secondary" data-bs-toggle="modal" data-bs-target="#deleteReviewModal" data-review-id="{{ $review->id }}">削除</a>
                                </div>
                            @endif
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span class="nagoyameshi-star-rating" data-rate="{{ $review->score }}"></span></li>
                            <li class="list-group-item">{{ $review->content }}</li>
                        </ul>
                    </div>
                @endforeach

                <!-- 有料プランに登録していれば表示する -->
                @if (Auth::user()->subscribed('premium_plan'))
                    <div class="d-flex justify-content-center">
                        {{ $reviews->links() }}
                    </div>
                @endif

                <!-- 有料プランに登録しておらず、レビュー数が3件を超えていれば表示する -->
                @if (!Auth::user()->subscribed('premium_plan') && $restaurant->reviews()->count() > 3)
                    <div class="text-center">
                        <p>レビューを全件表示するには<a href="{{ route('subscription.create') }}">有料プランへの登録</a>が必要です。</p>
                    </div>
                @endif

                <!-- 有料プランに登録しており、レビューを投稿済みでなければ表示する -->
                @if (Auth::user()->subscribed('premium_plan') && $restaurant->reviews()->where('user_id', Auth::id())->doesntExist())
                    <div class="text-center mt-3">
                        <a href="{{ route('restaurants.reviews.create', $restaurant) }}" class="btn text-white shadow-sm w-50 nagoyameshi-btn">レビューを投稿する</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection