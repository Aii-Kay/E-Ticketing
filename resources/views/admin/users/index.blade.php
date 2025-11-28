@extends('layouts.app')

@section('content')
    <h1>User Management</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        {{-- Tombol Approve & Reject hanya untuk organizer dengan status pending --}}
                        @if ($user->role === 'organizer' && $user->status === 'pending')
                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Approve</button>
                            </form>

                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Reject</button>
                            </form>
                        @endif

                        {{-- Tombol Delete untuk semua user kecuali dirinya sendiri --}}
                        @if (auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus user ini?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada user.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
