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
                        <h3 class="page-title">Timesheet</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Timesheet</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_todaywork"><i
                                class="fa fa-plus"></i> Add Today Work</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Projects</th>
                                    <th class="text-center">Assigned Hours</th>
                                    <th class="text-center">Hours</th>
                                    <th class="d-none d-sm-table-cell">Description</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timesheets as $key => $ts)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                @if (isset($ts->employee) && $ts->employee->avatar)
                                                    <a href="{{ route('employee.profile', $ts->employee->id) }}"
                                                        class="avatar">
                                                        <img alt="{{ $ts->employee->name ?? '' }}"
                                                            src="{{ URL::to('/assets/images/' . $ts->employee->avatar) }}">
                                                    </a>
                                                @else
                                                    <span class="avatar"
                                                        style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; background:#ccc; border-radius:50%;">
                                                        {{ strtoupper(substr($ts->employee->name ?? '', 0, 1)) }}
                                                    </span>
                                                @endif

                                                <a
                                                    href="{{ Route::has('employee.profile') && !empty($ts->employee->id) ? route('employee.profile', $ts->employee->id) : '#' }}">
                                                    {{ $ts->employee->name ?? '—' }}
                                                    @if (!empty($ts->employee->role))
                                                        <span>{{ $ts->employee->role }}</span>
                                                    @endif
                                                </a>
                                            </h2>

                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($ts->date)->format('d M, Y') }}</td>
                                        <td>
                                            <h2>{{ $ts->project }}</h2>
                                        </td>
                                        <td class="text-center">{{ $ts->total_hours }}</td>
                                        <td class="text-center">{{ $ts->hours }}</td>
                                        <td class="d-none d-sm-table-cell col-md-4">
                                            {{ \Illuminate\Support\Str::limit($ts->description, 120) }}</td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="dropdown-item edit-timesheet"
                                                        data-id="{{ $ts->id }}" data-toggle="modal"
                                                        data-target="#edit_todaywork">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>

                                                    <a href="#" class="dropdown-item open-delete-modal"
                                                        data-id="{{ $ts->id }}" data-toggle="modal"
                                                        data-target="#delete_workdetail">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No timesheets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add Today Work Modal -->
        <div id="add_todaywork" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Today Work details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/timesheet/save') }}" method="POST" id="add-timesheet-form">
                            @csrf

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Project <span class="text-danger">*</span></label>
                                    <select name="project" class="form-control select" required>
                                        <option value="">-- Select Project --</option>
                                        @foreach ($projects as $project_name)
                                            <option value="{{ $project_name->project_name }}"
                                                {{ old('project_name') == $project_name->project_name ? 'selected' : '' }}>
                                                {{ $project_name->project_name ?? ($project_name->title ?? 'Unknown') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Emplyee <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-control select" required>
                                        <option value="">-- Select User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name ?? ($user->title ?? 'Unknown') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>



                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label>Deadline</label>
                                    <div class="cal-icon">
                                        <input name="deadline" class="form-control datetimepicker" type="text"
                                            value="{{ old('deadline') }}">
                                    </div>
                                    @error('deadline')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-4">
                                    <label>Total Hours <span class="text-danger">*</span></label>
                                    <input name="total_hours" class="form-control" type="number" min="0"
                                        step="0.1" value="{{ old('total_hours', 0) }}" required>
                                    @error('total_hours')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-4">
                                    <label>Remaining Hours</label>
                                    <input name="remaining_hours" class="form-control" type="number" min="0"
                                        step="0.1" value="{{ old('remaining_hours') }}">
                                    @error('remaining_hours')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input name="date" class="form-control datetimepicker" type="text"
                                            value="{{ old('date', \Carbon\Carbon::now()->format('d M, Y')) }}" required>
                                    </div>
                                    @error('date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Hours <span class="text-danger">*</span></label>
                                    <input name="hours" class="form-control" type="number" min="0"
                                        step="0.1" value="{{ old('hours') }}" required>
                                    @error('hours')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
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
        <!-- /Add Today Work Modal -->
        <!-- Edit Today Work Modal -->
        <div id="edit_todaywork" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Work Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('form/timesheet/update') }}">
                            @csrf
                            <input type="hidden" name="id" id="edit_id">

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Project <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="project" id="edit_project"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label>Deadline</label>
                                    <input class="form-control" type="text" name="deadline" id="edit_deadline">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Total Hours</label>
                                    <input class="form-control" type="text" name="total_hours" id="edit_total_hours">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label>Remaining Hours</label>
                                    <input class="form-control" type="text" name="remaining_hours"
                                        id="edit_remaining_hours">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Date</label>
                                    <input class="form-control" type="text" name="date" id="edit_date">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Hours</label>
                                    <input class="form-control" type="text" name="hours" id="edit_hours">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea rows="4" class="form-control" name="description" id="edit_description"></textarea>
                            </div>

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Today Work Modal -->

        <!-- Delete Today Work Modal -->
        <div class="modal custom-modal fade" id="delete_workdetail" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="delete_timesheet_form" method="POST" action="{{ route('form/timesheet/delete') }}">
                        @csrf
                        <input type="hidden" name="id" id="timesheet_id" value="">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Work Details</h3>
                                <p>Are you sure you want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Delete Today Work Modal -->
    </div>
    <!-- /Page Wrapper -->

    @section('script')
        <script>
            $(function() {
                // for both deadline and date fields
                $('.datetimepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
            });
        </script>
        <script>
            $(document).on('click', '.edit-timesheet', function() {
                var id = $(this).data('id');
                var url = "{{ route('timesheet.edit', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#edit_id').val(data.id);
                        $('#edit_project').val(data.project);
                        $('#edit_deadline').val(data.deadline);
                        $('#edit_total_hours').val(data.total_hours);
                        $('#edit_remaining_hours').val(data.remaining_hours);
                        $('#edit_date').val(data.date);
                        $('#edit_hours').val(data.hours);
                        $('#edit_description').val(data.description);
                    },
                    error: function() {
                        alert('Failed to fetch record data.');
                    }
                });
            });
        </script>
        <script>
            $(document).on('click', '.open-delete-modal', function() {
                var id = $(this).data('id');
                $('#timesheet_id').val(id);


            });
        </script>
    @endsection
@endsection
