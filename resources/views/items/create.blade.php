@extends('layouts.app')

@section('title', 'Upload an item · 4vintage')

@section('content')

<div class="upload">
  <h1>Upload an item</h1>
  <p class="sub">Add a clear photo, say what it is, and set your price.</p>

  <form class="form" method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
    @csrf

    <label class="drop" for="photo">
      <img id="preview" alt="" hidden>
      <span id="dropText">Choose a photo<br><small>JPG, PNG or WEBP, up to 4MB</small></span>
      <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" required>
    </label>
    @error('photo') <small class="error">{{ $message }}</small> @enderror

    <label>
      What is it?
      <input type="text" name="name" value="{{ old('name') }}" placeholder="Track jacket" required maxlength="80">
      @error('name') <small class="error">{{ $message }}</small> @enderror
    </label>

    <div class="two">
      <label>
        Brand
        <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Adidas" required maxlength="60">
        @error('brand') <small class="error">{{ $message }}</small> @enderror
      </label>

      <label>
        Price (£)
        <input type="number" name="price" value="{{ old('price') }}" placeholder="25" step="0.01" min="0" required>
        @error('price') <small class="error">{{ $message }}</small> @enderror
      </label>
    </div>

    <button class="btn" type="submit">Upload item</button>
  </form>
</div>

@endsection

@push('scripts')
<script>
  // Show the chosen photo before uploading
  document.getElementById('photo').addEventListener('change', e => {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    if (!file) return;
    preview.src = URL.createObjectURL(file);
    preview.hidden = false;
    document.getElementById('dropText').hidden = true;
  });
</script>
@endpush
