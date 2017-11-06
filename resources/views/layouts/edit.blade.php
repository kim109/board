<div class="container">
  <ol class="breadcrumb">
    <li><a href="/{{ dirname(Request::path(), 2) }}">{{ $board }}</a></li>
    <li><a href="/{{ dirname(Request::path()) }}">{{ $subject }}</a></li>
    <li class="active">글 수정</li>
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
        {{ method_field('PATCH') }}
        {{ $slot }}
      </div>
      <div class="panel-footer text-right">
        <input class="btn btn-primary btn-sm" type="submit" value="수정">
        <a class="btn btn-default btn-sm" href="/{{ dirname(Request::path()) }}" role="button">취소</a>
      </div>
    </div>
  </form>
</div>