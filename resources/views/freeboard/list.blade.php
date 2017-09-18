@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="active">자유게시판</li>
    </ol>

    <p class="text-right">
        <a class="btn btn-primary btn-sm" href="freeboard/create" role="button">
            <i class="fa fa-pencil" aria-hidden="true"></i> 글 쓰기
        </a>
    </p>
    <div>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th class="text-center hidden-xs" style="width:4em;">번호</th>
                    <th class="text-center">제 목</th>
                    <th class="text-center" style="width:8em;">작성자</th>
                    <th class="text-center" style="width:6em;">작성일시</th>
                    <th class="text-center hidden-xs" style="width:5em;">조회수</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($articles as $article)
                <tr>
                    <td class="text-center hidden-xs">{{ $loop->iteration }}</td>
                    <td>
                        <a href="freeboard/{{ $article->id }}">{{ $article->subject }} [{{ $article->comments->count() }}]</a>
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
    </div>
</div>
@endsection