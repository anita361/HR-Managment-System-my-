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
                                    <th>Days</th>
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
                                        <td>{{ $shift->days }}</td>

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
                                                @php
                                                    $daysArray = $shift->days
                                                        ? array_values(array_filter(array_map('trim', explode(',', $shift->days))))
                                                        : [];
                                                @endphp
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="javascript:void(0);" class="dropdown-item edit-shift-btn"
                                                        data-action="{{ route('form/shiftscheduling/update', $shift->id) }}"
                                                        data-id="{{ $shift->id }}" data-name="{{ $shift->name }}"
                                                        data-start_time="{{ $shift->start_time }}"
                                                        data-end_time="{{ $shift->end_time }}"
                                                        data-min_start_time="{{ $shift->min_start_time }}"
                                                        data-max_start_time="{{ $shift->max_start_time }}"
                                                        data-min_end_time="{{ $shift->min_end_time }}"
                                                        data-max_end_time="{{ $shift->max_end_time }}"
                                                        data-break_time_minutes="{{ $shift->break_time_minutes }}"
                                                        data-recurring="{{ $shift->recurring }}"
                                                        data-repeat_every="{{ $shift->repeat_every }}"
                                                         data-days='@json($daysArray)'
                                                        data-end_on="{{ $shift->end_on }}"
                                                        data-indefinite="{{ $shift->indefinite }}"
                                                        data-tag="{{ $shift->tag }}" data-note="{{ $shift->note }}">
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
                                        <select class="select">
                                            <option value="">1 </option>
                                            <option value="1">2</option>
                                            <option value="2">3</option>
                                            <option value="3">4</option>
                                            <option selected value="4">5</option>
                                            <option value="3">6</option>
                                        </select>
                                        <label class="col-form-label">Week(s)</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group wday-box">
                                        <label class="checkbox-inline"><input type="checkbox" value="monday"
                                                class="days recurring" checked=""><span
                                                class="checkmark">M</span></label>

                                        <label class="checkbox-inline"><input type="checkbox" value="tuesday"
                                                class="days recurring" checked=""><span
                                                class="checkmark">T</span></label>

                                        <label class="checkbox-inline"><input type="checkbox" value="wednesday"
                                                class="days recurring" checked=""><span
                                                class="checkmark">W</span></label>

                                        <label class="checkbox-inline"><input type="checkbox" value="thursday"
                                                class="days recurring" checked=""><span
                                                class="checkmark">T</span></label>

                                        <label class="checkbox-inline"><input type="checkbox" value="friday"
                                                class="days recurring" checked=""><span
                                                class="checkmark">F</span></label>

                                        <label class="checkbox-inline"><input type="checkbox" value="saturday"
                                                class="days recurring"><span class="checkmark">S</span></label>

                                        <label class="checkbox-inline"><input type="checkbox" value="sunday"
                                                class="days recurring"><span class="checkmark">S</span></label>
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
        <div id="edit_shift" class="modal custom-modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Shift</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form id="editShiftForm" method="POST" action="">
                            @csrf
                            <input type="hidden" name="id" id="shift_id">

                            <!-- Shift Name -->
                            <div class="form-group">
                                <label>Shift Name</label>
                                <input type="text" name="name" id="shift_name" class="form-control" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col">
                                    <label>Min Start Time</label>
                                    <input type="time" name="min_start_time" id="min_start_time"
                                        class="form-control">
                                </div>
                                <div class="form-group col">
                                    <label>Start Time</label>
                                    <input type="time" name="start_time" id="start_time" class="form-control">
                                </div>
                                <div class="form-group col">
                                    <label>Max Start Time</label>
                                    <input type="time" name="max_start_time" id="max_start_time"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col">
                                    <label>Min End Time</label>
                                    <input type="time" name="min_end_time" id="min_end_time" class="form-control">
                                </div>
                                <div class="form-group col">
                                    <label>End Time</label>
                                    <input type="time" name="end_time" id="end_time" class="form-control">
                                </div>
                                <div class="form-group col">
                                    <label>Max End Time</label>
                                    <input type="time" name="max_end_time" id="max_end_time" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Break Time (Minutes)</label>
                                <input type="number" name="break_time_minutes" id="break_time_minutes"
                                    class="form-control" min="0">
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="recurring" name="recurring"
                                        value="1">
                                    <label class="custom-control-label" for="recurring">Recurring Shift</label>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Repeat Every</label>
                                    <select class="form-control" name="repeat_every" id="repeat_every">
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <label class="col-form-label">Week(s)</label>
                                </div>
                            </div>

                            <!-- Days -->
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

                            <div class="col-sm-12">
                                <div class="form-group wday-box">
                                    @foreach ($weekDays as $day => $label)
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="{{ $day }}" class="day_checkbox"
                                                name="days[]">
                                            <span class="checkmark">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">End On</label>
                                    <div class="cal-icon">
                                        <input class="form-control datetimepicker" type="text" name="end_on"
                                            id="end_on" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="indefinite"
                                        name="indefinite" value="1">
                                    <label class="custom-control-label" for="indefinite">Indefinite</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Add Tag</label>
                                <input type="text" name="tag" id="tag" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Add Note</label>
                                <textarea name="note" id="note" class="form-control"></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary">Update Shift</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit Shift Modal -->




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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<script>
    $(function() {
        // Initialize time pickers (24-hour HH:mm)
        $('.timepicker').datetimepicker({
            format: 'HH:mm'
        });


        // Initialize date picker for 'end_on' — display like "08 Nov, 2025"
        // $('.date-picker').datetimepicker({
        //     format: 'DD MMM, YYYY',
        //     useCurrent: false
        // });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const checkboxes = document.querySelectorAll('.day_checkbox');
        const daysInput = document.getElementById('daysInput');
        const form = document.getElementById('editShiftForm');

        function updateDaysInput() {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            daysInput.value = selected.join(',');
        }


        checkboxes.forEach(cb => cb.addEventListener('change', updateDaysInput));


        form.addEventListener('submit', updateDaysInput);
    });
