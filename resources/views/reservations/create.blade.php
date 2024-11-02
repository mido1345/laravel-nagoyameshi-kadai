@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <script>
        // JavaScriptに変数（その店舗の定休日のday_indexカラム値の配列）を渡す
        const restaurantRegularHolidays = @json($restaurant->regular_holidays()->pluck('day_index'));
    </script>
    <script src="{{ asset('/js/flatpickr.js') }}"></script>
@endpush

@section('content')
    <div class="container nagoyameshi-container pb-5">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <nav class="my-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.index') }}">店舗一覧</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $restaurant) }}">店舗詳細</a></li>
                        <li class="breadcrumb-item active" aria-current="page">予約</li>
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
                        <a class="nav-link active text-white nagoyameshi-bg" aria-current="page" href="{{ route('restaurants.reservations.create', $restaurant) }}">予約</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dark" href="{{ route('restaurants.reviews.index', $restaurant) }}">レビュー</a>
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

                <form method="POST" action="{{ route('restaurants.reservations.store', $restaurant) }}">
                    @csrf

                    <input type="hidden" name="restaurant_id", value="{{ $restaurant->id }}">
                    <div class="form-group row mb-3">
                        <label for="reservation_date" class="col-md-5 col-form-label text-md-left fw-bold">予約日</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="reservation_time" class="col-md-5 col-form-label text-md-left fw-bold">時間</label>

                        <div class="col-md-7">
                            <select class="form-control form-select" id="reservation_time" name="reservation_time">
                                <option value="" hidden>選択してください</option>
                                @for ($i = 0; $i <= (strtotime($restaurant->closing_time) - strtotime($restaurant->opening_time)) / 1800; $i++)
                                    {{ $reservation_time = date('H:i', strtotime($restaurant->opening_time . '+' . $i * 30 . 'minute')) }}
                                    @if ($reservation_time == old('reservation_time'))
                                        <option value="{{ $reservation_time }}" selected>{{ $reservation_time }}</option>
                                    @else
                                        <option value="{{ $reservation_time }}">{{ $reservation_time }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label for="number_of_people" class="col-md-5 col-form-label text-md-left fw-bold">人数</label>

                        <div class="col-md-7">
                            <select class="form-select" id="number_of_people" name="number_of_people">
                                <option value="" hidden>選択してください</option>
                                @for ($i = 1; $i <=50; $i++)
                                    <option value="{{ $i }}">{{ $i }}名</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-center mb-4">
                        <button type="submit" class="btn text-white shadow-sm w-50 nagoyameshi-btn">予約する</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection