@extends('layouts.app')

@push('stylesheets')
</style>
@endpush

@push('scripts')
<script src="{{ mix('/js/notice/create.js') }}"></script>
@endpush

@section('content')
  @component('layouts.create')
    @slot('board')
      공지사항
    @endslot

    <div class="form-horizontal">
      <div class="form-group">
        <div class="col-xs-4 col-sm-2 col-lg-1">
          <select class="form-control input-sm" name="category" required>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-xs-8 col-sm-10 col-lg-11">
          <input type="text" class="form-control input-sm" id="subject" name="subject" placeholder="제목을 입력해주세요" required>
        </div>
      </div>
    </div>
    <div class="form-group">
      <textarea class="form-control input-sm" rows="4" id="content" name="content" required></textarea>
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