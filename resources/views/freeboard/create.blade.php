@extends('layouts.app')

@push('scripts')
<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
<script src="{{ mix('/js/freeboard/create.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/freeboard">자유게시판</a></li>
        <li class="active">글 쓰기</li>
    </ol>

    <form id="fm" method="POST" action="/freeboard" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>분 류</label>
                    <select class="form-control input-sm" name="category" required>
                        <option>일상</option>
                        <option>유머</option>
                        <option>치과경영</option>
                        <option>의료윤리</option>
                        <option>의료사고</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>제 목</label>
                    <input type="text" class="form-control input-sm" name="subject" placeholder="제목을 입력해주세요" required>
                </div>
                <div class="form-group">
                    <label>내 용</label>
                    <textarea class="form-control input-sm" rows="4" name="content" id="content" required></textarea>
                </div>
                <div class="form-group">
                    <label>첨부파일</label>
                    <input type="file" name="attach">
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