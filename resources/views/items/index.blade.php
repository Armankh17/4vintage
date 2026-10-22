@extends('layouts.app')

@section('content')

<form class="search" method="GET" action="{{ route('items.index') }}">
  <input type="search" name="q" value="{{ $search }}" placeholder="Search by item or brand" aria-label="Search">
  @if ($brand)
    <input type="hidden" name="brand" value="{{ $brand }}">
  @endif
  <button class="btn" type="submit">Search</button>
</form>

@if ($brands->isNotEmpty())
  <nav class="brands">
    <a href="{{ route('items.index', array_filter(['q' => $search])) }}" @class(['on' => !$brand])>All</a>
    @foreach ($brands as $b)
      <a href="{{ route('items.index', array_filter(['q' => $search, 'brand' => $b])) }}" @class(['on' => $brand === $b])>{{ $b }}</a>
    @endforeach
  </nav>
@endif

@if ($items->isEmpty())
  <div class="empty">
    @if ($search !== '' || $brand)
      <p>No items match{{ $search !== '' ? ' "'.$search.'"' : '' }}{{ $brand ? ' in '.$brand : '' }}.</p>
      <a class="btn" href="{{ route('items.index') }}">Show all items</a>
    @else
      <p>Nothing here yet.</p>
      <a class="btn" href="{{ route('items.create') }}">Upload the first item</a>
    @endif
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
