@if ($items->isEmpty())
  <div class="empty">
    @if ($search !== '' || $brand)
      <p>No items match{{ $search !== '' ? ' "'.$search.'"' : '' }}{{ $brand ? ' in '.$brand : '' }}.</p>
      <a class="btn ghost" href="{{ route('items.index') }}">Show all items</a>
    @else
      <p>Nothing here yet.</p>
      <a class="btn" href="{{ route('items.create') }}">Upload the first item</a>
    @endif
  </div>
@else
  <p class="count">{{ $items->count() }} {{ Str::plural('item', $items->count()) }}</p>
  <div class="grid">
    @foreach ($items as $item)
      <article class="item">
        <div class="photo">
          <img src="{{ asset($item->photo) }}" alt="{{ $item->brand }} {{ $item->name }}" loading="lazy">
          <span class="price">£{{ number_format($item->price, 2) }}</span>
        </div>
        <strong>{{ $item->brand }}</strong>
        <p>{{ $item->name }}</p>
      </article>
    @endforeach
  </div>
@endif
