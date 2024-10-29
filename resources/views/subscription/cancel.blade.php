@extends('layouts.app')

@section('content')
    <div class="container nagoyameshi-container pb-5">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <nav class="my-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
                        <li class="breadcrumb-item active" aria-current="page">有料プラン解約</li>
                    </ol>
                </nav>

                <h1 class="mb-3 text-center">有料プラン解約</h1>

                <p>有料プランを解約すると以下の特典を受けられなくなります。本当に解約してもよろしいですか？</p>

                <div class="card mb-4">
                    <div class="card-header text-center">
                        有料プランの内容
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">・当日の2時間前までならいつでも予約可能</li>
                        <li class="list-group-item">・店舗をお好きなだけお気に入りに追加可能</li>
                        <li class="list-group-item">・レビューを全件閲覧可能</li>
                        <li class="list-group-item">・レビューを投稿可能</li>
                        <li class="list-group-item">・月額たったの300円</li>
                    </ul>
                </div>

                <form id="cardForm" action="{{ route('subscription.destroy') }}" method="post">
                    @csrf
                    @method('delete')
                    <div class="form-group d-flex justify-content-center">
                        <button class="btn text-white shadow-sm w-50 nagoyameshi-btn-danger">解約</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection