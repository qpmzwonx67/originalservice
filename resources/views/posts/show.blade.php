@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <h3>写真の詳細ページ</h3>
        
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <img src={{$post->photo}}>
                </div>
                
                <div class="col-sm-6">
                    <ul class="list-unstyled">
                        <li>ニックネーム：{{$child->nickname}}</li>
                        <li>撮影時の年齢：{{$post->age}}歳</li>
                        <li>@if($child->gender == 1)
                             性別：男の子
                            @elseif($child->gender == 2)
                             性別：女の子
                            @else
                             性別：未回答
                            @endif
                             </li>
                </div>
                
                @if (\Auth::id() === $post->user_id) 
                {!! link_to_route('posts.edit', 'この投稿を編集', ['post' => $post->id], ['class' => 'btn btn-light']) !!}
                {!! Form::model($post, ['route' => ['posts.destroy', $post->id], 'method' => 'delete']) !!}
                {!! Form::submit('削除', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
                @endif
                    

    @endif
@endsection