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

    <h4>{{ $article->subject }}</h4>
    <div class="small text-right">
        <span class="text-primary"><i class="fa fa-user" aria-hidden="true"></i> {{ $article->user->name }}</span> /
        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $article->created_at }}</span> /
        <span>조회수 : {{ $article->hits }}</span>
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

    <div class="well well-sm">
        @foreach ($article->comments as $comment)
            <div class="comment">
                <div class='comment-title'>
                    <div class="pull-right small">
                        {{ $comment->created_at }}
                        @if ($comment->user_id == Auth::id())
                            /
                            <a href="/freeboard/{{ $article->id }}/comments/{{ $comment->id }}" class="comment-delete">
                                <i class="fa fa-trash" aria-hidden="true"></i> 삭제
                            </a>
                        @endif
                    </div>
                    <strong>{{ $comment->user->name }}</strong>
                </div>
                <div class="comment-body">{!! $comment->content !!}</div>
            </div>
        @endforeach

        <form method="POST" action="/freeboard/{{ $article->id }}/comment">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-11">
                    <textarea class="form-control input-sm" rows="2" name="content" required></textarea>
                </div>
                <div class="col-sm-1 text-right" style="margin-top:1em;"><input class="btn btn-primary btn-sm" type="submit" value="등록"></div>
            </div>
        </form>
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
        <button class="btn btn-danger btn-sm" role="button" onclick="document.getElementById('delete').submit();">
            <i class="fa fa-trash" aria-hidden="true"></i> 삭제
        </button>
        <form method="POST" action="/freeboard/{{ $article->id }}" id="delete">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
        @endif
    </div>
</div>
@endsection