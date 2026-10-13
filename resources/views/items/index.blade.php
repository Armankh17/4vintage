@extends('layouts.app')

@section('content')

@if ($brands->isNotEmpty())
  <nav class="brands">
    <a href="{{ route('items.index') }}" @class(['on' => !$brand])>All</a>
    @foreach ($brands as $b)
      <a href="{{ route('items.index', ['brand' => $b]) }}" @class(['on' => $brand === $b])>{{ $b }}</a>
    @endforeach
  </nav>
@endif

@if ($items->isEmpty())
  <div class="empty">
    <p>Nothing here yet.</p>
    <a class="btn" href="{{ route('items.create') }}">Upload the first item</a>
  </div>
@else
  <div class="grid">
    @foreach ($items as $item)
      <article class="item">
        <img src="{{ asset($item->photo) }}" alt="{{ $item->brand }} {{ $item->name }}" loading="lazy">
        <div class="row">
          <strong>{{ $item->brand }}</strong>
          <span>£{{ number_format($item->price, 2) }}</span>
        </div>
        <p>{{ $item->name }}</p>
      </article>
    @endforeach
  </div>
@endif

@endsection
