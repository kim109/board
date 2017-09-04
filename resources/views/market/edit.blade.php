@extends('layouts.app')

@section('content')
<script src="https://cdn.ckeditor.com/4.7.2/standard/ckeditor.js"></script>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="/market">중고장터</a></li>
        <li><a href="/market/{{ $article->id }}">{{ $article->subject }}</a></li>
        <li class="active">글 수정</li>
    </ol>

    <form method="POST" action="/market/{{ $article->id }}" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="form-group">
                    <label>제 목</label>
                    <input type="text" class="form-control input-sm" name="subject" value="{{ $article->subject }}" required>
                </div>
                <div class="form-group">
                    <label>제품 이미지</label>
                    <input type="file" name="attach" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label>상품 가격</label>
                    <input type="number" class="form-control input-sm" name="price" min="1" value="{{ $article->price }}" required>
                </div>
                <div class="form-group">
                    <label>상세 설명</label>
                    <textarea class="form-control input-sm" rows="4" name="content" id="content" required>{{ $article->description }}</textarea>
                </div>
            </div>
            <div class="panel-footer text-right">
                <input class="btn btn-primary btn-sm" type="submit" value="수정">
                <a class="btn btn-default btn-sm" href="/market/{{ $article->id }}" role="button">취소</a>
            </div>
        </div>
    </form>
</div>
@endsection