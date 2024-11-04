@extends('layouts.app')

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xxl-9 col-xl-10 col-lg-11">
                <div class="row row-cols-md-3 row-cols-2 g-3 mb-5">
                    <div class="col">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">総会員数</h5>
                                <p class="card-text">{{ $total_users }}名</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">無料会員数</h5>
                                <p class="card-text">{{ $total_free_users }}名</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">有料会員数</h5>
                                <p class="card-text">{{ $total_premium_users }}名</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">店舗数</h5>
                                <p class="card-text">{{ $total_restaurants }}件</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">総予約数</h5>
                                <p class="card-text">{{ $total_reservations }}件</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">月間売上</h5>
                                <p class="card-text">{{ number_format($sales_for_this_month) }}円</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
