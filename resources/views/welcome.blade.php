@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <p>写真の掲載部分</p>                   
                </div>
                <div class="col-sm-4 border-left">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="性別、年齢" aria-label="検索キーワード" aria-describedby="basic-addon1">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">検索</button>
                        </div>
                    </div>

                    <ul class="list-unstyled">
                        <li><a href="#">写真を投稿する</a></li>
                        <li><a href="#">投稿した写真をみる</a></li>
                        <li><a href="#">お気に入り一覧</a></li>
                        <li><a href="#">限定公開一覧</a></li>
                        <li> {!! link_to_route('children.create', '子どもの情報登録', [], []) !!}</li>
                    @foreach ($children as $child)
                        <li> {!! link_to_route('children.edit', '子どもの登録情報の修正('.$child->nickname.')', ['child'=>$child->id]) !!}</li>
                    @endforeach
                    </ul>

                </div>
            </div>
        </div>

    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Child's Safariへようこそ</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', '新規登録はこちら', [], ['class' => 'btn btn-lg btn-primary']) !!}
                
            </div>
        </div>
    @endif
@endsection