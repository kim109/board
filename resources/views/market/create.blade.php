@extends('layouts.app')

@push('scripts')
<script src="{{ mix('/js/market/create.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li><a href="/market">중고장터</a></li>
        <li class="active">등록하기</li>
    </ol>

    <form id="fm" method="POST" action="/market" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>제 목</label>
                    <input type="text" class="form-control input-sm" name="subject" placeholder="제목을 입력해주세요" required>
                </div>
                <div class="form-group">
                    <label>상품 가격</label>
                    <input type="number" class="form-control input-sm" name="price" min="1" placeholder=" 상품 가격을 입력해주세요" required>
                </div>
                <div class="form-group">
                    <label>상세 설명</label>
                    <textarea class="form-control input-sm" rows="4" name="content" id="content" required></textarea>
                </div>
                <div class="form-group">
                    <label>상품 이미지</label>
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
                <a class="btn btn-default btn-sm" href="/market" role="button">취소</a>
            </div>
        </div>
    </form>
</div>
@endsection