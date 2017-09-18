@extends('layouts.app')

@push('scripts')
<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
<script src="{{ mix('/js/freeboard/create.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/freeboard">자유게시판</a></li>
        <li><a href="/freeboard/{{ $article->id }}">{{ $article->subject }}</a></li>
        <li class="active">글 수정</li>
    </ol>

    <form if="fm" method="POST" action="/freeboard/{{ $article->id }}">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="form-group">
                    <label>분 류</label>
                    <select class="form-control input-sm" name="category" required>
                        <option @if ($article->category == '일상') selected="selected" @endif>일상</option>
                        <option @if ($article->category == '유머') selected="selected" @endif>유머</option>
                        <option @if ($article->category == '치과경영') selected="selected" @endif>치과경영</option>
                        <option @if ($article->category == '의료윤리') selected="selected" @endif>의료윤리</option>
                        <option @if ($article->category == '의료사고') selected="selected" @endif>의료사고</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>제 목</label>
                    <input type="text" class="form-control input-sm" name="subject" value="{{ $article->subject }}" required>
                </div>
                <div class="form-group">
                    <label>내 용</label>
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