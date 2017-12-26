@extends('layouts.app')

@push('styles')
  <link href="{{ mix('css/wysiwyg.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    let attachments = [
        @foreach ($article->attachments as $attachment)
        { name : "{{ basename($attachment->name) }}", size : {{ $attachment->size }}, type : "{{ $attachment->mime }}", _id: {{ $attachment->id }} },
        @endforeach
    ];
</script>
<script src="{{ mix('/js/edit.js') }}"></script>
@endpush

@section('content')
  <div id="content" class="container mt-5">
    <summary-articles></summary-articles>
  
    <div class="row no-gutters mt-3">
      <div class="col">
        <form id="fm" class="content-box p-3" action="/{{ dirname(Request::path()) }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          {{ method_field('PATCH') }}
          <div class="row">
            <div class="col-4 col-sm-2">
              <select class="form-control form-control-sm" name="category" required>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" @if ($category->id == $article->category_id) selected="selected"@endif>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-8 col-sm-10">
              <input type="text" class="form-control form-control-sm" id="subject" name="subject" placeholder="제목을 입력해주세요" value="{{ $article->subject }}" required>
            </div>
          </div>
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="10" id="article" name="content" required>
                {!! $article->content !!}
            </textarea>
          </div>
          <div class="form-group">
            <label>첨부파일</label>
            <div id="attachment" class="dropzone">
              <div class="dz-default dz-message"></div>
            </div>
            <div>
              여기에 파일을 끌어 놓거나, 클릭하세요. <span class="small text-success">( 최대 크기 : 2MB )</span>
            </div>
          </div>
          <div class="text-center">
            <input class="btn btn-primary btn-sm" type="submit" value="수정">
            <a class="btn btn-secondary btn-sm" href="/{{ dirname(Request::path()) }}" role="button">취소</a>
          </div>
        </form>
      </div>
      <div class="ml-3 d-none d-sm-block" style="width:300px">
        @include('layouts.right_banner')
      </div>
    </div>
  </div>

  {{--  @component('layouts.edit')
    @slot('board')
      공지사항
    @endslot
    @slot('subject')
      {{ $article->subject }}
    @endslot

    <div class="form-horizontal">
      <div class="form-group">
        <div class="col-xs-4 col-sm-2">
          <select class="form-control input-sm" name="category" required>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" @if ($category->id == $article->category_id) selected="selected"@endif>{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-xs-8 col-sm-10">
          <input type="text" class="form-control input-sm" name="subject" placeholder="제목을 입력해주세요" value="{{ $article->subject }}" required>
        </div>
      </div>
    </div>

    <div class="form-group">
      <textarea class="form-control input-sm" rows="4" name="content" id="content" required>
        {!! $article->content !!}
      </textarea>
    </div>
    <div class="form-group">
      <label>첨부파일</label>
      <div id="attachment" class="dropzone">
            <div class="dz-default dz-message"></div>
      </div>
      <div>
        여기에 파일을 끌어 놓거나, 클릭하세요. <span class="small text-success">( 최대 크기 : 2MB )</span>
      </div>
    </div>
  @endcomponent  --}}
@endsection