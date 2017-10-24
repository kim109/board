@extends('layouts.app')

@push('scripts')
    <script src="{{ mix('/js/show.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/market">중고장터</a></li>
        <li class="active">{{ $article->subject }}</li>
    </ol>

    <h4>{{ $article->subject }}</h4>
    <div class="small text-right">
        <span class="text-primary"><i class="fa fa-user" aria-hidden="true"></i> {{ $article->user->name }}</span> /
        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $article->created_at }}</span> /
        <span>조회수 : {{ $article->hits }}</span>
    </div>
    <hr>

    <div class="text-center">
        {{--  <img src="/thumbnail/{{ $article->attachments[0]->id }}" style="max-width: 50%;">  --}}
    </div>
    <hr>

    <strong>판매가격 &#8361;{{ number_format($article->price) }}</strong>
    <p>{!! $article->content !!}</p>
    <hr>

    <div class="well well-sm" id="comments">
        <comment v-for="comment in comments" :key="comment.id" :comment="comment" :user="user" @reload="loadComments"></comment>

        <textarea class="form-control input-sm" rows="3" id="comment-content" required></textarea>
        <div class="text-right">
            <button class="btn btn-sm btn-primary" @click="comment">등록</button>
        </div>
    </div>
    <hr>

    <div class="text-center">
        <a class="btn btn-default btn-sm" href="/market" role="button">
            <i class="fa fa-list" aria-hidden="true"></i> 목록
        </a>
        @if ($article->user_id == Auth::id())
        <a class="btn btn-default btn-sm" href="/market/{{ $article->id }}/edit" role="button">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정
        </a>
        <button class="btn btn-danger btn-sm" role="button" onclick="document.getElementById('delete').submit();">
            <i class="fa fa-trash" aria-hidden="true"></i> 삭제
        </button>
        <form method="POST" action="/market/{{ $article->id }}" id="delete">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
        @endif
    </div>
</div>
@endsection