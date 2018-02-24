@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('js/qna/list.js') }}"></script>
@endpush

@section('content')
  <div id="content" class="container my-5">
    <div class="row mb-3">
      <div class="col-6 col-sm-8">
        <div class="input-group">
          <input type="search" id="keyword" class="form-control" placeholder="검색" aria-label="Search for..." @keyup.enter="search">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" @click="search">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
      @auth
      <div class="col-6 col-sm-4">
        <a href="{{ route('qna.create') }}" class="btn btn-block btn-primary">
          <i class="fas fa-pencil-alt"></i> 치카 지식인에 물어보기
        </a>
      </div>
      @endauth
    </div>

    <summary-articles type="qna"></summary-articles>

    <div class="row no-gutters mt-3">
      <div class="col">
        <articles-list :categories="categories" :keyword="keyword"></articles-list>
      </div>
      <div class="ml-3 d-none d-sm-block" style="width:300px">
        @include('layouts.right_banner')
      </div>
    </div>
  </div>
@endsection