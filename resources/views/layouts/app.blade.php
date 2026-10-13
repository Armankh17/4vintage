<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', '4vintage')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<header class="top">
  <a class="logo" href="{{ route('items.index') }}">4vintage</a>
  <a class="btn" href="{{ route('items.create') }}">Upload an item</a>
</header>

<main>
  @if (session('status'))
    <p class="status">{{ session('status') }}</p>
  @endif

  @yield('content')
</main>

<footer>© {{ date('Y') }} 4vintage</footer>

</body>
</html>
