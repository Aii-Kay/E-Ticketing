@extends('layouts.app')

@section('content')
    {{-- Form edit kategori --}}
    <h1>Edit Category</h1>

    @if ($errors->any())
        <div>
            <strong>Terjadi error:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Name</label><br>
            <input type="text" name="name"
                   value="{{ old('name', $category->name) }}" required>
        </div>

        <div>
            <label>Description</label><br>
            <textarea name="description" required>{{ old('description', $category->description) }}</textarea>
        </div>

        <div>
            <label>Image (path / url)</label><br>
            <input type="text" name="image"
                   value="{{ old('image', $category->image) }}" required>
        </div>

        <div>
            <button type="submit">Update</button>
        </div>
    </form>
@endsection
