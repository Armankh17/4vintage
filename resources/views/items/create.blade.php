@extends('layouts.app')

@section('title', 'Upload an item · 4vintage')

@section('content')

<h1>Upload an item</h1>

<form class="form" method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
  @csrf

  <label>
    Photo
    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
    @error('photo') <small>{{ $message }}</small> @enderror
  </label>

  <label>
    What is it?
    <input type="text" name="name" value="{{ old('name') }}" placeholder="Track jacket" required maxlength="80">
    @error('name') <small>{{ $message }}</small> @enderror
  </label>

  <label>
    Brand
    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Adidas" required maxlength="60">
    @error('brand') <small>{{ $message }}</small> @enderror
  </label>

  <label>
    Price (£)
    <input type="number" name="price" value="{{ old('price') }}" placeholder="25" step="0.01" min="0" required>
    @error('price') <small>{{ $message }}</small> @enderror
  </label>

  <button class="btn" type="submit">Upload item</button>
</form>

@endsection
