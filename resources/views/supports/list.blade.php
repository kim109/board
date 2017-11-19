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
      사용문의 Q &amp; A
    @endslot

    <table class="table table-hover table-condensed">
      <thead>
          <tr>
            <th class="hidden-xs" style="width:4em;">번호</th>
            <th style="width:6em;">구분</th>
            <th>제 목</th>
            <th style="width:6em;">상태</th>
            <th class="hidden-xs" style="width:6em;">작성자</th>
            <th style="width:6em;">작성일시</th>
            <th class="hidden-xs" style="width:5em;">조회수</th>
          </tr>
      </thead>
      <tbody>
        @forelse ($articles as $article)
          <tr>
            <td class="hidden-xs">
              {{ $articles->perPage() * ($articles->currentPage()-1) + $loop->iteration }}
            </td>
            <td>
              {{ $article->category->name }}
            </td>
            <td class="text-left">
              <a href="{{ Request::path() }}/{{ $article->id }}">
                {{ $article->subject }} [{{ $article->comments->count() }}]
                @if ($article->attachments->count() > 0)
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                @endif
                @if ($article->created_at > \Carbon\Carbon::today())
                <span class="icon-new"></span>
                @endif
              </a>
            </td>
            <td>
              @if ($article->answer == null )
                질문중
              @else
                완료
              @endif
            </td>
            <td class="hidden-xs">{{ $article->user->name }}</td>
            <td title="{{ $article->created_at }}">
                @if ($article->created_at > \Carbon\Carbon::today())
                    {{ $article->created_at->format('H:i') }}
                @else
                    {{ $article->created_at->format('m/d') }}
                @endif
            </td>
            <td class="hidden-xs">{{ number_format($article->hits) }}</td>
          </tr>
        @empty
        <tr>
          <td colspan="6">등록된 공지사항이 없습니다.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  @endcomponent
@endsection