@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/freeboard">자유게시판</a></li>
        <li class="active">{{ $article->subject }}</li>
    </ol>

    <h4>{{ $article->subject }}</h4>
    <div class="small text-right">
        <span class="text-primary"><i class="fa fa-user" aria-hidden="true"></i> {{ $article->user->name }}</span> /
        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $article->created_at }}</span> /
        <span>조회수 : {{ $article->hits }}</span>
    </div>
    <hr>
    <p>{{ $article->content }}</p>
    <hr>
    <div class="well well-lg">
        <form method="POST" action="/freeboard/{{ $article->id }}/comment">
            <textarea class="form-control input-sm" rows="2" name="content" required></textarea>
            <div class="text-right" style="margin-top:1em;"><input class="btn btn-primary btn-sm" type="submit" value="등록"></div>
        </from>
    </div>
    <hr>
    <div class="text-center">
        <a class="btn btn-default btn-sm" href="/freeboard" role="button">목록</a>
    </div>
</div>
@endsection