@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('/js/reservation-modal.js') }}"></script>
@endpush

@section('content')
    <!-- 予約のキャンセル用モーダル -->
    <div class="modal fade" id="cancelReservationModal" tabindex="-1" aria-labelledby="cancelReservationModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelReservationModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-footer">
                    <form action="" method="post" name="cancelReservationForm">
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
                        <li class="breadcrumb-item active" aria-current="page">予約一覧</li>
                    </ol>
                </nav>

                <h1 class="mb-3 text-center">予約一覧</h1>

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

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">店舗名</th>
                            <th scope="col">予約日時</th>
                            <th scope="col">人数</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $reservation)
                            <tr>
                                <td>
                                    <a href="{{ route('restaurants.show', $reservation->restaurant) }}">
                                        {{ $reservation->restaurant->name }}
                                    </a>
                                </td>
                                <td>{{ date('Y年n月j日 G時i分', strtotime($reservation->reserved_datetime)) }}</td>
                                <td>{{ $reservation->number_of_people }}名</td>
                                <td>
                                    @if ($reservation->reserved_datetime > now())
                                        <a href="#" class="link-secondary" data-bs-toggle="modal" data-bs-target="#cancelReservationModal" data-reservation-id="{{ $reservation->id }}" data-restaurant-name="{{ $reservation->restaurant->name }}">キャンセル</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection