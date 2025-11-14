@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Overtime</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
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
                        <h4>{{ $overtimeEmployeesCount ?? 0 }} <span>this month</span></h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Overtime Hours</h6>
                        <h4>{{ $overtimeHours ?? 0 }} <span>this month</span></h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Pending Request</h6>
                        <h4>{{ $pendingCount ?? 0 }}</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Rejected</h6>
                        <h4>{{ $rejectedCount ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <!-- /Overtime Statistics -->

            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>OT Date</th>
                                    <th class="text-center">OT Hours</th>
                                    <th>OT Type</th>
                                    <th>Description</th>
                                    <th class="text-center">Status</th>
                                    <th>Approved by</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overtimes as $key => $ot)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <h2 class="table-avatar blue-link">
                                                <a href="#" class="avatar">
                                                    <img alt=""
                                                        src="{{ $ot->employee && $ot->employee->avatar ? asset('storage/avatars/' . $ot->employee->avatar) : asset('assets/img/profiles/avatar-02.jpg') }}">
                                                </a>
                                                <a href="#">{{ $ot->employee->name ?? '—' }}</a>
                                            </h2>
                                        </td>
                                        <td>{{ $ot->ot_date->format('d M Y') }}</td>
                                        <td class="text-center">{{ $ot->ot_hours }}</td>
                                        <td>{{ $ot->ot_type }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($ot->description, 50) }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                @php
                                                    $badge = 'text-purple';
                                                    if ($ot->status === 'approved') {
                                                        $badge = 'text-success';
                                                    }
                                                    if ($ot->status === 'rejected') {
                                                        $badge = 'text-danger';
                                                    }
                                                    if ($ot->status === 'pending') {
                                                        $badge = 'text-warning';
                                                    }
                                                @endphp
                                                <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                    <i class="fa fa-dot-circle-o {{ $badge }}"></i>
                                                    {{ ucfirst($ot->status) }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <h2 class="table-avatar">
                                                @if ($ot->approver)
                                                    <a href="#" class="avatar avatar-xs"><img
                                                            src="{{ $ot->approver->avatar ? asset('storage/avatars/' . $ot->approver->avatar) : asset('assets/img/profiles/avatar-09.jpg') }}"
                                                            alt=""></a>
                                                    <a href="#">{{ $ot->approver->name }}</a>
                                                @else
                                                    —
                                                @endif
                                            </h2>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit-ot" href="javascript:void(0);"
                                                        data-toggle="modal" data-target="#edit_overtime"
                                                        data-id="{{ $ot->id }}"
                                                        data-employee_id="{{ $ot->employee_id }}"
                                                        data-ot_date="{{ $ot->ot_date->format('Y-m-d') }}"
                                                        data-ot_hours="{{ $ot->ot_hours }}"
                                                        data-ot_type="{{ $ot->ot_type }}"
                                                        data-description="{{ $ot->description }}"
                                                        data-status="{{ $ot->status }}"><i
                                                            class="fa fa-pencil m-r-5"></i> Edit</a>

                                                    <a class="dropdown-item delete-ot" href="javascript:void(0);"
                                                        data-toggle="modal" data-target="#delete_overtime"
                                                        data-id="{{ $ot->id }}">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($overtimes->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center">No overtime records found.</td>
                                    </tr>
                                @endif
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
                            <div class="form-group">
                                <label>Select Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-control select" required>
                                    <option value="">-</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Overtime Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input name="ot_date" class="form-control datetimepicker" type="date" required>
                                </div>
                                @error('ot_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Overtime Hours <span class="text-danger">*</span></label>
                                <input name="ot_hours" class="form-control" type="number" step="0.25" required>
                                @error('ot_hours')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>OT Type</label>
                                <input name="ot_type" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label>Description </label>
                                <textarea name="description" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
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
                        <form id="editOvertimeForm" action="{{ route('form/overtime/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="edit-id">
                            <div class="form-group">
                                <label>Select Employee <span class="text-danger">*</span></label>
                                <select id="edit-employee" name="employee_id" class="form-control select" required>
                                    <option value="">-</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Overtime Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input id="edit-ot-date" name="ot_date" class="form-control datetimepicker"
                                        type="date" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Overtime Hours <span class="text-danger">*</span></label>
                                <input id="edit-ot-hours" name="ot_hours" class="form-control" type="number"
                                    step="0.25" required>
                            </div>
                            <div class="form-group">
                                <label>OT Type</label>
                                <input id="edit-ot-type" name="ot_type" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label>Description </label>
                                <textarea id="edit-description" name="description" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select id="edit-status" name="status" class="form-control">
                                    <option value="new">New</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Overtime Modal -->

        <!-- Delete Overtime Modal -->
        <div class="modal custom-modal fade" id="delete_overtime" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="deleteOvertimeForm" method="POST" action="">
                        @csrf
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Overtime</h3>
                                <p>Are you sure want to delete this overtime record?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Delete Overtime Modal -->

    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit button click: populate edit modal fields
            document.querySelectorAll('.edit-ot').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('edit-id').value = this.dataset.id;
                    document.getElementById('edit-employee').value = this.dataset.employee_id;
                    document.getElementById('edit-ot-date').value = this.dataset.ot_date;
                    document.getElementById('edit-ot-hours').value = this.dataset.ot_hours;
                    document.getElementById('edit-ot-type').value = this.dataset.ot_type;
                    document.getElementById('edit-description').value = this.dataset.description;
                    document.getElementById('edit-status').value = this.dataset.status || 'new';
                });
            });

            // Delete button: set form action
            document.querySelectorAll('.delete-ot').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.dataset.id;
                    var form = document.getElementById('deleteOvertimeForm');
                    form.action = "{{ url('overtime/delete') }}/" + id;
                });
            });
        });
    </script>
@endsection
