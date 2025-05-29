<!-- resources/views/admin/users.blade.php -->

<h1>Pending Users</h1>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

@foreach($users as $user)
    <div style="margin-bottom: 10px">
        <strong>{{ $user->name }}</strong> - {{ $user->email }}
        <form method="POST" action="/admin/users/{{ $user->id }}/approve" style="display:inline">
            @csrf
            <button type="submit">Approve</button>
        </form>
    </div>
@endforeach
