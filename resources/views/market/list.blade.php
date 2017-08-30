@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="active">중고장터</li>
    </ol>

    <p class="text-right">
        <a class="btn btn-primary btn-sm" href="market/create" role="button">
            <i class="fa fa-pencil" aria-hidden="true"></i> 등록하기
        </a>
    </p>

    <div class="list-group">
        @forelse ($articles as $article)
            <div class="list-group-item">
                <div class="media">
                <div class="media-left">
                    <div style="width: 140px;">
                        <img class="media-object img-rounded" src="market/{{ $article->id }}/thumbnail" style="height:80px;">
                    </div>
                </div>
                <div class="media-body">
                    <a href="market/{{ $article->id }}">
                        <h4 class="media-heading">{{ $article->subject }}</h4>
                    </a>
                    작성자 : {{ $article->user->name }} |
                    등록일시 : {{ $article->created_at }} |
                    조회수 : {{ $article->hits }}
                </div>
                <div class="media-right">
                    <a href="market/{{ $article->id }}">
                        <strong>&#8361;{{ number_format($article->price) }}</strong>
                    </a>
                </div>
                </div>
            </div>
        @empty
        <div class="list-group-item">
            등록된 게시물이 없습니다.
        </div>
        @endforelse
    </div>
</div>
@endsection