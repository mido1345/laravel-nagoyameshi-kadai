@extends('layouts.app')

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">

                <h1 class="mb-4 text-center">利用規約</h1>

                <div class="d-flex justify-content-end align-items-end mb-3">
                    <div>
                        <a href="{{ route('admin.terms.edit', $term) }}" class="me-2">編集</a>
                    </div>
                </div>

                @if (session('flash_message'))
                    <div class="alert alert-info" role="alert">
                        <p class="mb-0">{{ session('flash_message') }}</p>
                    </div>
                @endif



                <div class="container mb-4 nagoyameshi-terms">
                    {!! $term->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection