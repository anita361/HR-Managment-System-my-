@extends('layouts.chat')

@section('content')
<div class="page-wrapper">
    <div class="container py-4">
        <div class="card shadow-sm">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-info-circle me-2"></i> Group Info — {{ $group->name }}
                </h5>
                <a href="{{ route('groups.members', $group->id) }}" class="btn btn-sm btn-light">
                    <i class="fa fa-users"></i> Members
                </a>
            </div>

            <div class="card-body">

               
                <div class="mb-3 d-flex align-items-center gap-3">
                    <img src="{{ $group->avatar ? URL::to('/assets/images/' . $group->avatar) : asset('assets/images/group.png') }}"
                         alt="{{ $group->name }}"
                         class="rounded-circle"
                         style="width:60px;height:60px;object-fit:cover;">
                    
                    
                    @if(auth()->id() === $group->created_by)
                        <form action="{{ route('groups.update-avatar', $group->id) }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                            @csrf
                            @method('PATCH')
                            <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*" required>
                            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                        </form>
                    @endif
                </div>

               
                <div class="mb-3">
                    <strong>Group Name:</strong>
                    <div>{{ $group->name }}</div>
                </div>

                <div class="mb-3">
                    <strong>Created By:</strong>
                    <div>{{ $group->creator->name ?? 'Admin' }}</div>
                </div>

                <div class="mb-3">
                    <strong>Total Members:</strong>
                    <div>{{ $group->users->count() }}</div>
                </div>

                <div class="mb-3">
                    <strong>Created At:</strong>
                    <div>{{ $group->created_at->format('d M Y') }}</div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
