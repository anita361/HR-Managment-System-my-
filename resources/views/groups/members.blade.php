@extends('layouts.chat')

@section('content')
<div class="page-wrapper">
    <div class="container py-4">
        <div class="card shadow-sm rounded">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-users me-2"></i> Members — {{ $group->name }}
                </h5>
                <a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-light">
                    <i class="fa fa-arrow-left"></i> Back to Group
                </a>
            </div>

            <div class="card-body p-3">

                
                @if(auth()->id() === $group->created_by)
                    <div class="mb-3 p-2 border rounded bg-light">
                        <form action="{{ route('groups.add-member.store', $group->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                            @csrf
                            <select name="user_id" class="form-select form-select-sm" required>
                                <option value="">-- Select user to add --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fa fa-user-plus"></i> Add Member
                            </button>
                        </form>
                    </div>
                @endif

                {{-- MEMBERS LIST --}}
                @forelse($members as $member)
                    <div class="d-flex align-items-center justify-content-between p-2 border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="{{ $member->avatar ? URL::to('/assets/images/'.$member->avatar) : asset('assets/images/default-avatar.png') }}"
                                 class="rounded-circle me-2"
                                 style="width:40px;height:40px;object-fit:cover;">
                            <div>
                                <strong>{{ $member->name }}</strong><br>
                                <small class="text-muted">{{ $member->email }}</small>
                            </div>
                        </div>

                        @if($member->id === $group->created_by)
                            <span class="badge bg-primary">Admin</span>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-muted py-4">No members found.</p>
                @endforelse

            </div>
        </div>
    </div>
</div>
@endsection
