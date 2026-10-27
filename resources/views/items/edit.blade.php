@extends('layouts.app')

@section('title', 'Edit item · 4vintage')

@section('content')

<div class="upload">
  <h1>Edit item</h1>
  <p class="sub">Change the details, or tap the photo to replace it.</p>

  <form class="form" method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label class="drop" for="photo">
      <img id="preview" src="{{ asset($item->photo) }}" alt="Current photo">
      <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
    </label>
    @error('photo') <small class="error">{{ $message }}</small> @enderror

    <label>
      What is it?
      <input type="text" name="name" value="{{ old('name', $item->name) }}" required maxlength="80">
      @error('name') <small class="error">{{ $message }}</small> @enderror
    </label>

    <div class="two">
      <label>
        Brand
        <input type="text" name="brand" value="{{ old('brand', $item->brand) }}" required maxlength="60">
        @error('brand') <small class="error">{{ $message }}</small> @enderror
      </label>

      <label>
        Price (£)
        <input type="number" name="price" value="{{ old('price', $item->price) }}" step="0.01" min="0" required>
        @error('price') <small class="error">{{ $message }}</small> @enderror
      </label>
    </div>

    <div class="form-buttons">
      <button class="btn" type="submit">Save changes</button>
      <a class="btn ghost" href="{{ route('items.index') }}">Cancel</a>
    </div>
  </form>
</div>

@endsection

@push('scripts')
<script>
  // Show the new photo before saving
  document.getElementById('photo').addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) document.getElementById('preview').src = URL.createObjectURL(file);
  });
</script>
@endpush
