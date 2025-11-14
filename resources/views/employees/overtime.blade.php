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
                        <h4>{{ $overtimeEmployeesCount }} <span>this month</span></h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Overtime Hours</h6>
                        <h4>{{ $overtimeHours }} <span>this month</span></h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Pending Request</h6>
                        <h4>{{ $pendingCount }}</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="stats-info">
                        <h6>Rejected</h6>
                        <h4>{{ $rejectedCount }}</h4>
                    </div>
                </div>
            </div>
            <!-- /Overtime Statistics -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Overtime Date</th>
                                    <th class="text-center">Hours</th>
                                    <th>OT Type</th>
                                    <th>Description</th>
                                    <th class="text-center">Status</th>
                                    <th>Approved By</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overtimes as $key => $ot)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        {{-- Employee cell (safe checks, uses route only if defined) --}}
                                        <td>
                                            <h2 class="table-avatar">
                                                @if (isset($ot->employee) && $ot->employee->avatar)
                                                    <a href="{{ Route::has('employee.profile') ? route('employee.profile', $ot->employee->id) : '#' }}"
                                                        class="avatar">
                                                        <img alt="{{ $ot->employee->name ?? '' }}"
                                                            src="{{ URL::to('/assets/images/' . $profile->avatar) }}">
                                                    </a>
                                                @else
                                                    <span class="avatar"
                                                        style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; background:#ccc; border-radius:50%;">

                                                    </span>
                                                @endif


                                                <a
                                                    href="{{ Route::has('employee.profile') && isset($ot->employee) ? route('employee.profile', $ot->employee->id) : '#' }}">
                                                    {{ $ot->employee->name ?? '—' }}
                                                    @if (!empty($ot->employee->role))
                                                        <span>{{ $ot->employee->role }}</span>
                                                    @endif
                                                </a>
                                            </h2>
                                        </td>

                                        {{-- Date --}}
                                        <td>{{ $ot->ot_date ? \Carbon\Carbon::parse($ot->ot_date)->format('d M Y') : '—' }}
                                        </td>

                                        {{-- Hours --}}
                                        <td class="text-center">{{ $ot->ot_hours ?? '—' }}</td>

                                        {{-- Type --}}
                                        <td>{{ $ot->ot_type ?? '—' }}</td>

                                        {{-- Description (limited) --}}
                                        <td>{{ \Illuminate\Support\Str::limit($ot->description ?? '—', 60) }}</td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            <div class="action-label">
                                                @php $status = $ot->status ?? 'new'; @endphp

                                                @if ($status === 'approved')
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-success"></i> Approved
                                                    </a>
                                                @elseif ($status === 'rejected')
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-danger"></i> Rejected
                                                    </a>
                                                @else
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-purple"></i>
                                                        {{ ucfirst($status) }}
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Approver (safe route check) --}}
                                        <td>
                                            <h2 class="table-avatar">
                                                @if ($ot->approver)
                                                    <a href="{{ Route::has('employee.profile') ? route('employee.profile', $ot->approver->id) : '#' }}"
                                                        class="avatar avatar-xs">
                                                        <img src="{{ $ot->approver->avatar ? asset('storage/' . $ot->approver->avatar) : asset('assets/img/profiles/default.jpg') }}"
                                                            alt="">
                                                    </a>
                                                    <a
                                                        href="{{ Route::has('employee.profile') ? route('employee.profile', $ot->approver->id) : '#' }}">
                                                        {{ $ot->approver->name }}
                                                    </a>
                                                @else
                                                    —
                                                @endif
                                            </h2>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <!-- Edit -->
                                                    <a class="dropdown-item edit-overtime" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#edit_overtime"
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
                        <form id="form-edit-overtime" method="post" action="{{ route('form/overtime/update') }}">
                            @csrf
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

                            <!-- Overtime Date -->
                            <div class="form-group">
                                <label>Overtime Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" type="text" name="ot_date"
                                        id="edit_ot_date" required>
                                </div>
                            </div>
                            <!-- Overtime Hours -->
                            <div class="form-group">
                                <label>Overtime Hours <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" step="0.1" name="ot_hours"
                                    id="edit_ot_hours" required>
                            </div>

                            <!-- OT Type -->
                            <div class="form-group">
                                <label>OT Type</label>
                                <input class="form-control" type="text" name="ot_type" id="edit_ot_type">
                            </div>


                            <!-- Description -->
                            <div class="form-group">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea rows="4" class="form-control" name="description" id="edit_description" required></textarea>
                            </div>


                            <!-- Status -->
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" id="edit_status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <!-- Approved By -->
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
                                <button type="submit" class="btn btn-sm btn-primary btn-update-overtime">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Overtime Modal -->

        <!-- Delete Overtime Modal -->
        <div id="delete_overtime" class="modal custom-modal fade" role="dialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="form-delete-overtime" method="POST" action="{{ route('form/overtime/delete') }}">
                        @csrf
                        <!-- single hidden input for id -->
                        <input type="hidden" name="id" id="delete_ot_id">

                        <div class="modal-header">
                            <h5 class="modal-title">Delete Overtime</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <p>Are you sure you want to delete this overtime record?</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <!-- submit the form to perform deletion -->
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
        <script>
            (function() {
                // helper to open modal in a Bootstrap-version-agnostic way
                function showModalById(id) {
                    // If jQuery/Bootstrap (v3/4) plugin present
                    if (typeof $ === 'function' && typeof $(id).modal === 'function') {
                        $(id).modal('show');
                        return;
                    }
                    // Bootstrap 5+ (no jQuery modal plugin)
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var el = document.querySelector(id);
                        if (el) {
                            var m = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                            m.show();
                        }
                        return;
                    }
                    console.warn('No modal method found. Check Bootstrap / jQuery includes.');
                }

                // Click handler for edit link
                $(document).on('click', '.edit-overtime', function(e) {
                    // prevent following the href="#" if you use it
                    e.preventDefault();

                    var $el = $(this);

                    // read raw attributes
                    var id = $el.attr('data-id') || '';
                    var employee_id = $el.attr('data-employee_id') || '';
                    var ot_date = $el.attr('data-ot_date') || '';
                    var ot_hours = $el.attr('data-ot_hours') || '';
                    var ot_type = $el.attr('data-ot_type') || '';
                    var description = $el.attr('data-description') || '';
                    var status = $el.attr('data-status') || 'pending';
                    var approver_id = $el.attr('data-approver_id') || '';

                    // populate inputs
                    $('#edit_ot_id').val(id);
                    $('#edit_employee_id').val(employee_id).trigger('change'); // select2 / bootstrap-select safe
                    $('#edit_ot_date').val(ot_date); // if datepicker, update via its API
                    $('#edit_ot_hours').val(ot_hours);
                    $('#edit_ot_type').val(ot_type);
                    $('#edit_description').val(description);
                    $('#edit_status').val(status).trigger('change');
                    $('#edit_approver_id').val(approver_id).trigger('change');

                    // explicitly show modal (works for BS4 & BS5)
                    showModalById('#edit_overtime');
                });
            })();

            $('#delete_overtime').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');


                $('#delete_ot_id').val(id);
            });
        </script>
    @endsection
@endsection
