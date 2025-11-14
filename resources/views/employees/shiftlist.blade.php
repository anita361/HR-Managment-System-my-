@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Shift List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('all/employee/list') }}">Employees</a></li>
                            <li class="breadcrumb-item active">Shift List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn m-r-5" data-toggle="modal" data-target="#add_shift">Add
                            Shifts</a>
                        <a href="#" class="btn add-btn m-r-5" data-toggle="modal" data-target="#add_schedule"> Assign
                            Shifts</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Content Starts -->
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Shift Name</th>
                                    <th>Min Start Time</th>
                                    <th>Start Time</th>
                                    <th>Max Start Time</th>
                                    <th>Min End Time</th>
                                    <th>End Time</th>
                                    <th>Max End Time</th>
                                    <th>Break Time</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shifts as $key => $shift)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $shift->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($shift->min_start_time)->format('h:i:s a') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i:s a') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($shift->max_start_time)->format('h:i:s a') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($shift->min_end_time)->format('h:i:s a') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('h:i:s a') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($shift->max_end_time)->format('h:i:s a') }}</td>
                                        <td>{{ $shift->break_time_minutes }} mins</td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            <div class="action-label">
                                                @if (strtolower(trim($shift->status)) === 'active')
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-success"></i> Active
                                                    </a>
                                                @else
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-danger"></i> Inactive
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        {{-- Action --}}
                                        <td class="text-right">
                                            <div class="dropdown" style="position: relative;">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="dropdown-item edit-shift-btn"
                                                        data-id="{{ $shift->id }}" data-name="{{ $shift->name }}"
                                                        data-start_time="{{ $shift->start_time }}"
                                                        data-end_time="{{ $shift->end_time }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a href="{{ route('form/shiftscheduling/delete', $shift->id) }}"
                                                        class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete this shift?');">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">No shifts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Content End -->


        </div>
        <!-- /Page Content -->

        <!-- Add Shift Modal -->

        <div id="add_shift" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Shift</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <form method="POST" action="{{ route('form/shiftscheduling/store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Shift Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Min Start Time <span class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input type="text" name="min_start_time" class="form-control"
                                                value="{{ old('min_start_time') }}">
                                            <span class="input-group-append input-group-addon">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Time <span class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input type="text" name="start_time" class="form-control"
                                                value="{{ old('start_time') }}">
                                            <span class="input-group-append input-group-addon">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Max Start Time <span class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input type="text" name="max_start_time" class="form-control"
                                                value="{{ old('max_start_time') }}">
                                            <span class="input-group-append input-group-addon">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Min End Time <span class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input type="text" name="min_end_time" class="form-control"
                                                value="{{ old('min_end_time') }}">
                                            <span class="input-group-append input-group-addon">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Time <span class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input type="text" name="end_time" class="form-control"
                                                value="{{ old('end_time') }}">
                                            <span class="input-group-append input-group-addon">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Max End Time <span class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input type="text" name="max_end_time" class="form-control"
                                                value="{{ old('max_end_time') }}">
                                            <span class="input-group-append input-group-addon">
                                                <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Break Time (In Minutes)</label>
                                        <input type="number" min="0" name="break_time_minutes"
                                            class="form-control" value="{{ old('break_time_minutes') }}">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <!-- Hidden input first ensures a value exists even when unchecked -->
                                    <input type="hidden" name="recurring" value="0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="recurring_check"
                                            name="recurring" value="1" {{ old('recurring') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="recurring_check">Recurring Shift</label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Repeat Every</label>
                                        <select class="select form-control" name="repeat_every">
                                            <option value="">Select</option>
                                            <option value="1" {{ old('repeat_every') == '1' ? 'selected' : '' }}>1
                                            </option>
                                            <option value="2" {{ old('repeat_every') == '2' ? 'selected' : '' }}>2
                                            </option>
                                            <option value="3" {{ old('repeat_every') == '3' ? 'selected' : '' }}>3
                                            </option>
                                            <option value="4" {{ old('repeat_every') == '4' ? 'selected' : '' }}>4
                                            </option>
                                            <option value="5" {{ old('repeat_every') == '5' ? 'selected' : '' }}>5
                                            </option>
                                            <option value="6" {{ old('repeat_every') == '6' ? 'selected' : '' }}>6
                                            </option>
                                        </select>
                                        <label class="col-form-label">Week(s)</label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group wday-box">
                                        <!-- use days[] so Laravel receives an array -->
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="days[]" value="monday" class="days recurring"
                                                {{ in_array('monday', old('days', [])) ? 'checked' : '' }}>
                                            <span class="checkmark">M</span>
                                        </label>

                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="days[]" value="tuesday" class="days recurring"
                                                {{ in_array('tuesday', old('days', [])) ? 'checked' : '' }}>
                                            <span class="checkmark">T</span>
                                        </label>

                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="days[]" value="wednesday"
                                                class="days recurring"
                                                {{ in_array('wednesday', old('days', [])) ? 'checked' : '' }}>
                                            <span class="checkmark">W</span>
                                        </label>

                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="days[]" value="thursday"
                                                class="days recurring"
                                                {{ in_array('thursday', old('days', [])) ? 'checked' : '' }}>
                                            <span class="checkmark">T</span>
                                        </label>

                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="days[]" value="friday" class="days recurring"
                                                {{ in_array('friday', old('days', [])) ? 'checked' : '' }}>
                                            <span class="checkmark">F</span>
                                        </label>

                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="days[]" value="saturday"
                                                class="days recurring"
                                                {{ in_array('saturday', old('days', [])) ? 'checked' : '' }}>
                                            <span class="checkmark">S</span>
                                        </label>

                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="days[]" value="sunday" class="days recurring"
                                                {{ in_array('sunday', old('days', [])) ? 'checked' : '' }}>
                                            <span class="checkmark">S</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">End On</label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="end_on"
                                                value="{{ old('end_on') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <input type="hidden" name="indefinite" value="0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="indefinite_check"
                                            name="indefinite" value="1" {{ old('indefinite') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="indefinite_check">Indefinite</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Add Tag</label>
                                        <input type="text" name="tag" class="form-control"
                                            value="{{ old('tag') }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Add Note</label>
                                        <textarea class="form-control" name="note">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Shift Modal -->


        <!-- Edit Shift Modal -->
        <div id="edit_shift" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Shift</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editShiftForm" method="POST"
                            action="{{ route('form/shiftscheduling/update', $shift->id) }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $shift->id }}">
                            <div class="row">

                                <!-- Shift Name -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Shift Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>

                                <!-- Start Times -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Min Start Time</label>
                                        <input type="time" class="form-control" name="min_start_time">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <input type="time" class="form-control" name="start_time">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Max Start Time</label>
                                        <input type="time" class="form-control" name="max_start_time">
                                    </div>
                                </div>

                                <!-- End Times -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Min End Time</label>
                                        <input type="time" class="form-control" name="min_end_time">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <input type="time" class="form-control" name="end_time">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Max End Time</label>
                                        <input type="time" class="form-control" name="max_end_time">
                                    </div>
                                </div>

                                <!-- Break Time -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Break Time (In Minutes)</label>
                                        <input type="number" class="form-control" name="break_time_minutes">
                                    </div>
                                </div>

                                <!-- Recurring Shift -->
                                <div class="col-sm-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="recurring"
                                            name="recurring">
                                        <label class="custom-control-label" for="recurring">Recurring Shift</label>
                                    </div>
                                </div>

                                <!-- Repeat Every -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Repeat Every</label>
                                        <select class="form-control" name="repeat_every">
                                            @for ($i = 1; $i <= 6; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <span>Week(s)</span>
                                    </div>
                                </div>

                                <!-- Days -->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Days</label>
                                        <div class="wday-box">
                                            @php
                                                $weekDays = [
                                                    'monday' => 'M',
                                                    'tuesday' => 'T',
                                                    'wednesday' => 'W',
                                                    'thursday' => 'T',
                                                    'friday' => 'F',
                                                    'saturday' => 'S',
                                                    'sunday' => 'S',
                                                ];
                                            @endphp
                                            @foreach ($weekDays as $day => $label)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="days[]" value="{{ $day }}">
                                                    <span>{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- End On / Indefinite -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End On</label>
                                        <input type="date" class="form-control" name="end_on">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="indefinite"
                                            name="indefinite">
                                        <label class="custom-control-label" for="indefinite">Indefinite</label>
                                    </div>
                                </div>

                                <!-- Tag & Note -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Add Tag</label>
                                        <input type="text" class="form-control" name="tag">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Add Note</label>
                                        <textarea class="form-control" name="note"></textarea>
                                    </div>
                                </div>

                            </div>

                            <!-- Submit Button -->
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary">Update Shift</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Shift Modal -->

        <!-- Add Schedule Modal -->
        <div id="add_schedule" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Department <span
                                                class="text-danger">*</span></label>
                                        <select class="select">
                                            <option value="">Select</option>
                                            <option value="">Development</option>
                                            <option value="1">Finance</option>
                                            <option value="2">Finance and Management</option>
                                            <option value="3">Hr & Finance</option>
                                            <option value="4">ITech</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Employee Name <span
                                                class="text-danger">*</span></label>
                                        <select class="select">
                                            <option value="">Select </option>
                                            <option value="1">Richard Miles </option>
                                            <option value="2">John Smith</option>
                                            <option value="3">Mike Litorus </option>
                                            <option value="4">Wilmer Deluna</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Date</label>
                                        <div class="cal-icon"><input class="form-control datetimepicker" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Shifts <span class="text-danger">*</span></label>
                                        <select class="select">
                                            <option value="">Select </option>
                                            <option value="1">10'o clock Shift</option>
                                            <option value="2">10:30 shift</option>
                                            <option value="3">Daily Shift </option>
                                            <option value="4">New Shift</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Min Start Time <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input class="form-control"><span
                                                class="input-group-append input-group-addon"><span
                                                    class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Start Time <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input class="form-control"><span
                                                class="input-group-append input-group-addon"><span
                                                    class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Max Start Time <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input class="form-control"><span
                                                class="input-group-append input-group-addon"><span
                                                    class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Min End Time <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input class="form-control"><span
                                                class="input-group-append input-group-addon"><span
                                                    class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">End Time <span class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input class="form-control"><span
                                                class="input-group-append input-group-addon"><span
                                                    class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Max End Time <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group time timepicker">
                                            <input class="form-control"><span
                                                class="input-group-append input-group-addon"><span
                                                    class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Break Time <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Accept Extra Hours </label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                                checked="">
                                            <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Publish </label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch2"
                                                checked="">
                                            <label class="custom-control-label" for="customSwitch2"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Schedule Modal -->

        <!-- Delete Shift Modal -->
        <div class="modal custom-modal fade" id="delete_employee" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Shift</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal"
                                        class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Employee Modal -->

    </div>
    <!-- Page Wrapper -->
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
</script>


<script>
    $(function() {
        // Initialize time pickers (24-hour HH:mm)
        $('.timepicker').datetimepicker({
            format: 'HH:mm'
        });


        // Initialize date picker for 'end_on' — display like "08 Nov, 2025"
        $('.date-picker').datetimepicker({
            format: 'DD MMM, YYYY',
            useCurrent: false
        });
    });
</script>


<script>
    $(document).on('click', '.edit-shift-btn', function() {
        var modal = $('#edit_shift');

        modal.find('form').attr('action', '/shiftscheduling/update/' + $(this).data('id'));
        modal.find('input[name="name"]').val($(this).data('name'));
        modal.find('input[name="start_time"]').val($(this).data('start_time'));
        modal.find('input[name="end_time"]').val($(this).data('end_time'));
        // repeat for other fields if needed

        modal.modal('show');
    });
</script>
