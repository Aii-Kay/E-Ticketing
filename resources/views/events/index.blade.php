@extends('layouts.app')

@section('content')
    <h1>Events</h1>

    @php
        $role = auth()->user()->role ?? null;
        $routePrefix = $role === 'admin' ? 'admin' : 'organizer';
    @endphp

    <p>
        <a href="{{ route($routePrefix . '.events.create') }}">Create Event</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Category</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr>
                    <td>{{ $event->id }}</td>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->date }}</td>
                    <td>{{ $event->time }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ optional($event->category)->name }}</td>
                    <td>{{ optional($event->creator)->name }}</td>
                    <td>
                        <a href="{{ route($routePrefix . '.events.edit', $event->id) }}">Edit</a>

                        <form action="{{ route($routePrefix . '.events.destroy', $event->id) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus event ini?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada event.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
