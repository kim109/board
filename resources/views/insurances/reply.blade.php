@extends('layouts.app')

@push('styles')
  <link href="{{ mix('css/wysiwyg.css') }}" rel="stylesheet">
@endpush

@push('scripts')
  <script src="{{ mix('/js/insurances/reply.js') }}"></script>
@endpush

@section('content')
<div id="content" class="container my-5">
  <div class="row no-gutters mt-3">
    <div class="col">
      <div class="content-box mb-5">
        <div class="p-3" style="border-bottom: 2px solid #e9ecef">
          <h4><span class="text-success">[{{ $article->category->name }}]</span> {{ $article->subject }}</h4>
          <div class="row">
            <div class="col-sm-4 col-md-6">
            <span class="text-primary">작성자 :</span> {{ $article->user->name }}
            </div>
            <div class="col-sm-4 col-md-3 text-right">
            <span class="text-primary">조회수 :</span> {{ number_format($article->hits) }}
            </div>
            <div class="col-sm-4 col-md-3 text-right">
            <span title="{{ $article->created_at }}">
                @if ($article->created_at > date('Y-m-d'))
                {{ $article->created_at->format('H:i') }}
                @else
                {{ $article->created_at->format('Y/m/d') }}
                @endif
            </span>
            </div>
          </div>
          </div>
          <div class="p-3">
            @if (count($article->attachments) > 0)
            <ul class="list-unstyled">
              @foreach ($article->attachments as $attachment)
              <li class="text-right">
                <a href="/attachments/{{ $attachment->id }}/{{ md5($attachment->path) }}">
                <i class="far @if (preg_match('/^image\//', $attachment->mime) === 1) fa-file-image @else fa-file @endif" aria-hidden="true"></i>
                {{ basename($attachment->name) }}
                </a>
              </li>
              @endforeach
            </ul>
            @endif

           <p>{!! $article->content !!}</p>
        </div>
      </div>

      <h5>답변하기</h5>
      <form id="fm" class="content-box p-3" action="/{{ dirname(Request::path()) }}/reply" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
          <textarea class="form-control form-control-sm" rows="10" id="article" name="content" required></textarea>
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