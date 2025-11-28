@extends('layouts.app')

@section('content')
    {{-- Form create event, termasuk dropdown kategori --}}
    <h1>Create Event</h1>

    @php
        $role = auth()->user()->role ?? null;
        $routePrefix = $role === 'admin' ? 'admin' : 'organizer';
    @endphp

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

    <form method="POST" action="{{ route($routePrefix . '.events.store') }}">
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
            <label>Date</label><br>
            <input type="date" name="date" value="{{ old('date') }}" required>
        </div>

        <div>
            <label>Time</label><br>
            <input type="time" name="time" value="{{ old('time') }}" required>
        </div>

        <div>
            <label>Location</label><br>
            <input type="text" name="location" value="{{ old('location') }}" required>
        </div>

        <div>
            <label>Category</label><br>
            <select name="category_id" required>
                <option value="">-- Pilih Category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
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
