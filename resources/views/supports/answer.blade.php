@extends('layouts.app')

@section('content')
<div class="container">
  <ol class="breadcrumb">
    <li><a href="/{{ dirname(Request::path(), 2) }}">사용문의 Q &amp; A</a></li>
    <li><a href="/{{ dirname(Request::path()) }}">{{ $article->subject }}</a></li>
    <li class="active">답변하기</li>
  </ol>

  @if (count($errors) > 0)
  <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
    <p>{!! nl2br(e($error)) !!}</p>
    @endforeach
  </div>
  @endif

  <form id="fm" action="/{{ dirname(Request::path()) }}/answer" method="post" enctype="multipart/form-data">
    <div class="panel panel-default">
      <div class="panel-body">
        {{ csrf_field() }}

        <h4>
          <span class="text-success">[{{ $article->category->name }}]</span> {{ $article->subject }}
        </h4>
        <hr>

        <p>{!! $article->question !!}</p>
        <hr>

        <div class="form-group">
          <textarea class="form-control input-sm" rows="4" name="content" id="content" required></textarea>
        </div>

      </div>
      <div class="panel-footer text-right">
        <input class="btn btn-primary btn-sm" type="submit" value="답변하기">
        <a class="btn btn-default btn-sm" href="/{{ dirname(Request::path()) }}" role="button">취소</a>
      </div>
    </div>
  </form>
</div>
@endsection