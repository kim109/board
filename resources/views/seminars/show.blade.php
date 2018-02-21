@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('/js/show.js') }}"></script>
@endpush

@section('content')
<div id="content" class="container my-5">
  <summary-articles></summary-articles>

  <div class="row no-gutters mt-3">
    <div class="col">
      <div class="content-box">
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
          <hr>
          
          @if (Auth::check()) 
          <div>
            <h6 class="font-weight-bold">플러스 댓글 남기기</h6>
            <textarea class="form-control input-sm mb-1" rows="3" id="comment-content" required></textarea>
            <div class="text-right">
              <button class="btn btn-sm btn-primary" @click="comment">등록</button>
            </div>
          </div>
          <hr>
          @endif

          <comments v-for="comment in comments" :key="comment.id" :comment="comment" :user="user" @reload="loadComments"></comments>

          <div class="text-center">
            <a class="btn btn-sm btn-primary" href="{{ $list }}" role="button">
              <i class="fas fa-list" aria-hidden="true"></i> 목록
            </a>
      
            @if ($writable)
            <a class="btn btn-sm btn-secondary" href="/{{ Request::path() }}/edit" role="button">
              <i class="fas fa-edit" aria-hidden="true"></i> 수정
            </a>
            <a class="btn btn-sm btn-danger" href="/{{ Request::path() }}" role="button" @click.prevent="destory">
              <i class="fas fa-trash" aria-hidden="true"></i> 삭제
            </a>
            @endif
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