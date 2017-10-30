@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="active">임플란트 매칭</li>
    </ol>

    <div class="row" style="margin:3em 0 1em;">
        <div class="col-sm-6 col-xs-8" style="padding-left:0;">
            <form metod="get">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="q" placeholder="검색어를 입력해주세요..." value="{{ Request::get('q') }}">
                    <span class="input-group-btn">
                        <input type="submit" class="btn btn-default" value="검색">
                    </span>
                </div>
            </form>
        </div>

        <div class="col-sm-6 col-xs-4 text-right"  style="padding-right:0;">
            <a class="btn btn-primary btn-sm" href="freeboard/create" role="button">
                <i class="fa fa-pencil" aria-hidden="true"></i> 글 쓰기
            </a>
        </div>
    </div>

    <div>
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

        <div class="text-center">
            {{ $articles->appends(Request::only('q'))->links() }}
        </div>
    </div>
</div>
@endsection