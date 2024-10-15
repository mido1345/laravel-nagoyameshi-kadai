@extends('layouts.app')

@section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xxl-9 col-xl-10 col-lg-11">
                <h1 class="mb-4 text-center">会員一覧</h1>

                <div class="d-flex justify-content-between align-items-end">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="nagoyameshi-admin-search-box mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="氏名・フリガナで検索" name="keyword" value="{{ $keyword }}">
                            <button type="submit" class="btn text-white shadow-sm nagoyameshi-btn">検索</button>
                        </div>
                    </form>
                </div>

                <div>
                    <p class="mb-0">計{{ number_format($total) }}件</p>
                </div>


                <table class="table table-hover nagoyameshi-users-table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">氏名</th>
                            <th scope="col">フリガナ</th>
                            <th scope="col">メールアドレス</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->kana }}</td>
                                <td>{{ $user->email }}</td>
                                <td><a href="{{ route('admin.users.show', $user) }}">詳細</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection