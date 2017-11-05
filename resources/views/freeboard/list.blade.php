@extends('layouts.app')

@section('content')
  @component('layouts.list', ['articles' => $articles])
    @slot('board')
      자유게시판
    @endslot

    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <th class="text-center hidden-xs" style="width:4em;">번호</th>
                <th class="text-center">제 목</th>
                <th class="text-center" style="width:6em;">작성자</th>
                <th class="text-center" style="width:6em;">작성일시</th>
                <th class="text-center hidden-xs" style="width:5em;">조회수</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($notices as $notice)
            <tr class="success">
                <td class="text-center hidden-xs"></td>
                <td>
                    <a href="freeboard/{{ $notice->id }}">
                        {{ $notice->subject }} [{{ $notice->comments->count() }}]
                        @if ($notice->created_at > \Carbon\Carbon::today())
                        <img class="icon-new" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJAgMAAACd/+6DAAAACVBMVEX/////X5D8/PwtSiEdAAAAAXRSTlMAQObYZgAAACFJREFUeNpjEA1lYAgNdWCIjHRgiALiyJlAnAnhg8SB8gCLUAe0QNoMtgAAAABJRU5ErkJggg==" />
                        @endif
                    </a>
                </td>
                <td class="text-center">{{ $notice->user->name }}</td>
                <td class="text-center" title="{{ $notice->created_at }}">
                    @if ($notice->created_at > \Carbon\Carbon::today())
                        {{ $notice->created_at->format('H:i') }}
                    @else
                        {{ $notice->created_at->format('m/d') }}
                    @endif
                </td>
                <td class="text-center hidden-xs">{{ $notice->hits }}</td>
            </tr>
        @endforeach
        @forelse ($articles as $article)
            <tr>
                <td class="text-center hidden-xs">
                    {{ $articles->perPage() * ($articles->currentPage()-1) + $loop->iteration }}
                </td>
                <td>
                    <a href="freeboard/{{ $article->id }}">
                        {{ $article->subject }} [{{ $article->comments->count() }}]
                        @if ($article->created_at > \Carbon\Carbon::today())
                        <img class="icon-new" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJAgMAAACd/+6DAAAACVBMVEX/////X5D8/PwtSiEdAAAAAXRSTlMAQObYZgAAACFJREFUeNpjEA1lYAgNdWCIjHRgiALiyJlAnAnhg8SB8gCLUAe0QNoMtgAAAABJRU5ErkJggg==" />
                        @endif
                    </a>
                </td>
                <td class="text-center">{{ $article->user->name }}</td>
                <td class="text-center" title="{{ $article->created_at }}">
                    @if ($article->created_at > \Carbon\Carbon::today())
                        {{ $article->created_at->format('H:i') }}
                    @else
                        {{ $article->created_at->format('m/d') }}
                    @endif
                </td>
                <td class="text-center hidden-xs">{{ $article->hits }}</td>
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