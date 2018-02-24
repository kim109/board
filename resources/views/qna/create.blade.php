@extends('layouts.app')

@push('styles')
  <link href="{{ mix('css/wysiwyg.css') }}" rel="stylesheet">
@endpush

@push('scripts')
  <script src="{{ mix('/js/create.js') }}"></script>
@endpush

@section('content')
  <div id="content" class="container my-5">
    <summary-articles></summary-articles>

    <div class="row no-gutters mt-3">
      <div class="col">
        <form id="fm" class="content-box p-3" action="/{{ dirname(Request::path()) }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row no-gutters">
            <div class="mr-3" style="width:9em;">
              <select class="form-control form-control-sm" name="category" required>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="subject" name="subject" placeholder="제목을 입력해주세요" required>
            </div>
          </div>
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="10" id="article" name="content" required></textarea>
          </div>
          <div class="form-group">
            <label><i class="fas fa-paperclip"></i> 첨부파일</label>
            <div id="attachment" class="dropzone">
              <div class="dz-default dz-message">
                파일첨부를 원하시면 여기에 파일을 드래그하거나 클릭해주세요.
              </div>
            </div>
          </div>
          <div class="text-center">
            <input class="btn btn-primary btn-sm" type="submit" value="등록">
            <a class="btn btn-secondary btn-sm" href="/{{ dirname(Request::path()) }}" role="button">취소</a>
          </div>
        </form>
      </div>
      <div class="ml-3 d-none d-sm-block" style="width:300px">
        @include('layouts.right_banner')
      </div>
    </div>
  </div>
@endsection