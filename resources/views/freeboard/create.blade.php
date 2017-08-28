@extends('layouts.app')

@section('content')
<script src="https://cdn.ckeditor.com/4.7.2/standard/ckeditor.js"></script>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="/freeboard">자유게시판</a></li>
        <li class="active">글 쓰기</li>
    </ol>

    <form method="POST" action="/freeboard">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>제 목</label>
                    <input type="text" class="form-control input-sm" name="subject" placeholder="제목을 입력해주세요" required>
                </div>
                <div class="form-group">
                    <label>내용</label>
                    <textarea class="form-control input-sm" rows="4" name="content" id="content" required></textarea>
                </div>
            </div>
            <div class="panel-footer text-right">
                <input class="btn btn-primary btn-sm" type="submit" value="등록">
                <a class="btn btn-default btn-sm" href="/freeboard" role="button">취소</a>
            </div>
        </div>
    </form>
</div>
@endsection