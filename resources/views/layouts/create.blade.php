<div class="container">
  <ol class="breadcrumb">
    <li><a href="/{{ dirname(Request::path()) }}">{{ $board }}</a></li>
    <li class="active">글 쓰기</li>
  </ol>

  @if (count($errors) > 0)
  <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
    <p>{!! nl2br(e($error)) !!}</p>
    @endforeach
  </div>
  @endif

  <form id="fm" action="/{{ dirname(Request::path()) }}" method="post" enctype="multipart/form-data">
    <div class="panel panel-default">
      <div class="panel-body">
        {{ csrf_field() }}
        {{ $slot }}
      </div>
      <div class="panel-footer text-right">
        <input class="btn btn-primary btn-sm" type="submit" value="등록">
        <a class="btn btn-default btn-sm" href="/{{ dirname(Request::path()) }}" role="button">취소</a>
      </div>
    </div>
  </form>
</div>