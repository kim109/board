@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('js/list.js') }}"></script>
@endpush

@section('content')
  <div id="content" class="container my-5">
    <summary-articles></summary-articles>

    <div class="row no-gutters mt-3">
      <div class="col">
        <articles-list writable="{{ $writable }}"></articles-list>
      </div>
      <div class="ml-3 d-none d-lg-block" style="width:300px">
        @include('layouts.right_banner')
      </div>
    </div>
  </div>

  {{--  @component('layouts.list', ['articles' => $articles, 'writable' => $writable])
    @slot('board')
      공지사항
    @endslot

    <table class="table table-hover table-condensed">
      <thead>
          <tr>
            <th class="hidden-xs" style="width:4em;">번호</th>
            <th style="width:6em;">구 분</th>
            <th>제 목</th>
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
            <td class="hidden-xs">치카톡</td>
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
  @endcomponent  --}}
@endsection