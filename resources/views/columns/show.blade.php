@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('/js/show.js') }}"></script>
@endpush

@section('content')
<div id="content" class="container my-5">
  <summary-articles type="columns"></summary-articles>

  <div class="row no-gutters mt-3">
    <div class="col">
      <div class="content-box">
        <div class="p-3" style="border-bottom: 2px solid #e9ecef">
          <h4><span class="text-success">[{{ $article->category->name }}]</span> {{ $article->subject }}</h4>
          <div class="row">
            <div class="col-sm-4 col-md-6">
              <span class="text-primary">작성자 :</span>
              {{ mb_substr($article->user->name, 0, 1).str_repeat('*', mb_strlen($article->user->name)-2).mb_substr($article->user->name, -1) }}
            </div>
            <div class="col-sm-4 col-md-3 text-right">
              <span class="text-primary">조회수 :</span> {{ number_format($article->hits) }}
            </div>
            <div class="col-sm-4 col-md-3 text-right">
              <span title="{{ $article->created_at }}">
                {{ $article->created_at->format('Y.m.d') }}
              </span>
            </div>
          </div>
        </div>

        <div class="p-3">
          {{-- 첨부파일 영역  --}}
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

          {{--  본문  --}}
          <p>{!! $article->content !!}</p>
          <hr>

          {{--  댓글 작성  --}}
          @auth
          <div>
            <h6 class="font-weight-bold">플러스 댓글 남기기</h6>
            <textarea class="form-control input-sm mb-1" rows="3" id="comment-content" required></textarea>
            <div class="text-right">
              <button class="btn btn-sm btn-primary" @click="comment">등록</button>
            </div>
          </div>
          <hr>
          @endif
          {{--  댓글 목록  --}}
          <comments v-for="comment in comments" :key="comment.id" :comment="comment" :user="user" @reload="loadComments"></comments>

          {{--  하단 버튼 (목록, 수정, 삭제)  --}}
          <div class="row">
            <div class="col-6">
              @if ($writable)
              <a class="btn btn-sm btn-primary" href="{{ route('columns.create') }}" role="button">
                <i class="fas fa-pencil-alt" aria-hidden="true"></i> 글쓰기
              </a>
              <a class="btn btn-sm btn-secondary" href="/{{ Request::path() }}/edit" role="button">
                <i class="fas fa-edit" aria-hidden="true"></i> 수정
              </a>
              <a class="btn btn-sm btn-danger" href="/{{ Request::path() }}" role="button" @click.prevent="destory">
                <i class="fas fa-trash" aria-hidden="true"></i> 삭제
              </a>
              @endif
            </div>

            <div class="col-6 text-right">
              <a class="btn btn-sm btn-primary" href="{{ route('columns.index') }}" role="button">
                <i class="fas fa-list" aria-hidden="true"></i> 목록
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ml-3 d-none d-sm-block" style="width:300px">
      @include('layouts.right_banner')
    </div>
  </div>
</div>
@endsection