@extends('layouts.app')

@push('scripts')
<script src="{{ mix('/js/show.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/freeboard">자유게시판</a></li>
        <li class="active">{{ $article->subject }}</li>
    </ol>

    <div class="row">
        <h4 class="col-sm-6">
            <span class="text-success">[{{ $article->category }}]</span>
            {{ $article->subject }}
        </h4>
        <div class="small text-right col-sm-6">
            <span class="text-primary"><i class="fa fa-user" aria-hidden="true"></i> {{ $article->user->name }}</span> /
            <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $article->created_at }}</span> /
            <span>조회수 : {{ number_format($article->hits) }}</span>
        </div>
    </div>
    <hr>

    @if ($article->attachments != null)
    <ul class="list-unstyled">
        @foreach ($article->attachments as $attachment)
        <li class="text-right">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> <a href="/attachments/{{ $attachment->id }}/{{ md5($attachment->path) }}">{{ basename($attachment->name) }}</a>
        </li>
        @endforeach
    </ul>
    @endif

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
        <a class="btn btn-default btn-sm" href="/freeboard" role="button">
            <i class="fa fa-list" aria-hidden="true"></i> 목록
        </a>
        @if ($article->user_id == Auth::id())
        <a class="btn btn-default btn-sm" href="/freeboard/{{ $article->id }}/edit" role="button">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정
        </a>
        <a id="delete" class="btn btn-default btn-sm" href="/freeboard/{{ $article->id }}" role="button">
            <i class="fa fa-trash" aria-hidden="true"></i> 삭제
        </a>
        @endif
    </div>
</div>
@endsection