@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1>お子さまの登録</h1>
    </div>

    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            {!! Form::model($child,['route' => 'children.store']) !!}
                <div class="form-group">
                    {!! Form::label('nickname', 'ニックネーム') !!}
                    {!! Form::text('nickname', old('nickname'), ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('gender', '性別') !!}<br>
                    {{ Form::radio('gender','1')}}男の子
                    {{ Form::radio('gender','2')}}女の子
                    {{ Form::radio('gender','3')}}未回答
                    
                </div>

                <div class="form-group">
                    {!! Form::label('birthday', '生年月日') !!}<br>
                    <?php $today = \Carbon\Carbon::now(); ?>
                    {{Form::selectRange('year', 2005, $today->year,'', ['placeholder' => ''])}}年
                    {{Form::selectRange('month', 1, 12,'', ['placeholder' => ''])}}月
                    {{Form::selectRange('day', 1, 31, '', ['placeholder' => ''])}}日
                </div>

                {!! Form::submit('登録', ['class' => 'btn btn-primary btn-block']) !!}

            {!! Form::close() !!}
        </div>
    </div>

@endsection