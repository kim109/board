@extends('layouts.app')

@push('scripts')
<script src="{{ mix('/js/show.js') }}"></script>
@endpush

@section('content')
  @component('layouts.show', ['article' => $article, 'list' => $list, 'writable' => $writable])
    @slot('board')
      자유게시판
    @endslot

    @slot('title')
      <span class="text-success">[{{ $article->category->name }}]</span> {{ $article->subject }}
    @endslot

    @if ($article->attachments != null)
    <ul class="list-unstyled">
      @foreach ($article->attachments as $attachment)
      <li class="text-right">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> <a href="/attachments/{{ $attachment->id }}/{{ md5($attachment->path) }}">{{ basename($attachment->name) }}</a>
      </li>
      @endforeach
    </ul>
    @endif

    <p>{!! $article->content !!}</p>
    <hr>

    <div class="well well-sm" id="comments">
      <comment v-for="comment in comments" :key="comment.id" :comment="comment" :user="user" @reload="loadComments"></comment>

      <textarea class="form-control input-sm" rows="3" id="comment-content" required></textarea>
      <div class="text-right">
        <button class="btn btn-sm btn-primary" @click="comment">등록</button>
      </div>
    </div>
    <hr>
  @endcomponent
@endsection