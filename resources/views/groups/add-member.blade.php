@extends('layouts.chat')

@section('content')
<div class="page-wrapper">
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add Member — {{ $group->name }}</h5>
                <a href="{{ route('groups.members', $group->id) }}" class="btn btn-sm btn-light">Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('groups.add-member.store', $group->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Choose a user --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
