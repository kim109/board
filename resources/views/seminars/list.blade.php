@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('js/seminars/list.js') }}"></script>
@endpush

@section('content')
  <div id="content" class="container my-5">
    <div class="row no-gutters mt-3">
      <div class="col">
        <media-list writable="{{ $writable }}"></media-list>
      </div>
      <div class="ml-3 d-none d-sm-block" style="width:300px">
        @include('layouts.right_banner')
      </div>
    </div>
  </div>
@endsection