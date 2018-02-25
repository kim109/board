@extends('layouts.app')

@push('scripts')
  <script src="{{ mix('js/home.js') }}"></script>
@endpush

@section('content')
<div id="content" class="container my-5">
  <summary-articles type="home"></summary-articles>

  <div class="row no-gutters mt-4">
    <div class="d-none d-lg-block">
      <div>
        <img src="/images/banner/home_banner1.jpg">
      </div>
      <div class="mt-3">
        <img src="/images/banner/home_banner2.jpg">
      </div>
    </div>
    <div class="col ml-3">
      <home-list></home-list>
    </div>
  </div>
</div>
@endsection
