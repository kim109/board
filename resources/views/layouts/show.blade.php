<div class="container">
  <ol class="breadcrumb">
    <li><a href="{{ $list }}">{{ $board }}</a></li>
    <li class="active">{{ $article->subject }}</li>
  </ol>

  <div class="row">
    <h4 class="col-sm-6">
      {{ $title }}
    </h4>
    <div class="small text-right col-sm-6">
      <span class="text-primary"><i class="fa fa-user" aria-hidden="true"></i> {{ $article->user->name }}</span> /
      <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $article->created_at }}</span> /
      <span>조회수 : {{ number_format($article->hits) }}</span>
    </div>
  </div>
  <hr>

  {{ $slot }}

  <div class="text-center">
      <a class="btn btn-default btn-sm" href="{{ $list }}" role="button">
        <i class="fa fa-list" aria-hidden="true"></i> 목록
      </a>

      @if ($article->user_id == Auth::id())
      <a class="btn btn-default btn-sm" href="/{{ Request::path() }}/edit" role="button">
        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정
      </a>
      <a id="delete" class="btn btn-danger btn-sm" href="/{{ Request::path() }}" role="button">
        <i class="fa fa-trash" aria-hidden="true"></i> 삭제
      </a>
      @endif
  </div>
</div>