<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', '4vintage')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>

<header class="top">
  <div class="wrap top-row">
    <a class="logo" href="{{ route('items.index') }}">4vintage</a>
    <a class="btn" href="{{ route('items.create') }}">Upload an item</a>
  </div>
</header>

<main class="wrap">
  @if (session('status'))
    <p class="status">{{ session('status') }}</p>
  @endif

  @yield('content')
</main>

<footer>
  <div class="wrap">4vintage · Vintage clothes, uploaded by real people</div>
</footer>

@stack('scripts')
</body>
</html>
