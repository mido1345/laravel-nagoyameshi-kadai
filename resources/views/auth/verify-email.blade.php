@extends('layouts.app')

@section('content')
<div class="container py-4 nagoyameshi-container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <h1 class="mb-4 text-center">会員登録を完了してください</h1>

            @if (session('resent'))
                <div class="alert alert-info" role="alert">
                    <p class="mb-0">新しいURLをあなたのメールアドレスに送信しました。</p>
                </div>
            @endif

            <p>現在、仮会員の状態です。メールに記載されている「メールアドレス確認」ボタンをクリックして会員登録の手続きを完了してください。</p>

            <p>もしメールが届いていない場合は、以下のボタンをクリックしてメールを再送信してください。</p>

            <form class="text-center" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn text-white nagoyameshi-btn">確認メールを再送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection
