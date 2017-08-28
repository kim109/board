@extends('layouts.app')

@section('content')
<script src="https://cdn.ckeditor.com/4.7.2/standard/ckeditor.js"></script>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="/freeboard">자유게시판</a></li>
        <li><a href="/freeboard/{{ $article->id }}">{{ $article->subject }}</a></li>
        <li class="active">글 수정</li>
    </ol>

    <form method="POST" action="/freeboard/{{ $article->id }}">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="form-group">
                    <label>제 목</label>
                    <input type="text" class="form-control input-sm" name="subject" value="{{ $article->subject }}" required>
                </div>
                <div class="form-group">
                    <label>내용</label>
                    <textarea class="form-control input-sm" rows="4" name="content" id="content" required>
                        {!! $article->content !!}
                    </textarea>
                </div>
            </div>
            <div class="panel-footer text-right">
                <input class="btn btn-primary btn-sm" type="submit" value="수정">
                <a class="btn btn-default btn-sm" href="/freeboard/{{ $article->id }}" role="button">취소</a>
            </div>
        </div>
    </form>
</div>
@endsection