@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('js/list.js') }}"></script>
@endpush

@section('content')
  <div id="content" class="container my-5">
    <summary-articles></summary-articles>

    <div class="row no-gutters mt-3">
      <div class="col">
        <articles-list writable="{{ Auth::check() }}"></articles-list>
      </div>
      <div class="ml-3 d-none d-sm-block" style="width:300px">
        @include('layouts.right_banner')
      </div>
    </div>
  </div>
@endsection