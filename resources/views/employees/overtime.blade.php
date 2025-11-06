@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Overtime</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Overtime</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_overtime"><i
                                class="fa fa-plus"></i> Add Overtime</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Overtime Statistics -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Overtime Employee</h6>
                        <h4>12 <span>this month</span></h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Overtime Hours</h6>
                        <h4>118 <span>this month</span></h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Pending Request</h6>
                        <h4>23</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Rejected</h6>
                        <h4>5</h4>
                    </div>
                </div>
            </div>
            <!-- /Overtime Statistics -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <tbody>
                                @forelse($overtimes as $key => $ot)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            <h2 class="table-avatar blue-link">
                                                <a href="{{ isset($ot->employee) ? route('employee.profile', $ot->employee->id) : '#' }}"
                                                    class="avatar">
                                                    <img alt="{{ $ot->employee->name ?? 'N/A' }}"
                                                        src="{{ $ot->employee && $ot->employee->avatar
                                                            ? asset('storage/' . $ot->employee->avatar)
                                                            : asset('assets/img/profiles/default.jpg') }}">
                                                </a>
                                                <a
                                                    href="{{ isset($ot->employee) ? route('employee.profile', $ot->employee->id) : '#' }}">
                                                    {{ $ot->employee->name ?? 'Unknown' }}
                                                </a>
                                            </h2>
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($ot->ot_date)->format('d M Y') }}</td>

                                        <td class="text-center">{{ $ot->ot_hours }}</td>

                                        <td>{{ $ot->ot_type }}</td>

                                        <td>{{ \Illuminate\Support\Str::limit($ot->description, 60) }}</td>

                                        <td class="text-center">
                                            <div class="action-label">
                                                @if ($ot->status === 'approved')
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-success"></i> Approved
                                                    </a>
                                                @elseif($ot->status === 'rejected')
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-danger"></i> Rejected
                                                    </a>
                                                @else
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-purple"></i>
                                                        {{ ucfirst($ot->status ?? 'New') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <h2 class="table-avatar">
                                                @if ($ot->approver)
                                                    <a href="{{ route('employee.profile', $ot->approver->id) }}"
                                                        class="avatar avatar-xs">
                                                        <img src="{{ $ot->approver->avatar ? asset('storage/' . $ot->approver->avatar) : asset('assets/img/profiles/default.jpg') }}"
                                                            alt="">
                                                    </a>
                                                    <a
                                                        href="{{ route('employee.profile', $ot->approver->id) }}">{{ $ot->approver->name }}</a>
                                                @else
                                                    —
                                                @endif
                                            </h2>
                                        </td>

                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <!-- Edit -->
                                                    <a class="dropdown-item edit-overtime" href="#"
                                                        data-toggle="modal" data-target="#edit_overtime"
                                                        data-id="{{ $ot->id }}"
                                                        data-employee_id="{{ $ot->employee_id }}"
                                                        data-ot_date="{{ $ot->ot_date }}"
                                                        data-ot_hours="{{ $ot->ot_hours }}"
                                                        data-ot_type="{{ $ot->ot_type }}"
                                                        data-description="{{ $ot->description }}"
                                                        data-status="{{ $ot->status }}"
                                                        data-approver_id="{{ $ot->approved_by ?? '' }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>

                                                    <!-- Delete -->
                                                    <a class="dropdown-item delete-overtime" href="#"
                                                        data-toggle="modal" data-target="#delete_overtime"
                                                        data-id="{{ $ot->id }}">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No overtime records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add Overtime Modal -->
        <div id="add_overtime" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Overtime</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('form/overtime/save') }}" method="POST">
                            @csrf

                            <div class="form-group col-sm-6">
                                <label>Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-control select" required>
                                    <option value="">-- Select Employee --</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name ?? ($employee->title ?? 'Unknown') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Overtime Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" type="text" name="ot_date"
                                        value="{{ old('ot_date') }}" required>
                                </div>
                                @error('ot_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Overtime Hours <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="ot_hours"
                                    value="{{ old('ot_hours') }}" required>
                                @error('ot_hours')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>OT Type</label>
                                <input class="form-control" type="text" name="ot_type" value="{{ old('ot_type') }}">
                                @error('ot_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description </label>
                                <textarea rows="4" class="form-control" name="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Add Overtime Modal -->

        <!-- Edit Overtime Modal -->
        <div id="edit_overtime" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Overtime</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="form-edit-overtime" method="post" action="#">
                            @csrf
                            @method('PUT')

                            <!-- hidden id -->
                            <input type="hidden" name="id" id="edit_ot_id">

                            <div class="form-group">
                                <label>Select Employee <span class="text-danger">*</span></label>
                                <select class="form-control select" name="employee_id" id="edit_employee_id" required>
                                    <option value="">- Select -</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Overtime Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" type="text" name="ot_date"
                                        id="edit_ot_date" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Overtime Hours <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="ot_hours" id="edit_ot_hours" required>
                            </div>

                            <div class="form-group">
                                <label>OT Type</label>
                                <input class="form-control" type="text" name="ot_type" id="edit_ot_type">
                            </div>

                            <div class="form-group">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea rows="4" class="form-control" name="description" id="edit_description" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" id="edit_status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Approved By</label>
                                <select class="form-control select" name="approved_by" id="edit_approver_id">
                                    <option value="">- Select Approver -</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Overtime Modal -->

        <!-- Delete Overtime Modal -->
        <div id="delete_overtime" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="form-delete-overtime" method="post" action="#">
                        @csrf
                        @method('DELETE')

                        <div class="modal-header">
                            <h5 class="modal-title">Delete Overtime</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <p>Are you sure you want to delete this overtime record?</p>
                            <input type="hidden" name="id" id="delete_ot_id">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /Delete Overtime Modal -->

    </div>
    <!-- /Page Wrapper -->

    @section('script')
    @endsection
@endsection
