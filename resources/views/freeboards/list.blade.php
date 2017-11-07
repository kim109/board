@extends('layouts.app')

@push('stylesheets')
<style type="text/css">
  thead th, tbody td {
    text-align: center;
  }
</style>
@endpush

@section('content')
  @component('layouts.list', ['articles' => $articles, 'writable' => true])
    @slot('board')
      자유게시판
    @endslot

    <table class="table table-hover table-condensed">
      <thead>
        <tr>
          <th class="hidden-xs" style="width:4em;">번호</th>
          <th>제 목</th>
          <th style="width:6em;">작성자</th>
          <th style="width:6em;">작성일시</th>
          <th class="hidden-xs" style="width:5em;">조회수</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($notices as $notice)
        <tr class="success">
            <td class="hidden-xs"></td>
            <td class="text-left">
                <a href="{{ Request::path() }}/{{ $notice->id }}">
                    {{ $notice->subject }} [{{ $notice->comments->count() }}]
                    @if ($notice->created_at > \Carbon\Carbon::today())
                    <img class="icon-new" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJAgMAAACd/+6DAAAACVBMVEX/////X5D8/PwtSiEdAAAAAXRSTlMAQObYZgAAACFJREFUeNpjEA1lYAgNdWCIjHRgiALiyJlAnAnhg8SB8gCLUAe0QNoMtgAAAABJRU5ErkJggg==" />
                    @endif
                </a>
            </td>
            <td>{{ $notice->user->name }}</td>
            <td title="{{ $notice->created_at }}">
                @if ($notice->created_at > \Carbon\Carbon::today())
                    {{ $notice->created_at->format('H:i') }}
                @else
                    {{ $notice->created_at->format('m/d') }}
                @endif
            </td>
            <td class="hidden-xs">{{ $notice->hits }}</td>
        </tr>
        @endforeach
        @forelse ($articles as $article)
        <tr>
            <td class="hidden-xs">
                {{ $articles->perPage() * ($articles->currentPage()-1) + $loop->iteration }}
            </td>
            <td class="text-left">
                <a href="{{ Request::path() }}/{{ $article->id }}">
                    {{ $article->subject }} [{{ $article->comments->count() }}]
                    @if ($article->created_at > \Carbon\Carbon::today())
                    <img class="icon-new" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJAgMAAACd/+6DAAAACVBMVEX/////X5D8/PwtSiEdAAAAAXRSTlMAQObYZgAAACFJREFUeNpjEA1lYAgNdWCIjHRgiALiyJlAnAnhg8SB8gCLUAe0QNoMtgAAAABJRU5ErkJggg==" />
                    @endif
                </a>
            </td>
            <td>{{ $article->user->name }}</td>
            <td title="{{ $article->created_at }}">
                @if ($article->created_at > \Carbon\Carbon::today())
                    {{ $article->created_at->format('H:i') }}
                @else
                    {{ $article->created_at->format('m/d') }}
                @endif
            </td>
            <td class="hidden-xs">{{ $article->hits }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">등록된 글이 없습니다</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  @endcomponent
@endsection