</script>

<script>
    $(document).on('click', '.edit-shift-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);

        $('#editShiftForm').attr('action', $btn.data('action'));
        $('#shift_id').val($btn.data('id'));
        $('#shift_name').val($btn.data('name') || '');
        $('#start_time').val($btn.data('start_time') || '');
        $('#end_time').val($btn.data('end_time') || '');
        $('#min_start_time').val($btn.data('min_start_time') || '');
        $('#max_start_time').val($btn.data('max_start_time') || '');
        $('#min_end_time').val($btn.data('min_end_time') || '');
        $('#max_end_time').val($btn.data('max_end_time') || '');
        $('#break_time_minutes').val($btn.data('break_time_minutes') || '');
        $('#repeat_every').val($btn.data('repeat_every') || '');
        $('#end_on').val($btn.data('end_on') || '');
        $('#tag').val($btn.data('tag') || '');
        $('#note').val($btn.data('note') || '');
        $('#recurring').prop('checked', $btn.data('recurring') == 1);
        $('#indefinite').prop('checked', $btn.data('indefinite') == 1);

        let raw = $btn.attr('data-days'); 
        let days = [];

        if (raw == null || raw === '') {
            days = [];
        } else {
    
            try {
                days = JSON.parse(raw);
                if (!Array.isArray(days)) days = [];
            } catch (err) {
                days = raw.split(',')
                        .map(s => s.replace(/^["'\s]+|["'\s]+$/g, ''))
                        .filter(Boolean);
            }
        }

        $('.day_checkbox').prop('checked', false);
        days.forEach(day => {
            $('.day_checkbox').each(function() {
                if ($(this).val() === day) {
                    $(this).prop('checked', true);
                }
            });
        });

        $('#edit_shift').modal('show');
    });
</script>
