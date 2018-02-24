@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('/js/qna/show.js') }}"></script>
@endpush

@section('content')
<div id="content" class="container my-5">
  <summary-articles type="qna"></summary-articles>

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
                {{ $article->created_at->format('y.m.d') }}
              </span>
            </div>
          </div>
        </div>
        <div class="p-3" style="border-bottom: 2px solid #e9ecef">
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

        {{--  답변영역  --}}
        @foreach ($answers as $answer)
        <div class="p-3" style="border-bottom: 2px solid #e9ecef">
          <div class="row">
            <div class="col-8">
              <h5><strong class="text-primary">{{ $answer->user->name }}</strong>님의 답변입니다.</h5>
            </div>
            <div class="col-4 text-right">
              <span title="{{ $answer->created_at }}">
                {{ $answer->created_at->format('y.m.d') }}
              </span>
            </div>
          </div>
          <p>{!! $answer->content !!}</p>
          @if ($answer->user->id == Auth::id())
          <div class="text-right">
            <a class="btn btn-sm btn-danger" href="/{{ Request::path() }}/answers/{$answer->id}" role="button" @click.prevent="destoryAnswer">
              <i class="fas fa-trash" aria-hidden="true"></i> 답변삭제
            </a>
          </div>
          @endif
        </div>
        @endforeach


        <div class="p-3">
          @auth
          <div class="text-right">
            <a class="btn btn-primary" href="/{{ Request::path() }}/answer" role="button">
              <i class="fas fa-reply" aria-hidden="true"></i> 질문 답변하기
            </a>
          </div>
          <hr>

          <div>
            <h6 class="font-weight-bold">치카지식인 댓글</h6>
            <textarea class="form-control input-sm mb-1" rows="3" id="comment-content" required></textarea>
            <div class="text-right">
              <button class="btn btn-sm btn-primary" @click="comment">등록</button>
            </div>
          </div>
          <hr>
          @endauth

          <comments v-for="comment in comments" :key="comment.id" :comment="comment" :user="user" @reload="loadComments"></comments>

          <div class="row">
            @auth
            <div class="col-6">
              <a class="btn btn-sm btn-primary" href="{{ route('qna.create') }}" role="button">
                <i class="fas fa-pencil-alt" aria-hidden="true"></i> 지식인에 물어보기
              </a>
              @if ($writable)
              <a class="btn btn-sm btn-secondary" href="{{ route('qna.edit', ['id' => $article->id]) }}" role="button">
                <i class="fas fa-edit" aria-hidden="true"></i> 수정
              </a>
              <a class="btn btn-sm btn-danger" href="/{{ Request::path() }}" role="button" @click.prevent="destory">
                <i class="fas fa-trash" aria-hidden="true"></i> 삭제
              </a>
              @endif
            </div>
            @endauth

            <div class="col-6 text-right">
              <a class="btn btn-sm btn-primary" href="{{ route('qna.index') }}" role="button">
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