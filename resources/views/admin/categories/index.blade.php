@extends('layouts.app')

@section('content')
    {{-- Halaman list kategori untuk admin --}}
    <h1>Category List</h1>

    <p>
        <a href="{{ route('admin.categories.create') }}">Create Category</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image (path)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>{{ $category->image }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}">Edit</a>

                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus kategori ini?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
