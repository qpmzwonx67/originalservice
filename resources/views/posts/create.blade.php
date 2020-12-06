@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1>写真の投稿</h1>
    </div>

    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            {!! Form::model($post,['route' => 'posts.store','enctype'=>'multipart/form-data']) !!}
            
                <div class ="form-group">
                    {!!Form::label('photo','写真')!!}<br>
                    {!!Form::file('photo')!!}
                </div>
                
                <div class="form-group">
                    {!! Form::label('child_id', 'ニックネーム') !!}<br>
                    {{ Form::select('child_id',$nickname,old('nickname'),['placeholder'=>'選択してください']) }}

                </div>

                <div class="form-group">
                    {!! Form::label('age', '撮影時の年齢') !!}<br>
                    {{Form::selectRange('age', 0, 15,'', ['placeholder' => ''])}}歳
                </div>

                <div class="form-group">
                    {!! Form::label('open', '公開の選択') !!}<br>
                    {{ Form::radio('open','1')}}公開<br>
                    {{ Form::radio('open','2')}}限定公開
                </div>
                
                <p>限定公開の場合は、閲覧を許可する方のメールアドレスを記載してください。</p>
                
                <div class="form-group">
                    {!! Form::label('allowemail1', 'Email1') !!}
                    {!! Form::email('allowemail1', old('allowemail1'), ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('allowemail2', 'Email2') !!}
                    {!! Form::email('allowemail2', old('allowemail2'), ['class' => 'form-control']) !!}
                </div>

                {!! Form::submit('投稿', ['class' => 'btn btn-primary w-50']) !!}
                   
            {!! Form::close() !!}
               
                <button type ="button" class='btn btn-primary btn-group' onclick="history.back()">戻る</button>
              
            </div>
        </div>
    </div>


@endsection