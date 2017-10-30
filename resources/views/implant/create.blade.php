@extends('layouts.app')

@push('scripts')
<script src="{{ mix('/js/freeboard/create.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/implant">임플란트 매칭</a></li>
        <li class="active">문의하기</li>
    </ol>

    @if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
        <p>{!! nl2br(e($error)) !!}</p>
    @endforeach
    </div>
    @endif

    <form id="fm" method="POST" action="/freeboard" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>제 목</label>
                    <input type="text" class="form-control input-sm" name="subject" placeholder="제목을 입력해주세요" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control input-sm" rows="4" name="content" id="content" required></textarea>
                </div>
                <div class="form-group">
                    <label>첨부파일</label>
                    <div id="attachment" class="dropzone">
                        <div class="dz-default dz-message">
                            여기에 파일을 끌어 놓거나, 클릭하세요.
                            <div class="small text-success">( 최대 크기 : 2MB )</div>
                        </div>
                    </div>
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