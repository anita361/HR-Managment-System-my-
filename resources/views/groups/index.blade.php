@extends('layouts.chat')

@section('content')
<div class="page-wrapper">
    <div class="container py-4">
        <h4>My Groups</h4>
        <div class="list-group mt-3">
            @forelse($groups as $group)
                <a href="{{ route('groups.show', $group->id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <img src="{{ $group->avatar ? asset('assets/images/'.$group->avatar) : asset('assets/images/group.png') }}"
                         class="rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;">
                    <span>{{ $group->name }}</span>
                </a>
            @empty
                <p class="text-muted">You are not part of any groups yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
