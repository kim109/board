@extends('layouts.app')

@push('scripts')
<script src="{{ mix('/js/create.js') }}"></script>
@endpush

@section('content')
  @component('layouts.create')
    @slot('board')
      자유게시판
    @endslot

    <div class="form-group">
      <label>분 류</label>
      <select class="form-control input-sm" name="category" required>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
    </div>
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
  @endcomponent
@endsection