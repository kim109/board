@extends('layouts.app')

@push('scripts')
<script>
    let article_id = {{ $article->id }};
    let attachments = [
        @foreach ($article->attachments as $attachment)
        { name : "{{ basename($attachment->name) }}", size : {{ $attachment->size }}, mime : "{{ basename($attachment->mime) }}", _id: {{ $attachment->id }} },
        @endforeach
    ];
</script>
<script src="{{ mix('/js/freeboard/edit.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/freeboard">자유게시판</a></li>
        <li><a href="/freeboard/{{ $article->id }}">{{ $article->subject }}</a></li>
        <li class="active">글 수정</li>
    </ol>

    <form if="fm" method="POST" action="/freeboard/{{ $article->id }}" enctype="multipart/form-data">
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
                    <input type="text" class="form-control input-sm" name="subject" placeholder="제목을 입력해주세요" value="{{ $article->subject }}" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control input-sm" rows="4" name="content" id="content" required>
                        {!! $article->content !!}
                    </textarea>
                </div>
                <div class="form-group">
                    <label>첨부파일</label>
                    @if ($article->attachments->count() > 0)
                    <div>
                        @foreach ($article->attachments as $attachment)
                        {{ basename($attachment->name) }} -
                        <a href="/attachments/{{ $attachment->id }}" class="attachment-delete">
                            <i class="fa fa-trash" aria-hidden="true"></i> 삭제
                        </a>
                        @endforeach
                    </div>
                    @endif
                    <div id="attachment" class="dropzone">
                        <div class="dz-default dz-message">
                            여기에 파일을 끌어 놓거나, 클릭하세요.
                            <div class="small text-success">( 최대 크기 : 2MB )</div>
                        </div>
                    </div>
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