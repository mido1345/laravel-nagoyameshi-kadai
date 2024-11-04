@extends('layouts.app')

@section('content')
    <div class="container nagoyameshi-container pb-5">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <nav class="my-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
                        <li class="breadcrumb-item active" aria-current="page">利用規約</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">利用規約</h1>

                <div class="mb-4 nagoyameshi-terms">
                    {!! $term->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection