@extends('layouts.app')

@section('content')

<section class="intro">
  <h1>Find your next favourite piece.</h1>
  <p>Vintage clothes from people's wardrobes, with the brand and price up front.</p>

  <form class="search" id="searchForm" method="GET" action="{{ route('items.index') }}" role="search">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
    <input type="search" name="q" value="{{ $search }}" placeholder="Search jackets, Nike, denim…" aria-label="Search items" autocomplete="off">
    <input type="hidden" name="brand" value="{{ $brand }}">
    <input type="hidden" name="sort" value="{{ $sort }}">
    <button class="btn" type="submit">Search</button>
  </form>
</section>

<div class="toolbar">
  @if ($brands->isNotEmpty())
    <nav class="brands" id="brandFilter" aria-label="Filter by brand">
      <a href="{{ route('items.index', array_filter(['q' => $search, 'sort' => $sort !== 'newest' ? $sort : null])) }}" data-brand="" @class(['on' => !$brand])>All</a>
      @foreach ($brands as $b)
        <a href="{{ route('items.index', array_filter(['q' => $search, 'brand' => $b, 'sort' => $sort !== 'newest' ? $sort : null])) }}" data-brand="{{ $b }}" @class(['on' => $brand === $b])>{{ $b }}</a>
      @endforeach
    </nav>
  @endif

  <nav class="sort" id="sortOptions" aria-label="Sort items">
    <span>Sort</span>
    @foreach ([
      'newest' => 'Newest',
      'name_asc' => 'Name A–Z',
      'name_desc' => 'Name Z–A',
      'brand_asc' => 'Brand A–Z',
      'brand_desc' => 'Brand Z–A',
    ] as $value => $label)
      <a href="{{ route('items.index', array_filter(['q' => $search, 'brand' => $brand, 'sort' => $value !== 'newest' ? $value : null])) }}"
         data-sort="{{ $value }}" @class(['on' => $sort === $value])>{{ $label }}</a>
    @endforeach
  </nav>
</div>

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
      if (!value || (key === 'sort' && value === 'newest')) params.delete(key);
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
        if ('speechSynthesis' in window) speechSynthesis.cancel();
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

  // Sorting: hover over an option to apply it (click or tap works too).
  // A short delay means just moving the mouse past it doesn't trigger it.
  const sortInput = form.querySelector('input[name="sort"]');
  const sortNav = document.getElementById('sortOptions');
  let hoverTimer = null;

  function applySort(link) {
    if (sortInput.value === link.dataset.sort) return; // already sorted this way
    sortInput.value = link.dataset.sort;
    sortNav.querySelectorAll('a').forEach(a => a.classList.toggle('on', a === link));
    load();
  }

  sortNav.querySelectorAll('a[data-sort]').forEach(link => {
    link.addEventListener('mouseenter', () => {
      clearTimeout(hoverTimer);
      hoverTimer = setTimeout(() => applySort(link), 250);
    });
    link.addEventListener('mouseleave', () => clearTimeout(hoverTimer));
    link.addEventListener('click', e => {
      e.preventDefault();
      clearTimeout(hoverTimer);
      applySort(link);
    });
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    clearTimeout(timer);
    load();
  });

  // ---- Hardware API: Speech Synthesis (speakers) ----
  // Reads an item out loud, e.g. "Nike. Track jacket. £25.00".
  // Uses event delegation on #results, so it still works after AJAX replaces the cards.
  if ('speechSynthesis' in window) {
    let speakingButton = null;

    function resetButton() {
      if (speakingButton) {
        speakingButton.textContent = '🔊 Listen';
        speakingButton.classList.remove('on');
        speakingButton = null;
      }
    }

    results.addEventListener('click', e => {
      const button = e.target.closest('.speak');
      if (!button) return;

      // Pressing the same button again stops it
      const wasSameButton = button === speakingButton;
      speechSynthesis.cancel();
      resetButton();
      if (wasSameButton) return;

      const speech = new SpeechSynthesisUtterance(button.dataset.text);
      speech.lang = 'en-GB';
      speech.rate = 1;
      speech.onend = resetButton;
      speech.onerror = resetButton;

      speakingButton = button;
      button.textContent = '⏹ Stop';
      button.classList.add('on');
      speechSynthesis.speak(speech);
    });
  } else {
    // Browser can't speak: hide the Listen buttons
    document.body.classList.add('no-speech');
  }

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
