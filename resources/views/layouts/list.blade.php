<div class="container">
  <ol class="breadcrumb"><li class="active">{{ $board }}</li></ol>

  <div class="row" style="margin:3em 0 1em;">
    <div class="col-sm-6 col-xs-8" style="padding-left:0;">
      <form metod="get" action="/{{ Request::path() }}">
        <div class="input-group input-group-sm">
          <input type="text" class="form-control" name="q" placeholder="검색어를 입력해주세요" value="{{ Request::get('q') }}">
          <span class="input-group-btn">
            <input type="submit" class="btn btn-default" value="검색">
          </span>
        </div>
      </form>
    </div>

    @if ($writable)
    <div class="col-sm-6 col-xs-4 text-right" style="padding-right:0;">
      <a class="btn btn-primary btn-sm" href="/{{ Request::path() }}/create" role="button">
        <i class="fa fa-pencil" aria-hidden="true"></i> 글 쓰기
      </a>
    </div>
    @endif
  </div>

  <div>
    {{ $slot }}
    <div class="text-center">
      {{ $articles->appends(Request::only('q'))->links() }}
    </div>
  </div>
</div>