@extends('layouts.master')
@section('content')
    {{-- message --}}

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Leave Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Leave Report</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="#" class="btn btn-primary">PDF</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <div class="row filter-row mb-4">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input class="form-control floating" type="text">
                        <label class="focus-label">Employee</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="#" class="btn btn-success btn-block"> Search </a>
                </div>
            </div>
            <!-- /Search Filter -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Leave Type</th>
                                    <th>No.of Days</th>
                                    <th>Remaining Leave</th>
                                    <th>Total Leaves</th>
                                    <th>Total Leave Taken</th>
                                    <th>Leave Carry Forward</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaves as $items)
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="#" class="avatar">
                                                    <img alt="{{ $items->avatar ?? 'avatar' }}"
                                                        src="{{ $items->avatar ? URL::to('/assets/images/' . $items->avatar) : asset('assets/images/default-avatar.png') }}">
                                                </a>
                                                <a href="#">{{ $items->name ?? 'N/A' }}
                                                    <span>{{ $items->user_id ?? '' }}</span></a>
                                            </h2>
                                        </td>
                                        <td>{{ $items->date ? \Carbon\Carbon::parse($items->date)->format('Y-m-d') : ($items->created_at ? $items->created_at->format('Y-m-d') : '-') }}
                                        </td>
                                        <td>{{ $items->department ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($items->leave_type == 'Loss of Pay')
                                                <button
                                                    class="btn btn-outline-info btn-sm">{{ $items->leave_type }}</button>
                                            @elseif ($items->leave_type == 'Medical Leave')
                                                <button
                                                    class="btn btn-outline-danger btn-sm">{{ $items->leave_type }}</button>
                                            @else
                                                <button
                                                    class="btn btn-outline-success btn-sm">{{ $items->leave_type }}</button>
                                            @endif
                                        </td>
                                        <td class="text-center"><span class="btn btn-danger btn-sm">{{ $items->number_of_day }}
                                                Day{{ $items->number_of_day > 1 ? 's' : '' }}</span></td>
                                        <td class="text-center"><span
                                                class="btn btn-warning btn-sm"><b>{{ $items->remaining_leave ?? 0 }}</b></span>
                                        </td>
                                        <td class="text-center"><span
                                                class="btn btn-success btn-sm"><b>{{ $items->total_leaves ?? 0 }}</b></span>
                                        </td>
                                        <td class="text-center">{{ $items->total_leave_taken ?? 0 }}</td>
                                        <td class="text-center">{{ $items->leave_carry_forward ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No leave records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
