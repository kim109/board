@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('js/home.js') }}"></script>
@endpush

@section('content')
<div id="content" class="container mt-5">
  <summary-articles></summary-articles>

  내용 내용
</div>
@endsection
