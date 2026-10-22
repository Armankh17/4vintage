@extends('layouts.app')

@section('content')

<section class="intro">
  <h1>Find your next favourite piece.</h1>
  <p>Vintage clothes from people's wardrobes, with the brand and price up front.</p>

  <form class="search" id="searchForm" method="GET" action="{{ route('items.index') }}" role="search">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
    <input type="search" name="q" value="{{ $search }}" placeholder="Search jackets, Nike, denim…" aria-label="Search items" autocomplete="off">
    <input type="hidden" name="brand" value="{{ $brand }}">
    <button class="btn" type="submit">Search</button>
  </form>
</section>

@if ($brands->isNotEmpty())
  <nav class="brands" id="brandFilter" aria-label="Filter by brand">
    <a href="{{ route('items.index', array_filter(['q' => $search])) }}" data-brand="" @class(['on' => !$brand])>All</a>
    @foreach ($brands as $b)
      <a href="{{ route('items.index', array_filter(['q' => $search, 'brand' => $b])) }}" data-brand="{{ $b }}" @class(['on' => $brand === $b])>{{ $b }}</a>
    @endforeach
  </nav>
@endif

<div id="results" aria-live="polite">
  @include('items._results')
</div>

@endsection

@push('scripts')
<script>
// Live search with AJAX. Without JavaScript the form still works as a normal page reload.
(function () {
  const form = document.getElementById('searchForm');
  const results = document.getElementById('results');
  const brandInput = form.querySelector('input[name="brand"]');
  const brandNav = document.getElementById('brandFilter');
  let timer = null;
  let request = null;

  function buildUrl() {
    const params = new URLSearchParams(new FormData(form));
    for (const [key, value] of [...params]) {
      if (!value) params.delete(key);
    }
    const qs = params.toString();
    return form.action + (qs ? '?' + qs : '');
  }

  function load() {
    const url = buildUrl();
    if (request) request.abort();
    request = new AbortController();
    results.classList.add('loading');

    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      signal: request.signal
    })
      .then(res => {
        if (!res.ok) throw new Error('Search failed');
        return res.text();
      })
      .then(html => {
        results.innerHTML = html;
        results.classList.remove('loading');
        history.replaceState(null, '', url); // keeps the URL shareable
      })
      .catch(err => {
        if (err.name === 'AbortError') return;
        window.location = url; // fall back to a normal page load
      });
  }

  // Search as you type, waiting 300ms after the last key press
  form.q.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(load, 300);
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    clearTimeout(timer);
    load();
  });

  if (brandNav) {
    brandNav.addEventListener('click', e => {
      const link = e.target.closest('a[data-brand]');
      if (!link) return;
      e.preventDefault();
      brandInput.value = link.dataset.brand;
      brandNav.querySelectorAll('a').forEach(a => a.classList.toggle('on', a === link));
      load();
    });
  }
})();
</script>
@endpush
