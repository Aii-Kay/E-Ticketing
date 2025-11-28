@extends('layouts.app')

@section('content')
    {{-- Form create kategori baru --}}
    <h1>Create Category</h1>

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

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div>
            <label>Name</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label>Description</label><br>
            <textarea name="description" required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label>Image (path / url)</label><br>
            <input type="text" name="image" value="{{ old('image') }}" required>
        </div>

        <div>
            <button type="submit">Save</button>
        </div>
    </form>
@endsection

