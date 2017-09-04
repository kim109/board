@extends('layouts.app')

@section('content')
<div class="container">
    <ul>
        @foreach ($categories as $category)
        <li><a href="{{ $category->prefix }}">{{ $category->title }}</a></li>
        @endforeach
    </ul>
</div>
@endsection
