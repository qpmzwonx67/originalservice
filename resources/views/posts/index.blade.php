@extends('layouts.app')

@section('content')
<div>
    @foreach ($posts as $post)
        {!! link_to_route('posts.show', '詳細', ['post' => $post->id]) !!}
         <img src={{$post->photo}} width=300 height=auto>
    @endforeach
</div>



    
@endsection