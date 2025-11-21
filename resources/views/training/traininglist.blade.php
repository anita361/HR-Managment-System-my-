@extends('layouts.master')
@section('content')
    {{-- message --}}

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Training</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Training</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_training"><i
                                class="fa fa-plus"></i> Add New </a>
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
                                    <th style="width: 30px;">No</th>
                                    <th>Training Type</th>
                                    <th>Trainer</th>
                                    {{-- <th>Employee</th> --}}
                                    <th>Time Duration</th>
                                    <th>Description </th>
                                    <th>Cost </th>
                                    <th>Status </th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trainings as $training)
                                    <tr class="training-row" data-id="{{ $training->id }}"
                                        data-training_type="{{ $training->training_type }}"
                                        data-trainer_id="{{ $training->trainer_id }}"
                                        data-trainer="{{ $training->trainer }}"
                                        data-trainer_avatar="{{ $training->avatar }}"
                                        data-start_date="{{ $training->start_date }}"
                                        data-end_date="{{ $training->end_date }}"
                                        data-description="{{ $training->description }}"
                                        data-training_cost="{{ $training->training_cost }}"
                                        data-status="{{ $training->status }}" data-user_id="{{ $training->user_id }}">

                                        <td>{{ $loop->iteration }}</td>
                                        <td class="training_type">{{ $training->training_type }}</td>

                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ url('employee/profile/' . $training->trainer_user_id) }}"
                                                    class="avatar">
                                                    <img alt=""
                                                        src="{{ URL::to('/assets/images/' . $training->trainer_avatar) }}">
                                                </a>
                                                <a
                                                    href="{{ url('employee/profile/' . $training->trainer_user_id) }}">{{ $training->trainer_name }}</a>
                                            </h2>
                                        </td>

                                        {{-- <td>
                                            <ul class="team-members">
                                                @php
                                                    // Check if employees is a collection (relationship) or a comma-separated string
                                                    if (
                                                        $training->employees instanceof \Illuminate\Support\Collection
                                                    ) {
                                                        $employees = $training->employees;
                                                    } elseif (!empty($training->employees_id)) {
                                                        $ids = explode(',', $training->employees_id);
                                                        $employees = \App\Models\User::whereIn('user_id', $ids)->get();
                                                    } else {
                                                        $employees = collect();
                                                    }

                                                    $showAvatars = $employees->take(3);
                                                    $remaining = $employees->count() - $showAvatars->count();
                                                @endphp

                                                @foreach ($showAvatars as $emp)
                                                    <li>
                                                        <a href="{{ url('employee/profile/' . $emp->user_id) }}"
                                                            title="{{ $emp->name }}" data-toggle="tooltip">
                                                            <img alt="{{ $emp->name }}"
                                                                src="{{ asset('assets/images/' . $emp->avatar) }}">
                                                        </a>
                                                    </li>
                                                @endforeach

                                                @if ($remaining > 0)
                                                    <li class="dropdown avatar-dropdown">
                                                        <a href="#" class="all-users dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            +{{ $remaining }}
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <div class="avatar-group">
                                                                @foreach ($employees as $emp)
                                                                    <a class="avatar avatar-xs"
                                                                        href="{{ url('employee/profile/' . $emp->user_id) }}">
                                                                        <img alt="{{ $emp->name }}"
                                                                            src="{{ asset('assets/images/' . $emp->avatar) }}">
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </td> --}}

                                        <td>{{ $training->start_date }} - {{ $training->end_date }}</td>
                                        <td class="description">
                                            {{ \Illuminate\Support\Str::limit($training->description, 80) }}</td>
                                        <td>${{ number_format($training->training_cost, 2) }}</td>

                                        <td>
                                            @php $status = strtolower($training->status); @endphp
                                            <i
                                                class="fa fa-dot-circle-o 
                    {{ $status === 'completed' ? 'text-success' : ($status === 'pending' ? 'text-warning' : 'text-muted') }}">
                                            </i>
                                            {{ $training->status }}
                                        </td>

                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit_training" href="#"
                                                        data-toggle="modal" data-target="#edit_training"
                                                        data-id="{{ $training->id }}"
                                                        data-training-type="{{ $training->training_type }}"
                                                        data-trainer-id="{{ $training->trainer_id }}"
                                                        data-employees-id="{{ $training->employees_id }}"
                                                        data-training-cost="{{ $training->training_cost }}"
                                                        data-start-date="{{ $training->start_date }}"
                                                        data-end-date="{{ $training->end_date }}"
                                                        data-description="{{ $training->description }}"
                                                        data-status="{{ $training->status }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item delete_training" href="#"
                                                        data-toggle="modal" data-target="#delete_training">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add Training List Modal -->
        <div id="add_training" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Training</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('form.training.save') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Training Type</label>
                                        <select class="select form-control" name="training_type">
                                            <option value="" disabled {{ old('training_type') ? '' : 'selected' }}>
                                                -- Select --</option>
                                            <option value="Node Training"
                                                {{ old('training_type') == 'Node Training' ? 'selected' : '' }}>Node
                                                Training</option>
                                            <option value="Swift Training"
                                                {{ old('training_type') == 'Swift Training' ? 'selected' : '' }}>Swift
                                                Training</option>
                                        </select>
                                        @error('training_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Trainer</label>
                                        <select class="select form-control" id="trainer" name="trainer_id">
                                            <option value="" disabled {{ old('trainer_id') ? '' : 'selected' }}>--
                                                Select --</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('trainer_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('trainer_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Employees</label>
                                        <select class="select form-control" id="employees" name="employees_id">
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('employees_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small>Hold Ctrl / Cmd to select multiple</small>
                                        @error('employees_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @error('employees_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Training Cost <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="number" step="0.01" name="training_cost"
                                            value="{{ old('training_cost') }}">
                                        @error('training_cost')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label>Start Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input class="form-control datetimepicker" type="text" name="start_date"
                                            value="{{ old('start_date') }}">
                                    </div>
                                    @error('start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label>End Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input class="form-control datetimepicker" type="text" name="end_date"
                                            value="{{ old('end_date') }}">
                                    </div>
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-12 mt-2">
                                    <label>Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="3" name="description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-12 mt-2">
                                    <label class="col-form-label">Status</label>
                                    <select class="select form-control" name="status">
                                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="submit-section mt-3">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Training List Modal -->


        <!-- Edit Training List Modal -->
        <div id="edit_training" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Training List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- show validation errors after redirect -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('form/training/update') }}" method="POST" id="editTrainingForm">
                            @csrf
                            <input type="hidden" id="e_id" name="id" value="">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Training Type</label>
                                        <select class="select form-control" id="e_training_type" name="training_type"
                                            required>
                                            <option selected disabled>-- Select --</option>
                                            <option value="Node Training">Node Training</option>
                                            <option value="Swift Training">Swift Training</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Trainer</label>
                                        <select class="select form-control" id="e_trainer" name="trainer_id" required>
                                            <option selected disabled>-- Select --</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Employees</label>
                                        <select class="select form-control" id="e_employees" name="employees_id"
                                            required>
                                            <option selected disabled>-- Select --</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        {{-- If you need multiple employees: change name to employees_id[] and add multiple attribute,
                                    and update backend validation accordingly. --}}
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Training Cost <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="e_training_cost"
                                            name="training_cost" value="" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" id="e_start_date"
                                                name="start_date" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" id="e_end_date"
                                                name="end_date" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" rows="3" id="e_description" name="description"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Status</label>
                                        <select class="select form-control" id="e_status" name="status" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Training List Modal -->

        <!-- Delete Training List Modal -->
        <div class="modal custom-modal fade" id="delete_training" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Training List</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('form/training/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="e_id" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit"
                                            class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Training List Modal -->

    </div>
    <!-- /Page Wrapper -->
@section('script')
    {{-- update js --}}
    <script>
      $(document).on('click', '.edit_training', function (e) {
    var $btn = $(this);

    // DEBUG: print all data attributes to console (helps see what's available)
    console.log('edit button data:', $btn.data());

    // read values using camelCase keys (jQuery maps data-trainer-id -> trainerId)
    $('#e_id').val( $btn.data('id') ?? '' );
    $('#e_training_type').val( $btn.data('trainingType') ?? '' ).trigger('change');

    $('#e_trainer').val( $btn.data('trainerId') ?? '' ).trigger('change');
    $('#e_employees').val( $btn.data('employeesId') ?? '' ).trigger('change');

    $('#e_training_cost').val( $btn.data('trainingCost') ?? '' );
    $('#e_start_date').val( $btn.data('startDate') ?? '' );
    $('#e_end_date').val( $btn.data('endDate') ?? '' );
    $('#e_description').val( $btn.data('description') ?? '' );
    $('#e_status').val( $btn.data('status') ?? 'Active' ).trigger('change');
});
    </script>

    {{-- delete model --}}
    <script>
        $(document).on('click', '.delete_training', function() {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
@endsection
@endsection
