@extends('layouts.master')

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Timesheet</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') ?? url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Timesheet</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_todaywork">
                        <i class="fa fa-plus"></i> Add Today Work
                    </a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Table -->
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th class="text-center">Assigned Hours</th>
                                <th class="text-center">Hours</th>
                                <th class="d-none d-sm-table-cell">Description</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($timesheets as $t)
                                <tr>
                                    <td>
                                        <h2 class="table-avatar">
                                            @if($t->user && $t->user->avatar)
                                                <a href="#" class="avatar"><img alt="" src="{{ asset('storage/'.$t->user->avatar) }}"></a>
                                            @else
                                                <a href="#" class="avatar"><img alt="" src="{{ asset('assets/img/profiles/avatar-01.jpg') }}"></a>
                                            @endif
                                            <a href="#">{{ $t->user->name ?? '—' }} <span>{{ $t->user->designation ?? '' }}</span></a>
                                        </h2>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($t->date)->format('d M Y') }}</td>
                                    <td><h2>{{ $t->project }}</h2></td>
                                    <td class="text-center">{{ $t->total_hours ?? '-' }}</td>
                                    <td class="text-center">{{ $t->hours }}</td>
                                    <td class="d-none d-sm-table-cell col-md-4">{{ \Illuminate\Support\Str::limit($t->description, 80) }}</td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item edit-btn"
                                                   href="#"
                                                   data-toggle="modal"
                                                   data-target="#edit_todaywork"
                                                   data-id="{{ $t->id }}"
                                                   data-project="{{ e($t->project) }}"
                                                   data-deadline="{{ optional($t->deadline)->format('Y-m-d') }}"
                                                   data-total_hours="{{ $t->total_hours }}"
                                                   data-remaining_hours="{{ $t->remaining_hours }}"
                                                   data-date="{{ $t->date->format('Y-m-d') }}"
                                                   data-hours="{{ $t->hours }}"
                                                   data-description="{{ e($t->description) }}"
                                                >
                                                    <i class="fa fa-pencil m-r-5"></i> Edit
                                                </a>

                                                <a class="dropdown-item delete-btn"
                                                   href="#"
                                                   data-toggle="modal"
                                                   data-target="#delete_workdetail"
                                                   data-id="{{ $t->id }}"
                                                >
                                                    <i class="fa fa-trash-o m-r-5"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No timesheets found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Table -->
    </div>
</div>

<!-- Add Today Work Modal -->
<div id="add_todaywork" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('form/timesheet/save') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Today Work details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Project <span class="text-danger">*</span></label>
                            <select class="form-control select" name="project" required>
                                <option value="">Select Project</option>
                                <option value="Office Management">Office Management</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Video Calling App">Video Calling App</option>
                                <option value="Hospital Administration">Hospital Administration</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label>Deadline</label>
                            <div class="cal-icon">
                                <input class="form-control" type="date" name="deadline" value="">
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label>Total Hours</label>
                            <input class="form-control" type="number" name="total_hours" value="">
                        </div>

                        <div class="form-group col-sm-4">
                            <label>Remaining Hours</label>
                            <input class="form-control" type="number" name="remaining_hours" value="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Date <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control" type="date" name="date" required>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label>Hours <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" name="hours" step="0.25" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea rows="4" class="form-control" name="description" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add Today Work Modal -->

<!-- Edit Today Work Modal -->
<div id="edit_todaywork" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('form/timesheet/update') }}" method="POST">
                @csrf
                <!-- if your update route expects PUT, add: @method('PUT') -->
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Work Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Project <span class="text-danger">*</span></label>
                            <select class="form-control select" name="project" id="edit_project" required>
                                <option value="">Select Project</option>
                                <option value="Office Management">Office Management</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Video Calling App">Video Calling App</option>
                                <option value="Hospital Administration">Hospital Administration</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label>Deadline</label>
                            <div class="cal-icon">
                                <input class="form-control" type="date" name="deadline" id="edit_deadline">
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label>Total Hours</label>
                            <input class="form-control" type="number" name="total_hours" id="edit_total_hours">
                        </div>

                        <div class="form-group col-sm-4">
                            <label>Remaining Hours</label>
                            <input class="form-control" type="number" name="remaining_hours" id="edit_remaining_hours">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Date <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                                <input class="form-control" type="date" name="date" id="edit_date" required>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label>Hours <span class="text-danger">*</span></label>
                            <input class="form-control" type="number" name="hours" id="edit_hours" step="0.25" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea rows="4" class="form-control" name="description" id="edit_description" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Today Work Modal -->

<!-- Delete confirmation Modal -->
<div class="modal custom-modal fade" id="delete_workdetail" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('form/timesheet/delete') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Work Details</h3>
                        <p>Are you sure you want to delete this timesheet?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-danger continue-btn">Delete</button>
                            </div>
                            <div class="col-6">
                                <button type="button" data-dismiss="modal" class="btn btn-secondary cancel-btn">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Delete Modal -->

@endsection

@section('script')
<script>
    $(function () {
        // populate edit modal when an Edit button is clicked
        $('.edit-btn').on('click', function () {
            var el = $(this);
            $('#edit_id').val(el.data('id'));
            $('#edit_project').val(el.data('project'));
            $('#edit_deadline').val(el.data('deadline') || '');
            $('#edit_total_hours').val(el.data('total_hours') || '');
            $('#edit_remaining_hours').val(el.data('remaining_hours') || '');
            $('#edit_date').val(el.data('date') || '');
            $('#edit_hours').val(el.data('hours') || '');
            $('#edit_description').val(el.data('description') || '');
        });

        // populate delete modal id
        $('.delete-btn').on('click', function () {
            $('#delete_id').val($(this).data('id'));
        });

        // optional: init any datetimepicker you use
        if ($.fn.datetimepicker) {
            $('.datetimepicker').datetimepicker({ format: 'YYYY-MM-DD' });
        }
    });
</script>
@endsection
