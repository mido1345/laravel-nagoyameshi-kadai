@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('/js/category-modal.js') }}"></script>
@endpush

@section('content')
    <!-- カテゴリの登録用モーダル -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">カテゴリの登録</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn text-white shadow-sm nagoyameshi-btn">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- カテゴリの編集用モーダル -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">カテゴリの編集</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <form action="" method="post" name="editCategoryForm">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn text-white shadow-sm nagoyameshi-btn">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- カテゴリの削除用モーダル -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-footer">
                    <form action="" method="post" name="deleteCategoryForm">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn text-white shadow-sm nagoyameshi-btn-danger">削除</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">
                <h1 class="mb-4 text-center">カテゴリ一覧</h1>

                <div class="d-flex justify-content-between align-items-end flex-wrap">
                    <form method="GET" action="{{ route('admin.categories.index') }}" class="nagoyameshi-admin-search-box mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="カテゴリ名で検索" name="keyword" value="{{ $keyword }}">
                            <button type="submit" class="btn text-white shadow-sm nagoyameshi-btn">検索</button>
                        </div>
                    </form>

                    <a href="#" class="btn text-white shadow-sm mb-3 nagoyameshi-btn" data-bs-toggle="modal" data-bs-target="#createCategoryModal">＋ 新規登録</a>
                </div>

                @if (session('flash_message'))
                    <div class="alert alert-info" role="alert">
                        <p class="mb-0">{{ session('flash_message') }}</p>
                    </div>
                @endif

                <div>
                    <p class="mb-0">計{{ number_format($total) }}件</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">カテゴリ名</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}">編集</a>
                                </td>
                                <td>
                                    <a href="#" class="link-secondary" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}">削除</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection