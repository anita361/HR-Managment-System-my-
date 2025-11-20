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
                        <h3 class="page-title">Performance Indicator</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Performance</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_indicator"><i
                                class="fa fa-plus"></i> Add New</a>
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
                                    <th style="width: 30px;">#</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Added By</th>
                                    <th>Create At</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($performance_indicators as $key => $performance)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td hidden class="id">{{ $performance->id }}</td>
                                        <td hidden class="designation">{{ $performance->designation }}</td>
                                        <td hidden class="customer_eperience">{{ $performance->customer_eperience }}</td>
                                        <td hidden class="marketing">{{ $performance->marketing }}</td>
                                        <td hidden class="management">{{ $performance->management }}</td>
                                        <td hidden class="administration">{{ $performance->administration }}</td>
                                        <td hidden class="presentation_skill">{{ $performance->presentation_skill }}</td>
                                        <td hidden class="quality_of_Work">{{ $performance->quality_of_Work }}</td>
                                        <td hidden class="efficiency">{{ $performance->efficiency }}</td>
                                        <td hidden class="integrity">{{ $performance->integrity }}</td>
                                        <td hidden class="professionalism">{{ $performance->professionalism }}</td>
                                        <td hidden class="team_work">{{ $performance->team_work }}</td>
                                        <td hidden class="critical_thinking">{{ $performance->critical_thinking }}</td>
                                        <td hidden class="conflict_management">{{ $performance->conflict_management }}</td>
                                        <td hidden class="attendance">{{ $performance->attendance }}</td>
                                        <td hidden class="ability_to_meet_deadline">
                                            {{ $performance->ability_to_meet_deadline }}</td>
                                        <td hidden class="status">{{ $performance->status }}</td>

                                        <td>{{ $performance->designation }}</td>
                                        <td>{{ $performance->department }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="profile.html" class="avatar"><img alt=""
                                                        src="{{ URL::to('/assets/images/' . $performance->avatar) }}"
                                                        alt="{{ $performance->avatar }}"></a>
                                                <a href="profile.html">{{ $performance->name }} </a>
                                            </h2>
                                        </td>
                                        <td>{{ date('d F, Y', strtotime($performance->created_at)) }}</td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-success"></i> Active
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit_indicator" href="#"
                                                        data-toggle="modal" data-target="#edit_indicator"
                                                        data-id="{{ $performance->id }}"
                                                        data-designation="{{ $performance->designation }}"
                                                        data-customer_eperience="{{ $performance->customer_eperience }}"
                                                        data-marketing="{{ $performance->marketing }}"
                                                        data-management="{{ $performance->management }}"
                                                        data-administration="{{ $performance->administration }}"
                                                        data-presentation_skill="{{ $performance->presentation_skill }}"
                                                        data-quality_of_Work="{{ $performance->quality_of_Work }}"
                                                        data-efficiency="{{ $performance->efficiency }}"
                                                        data-integrity="{{ $performance->integrity }}"
                                                        data-professionalism="{{ $performance->professionalism }}"
                                                        data-team_work="{{ $performance->team_work }}"
                                                        data-critical_thinking="{{ $performance->critical_thinking }}"
                                                        data-conflict_management="{{ $performance->conflict_management }}"
                                                        data-attendance="{{ $performance->attendance }}"
                                                        data-ability_to_meet_deadline="{{ $performance->ability_to_meet_deadline }}"
                                                        data-status="{{ $performance->status }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#delete_indicator_{{ $performance->id }}" data-toggle="modal">
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

        <!-- Add Performance Indicator Modal -->
        <div id="add_indicator" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Set New Indicator</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/performance/indicator/save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Session::get('user_id') }}">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Designation</label>
                                        <select class="select" id="designation" name="designation">
                                            <option selected disabled>--Select Designation--</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->department }}">
                                                    {{ $department->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="modal-sub-title">Technical</h4>
                                    <div class="form-group">
                                        <label class="col-form-label">Customer Experience</label>
                                        <select class="select" id="customer_eperience" name="customer_eperience">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Marketing</label>
                                        <select class="select" id="marketing" name="marketing">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Management</label>
                                        <select class="select" id="management" name="management">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Administration</label>
                                        <select class="select" id="administration" name="administration">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Presentation Skill</label>
                                        <select class="select" id="presentation_skill" name="presentation_skill">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Quality Of Work</label>
                                        <select class="select" id="quality_of_Work" name="quality_of_Work">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Efficiency</label>
                                        <select class="select" id="efficiency" name="efficiency">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="modal-sub-title">Organizational</h4>
                                    <div class="form-group">
                                        <label class="col-form-label">Integrity</label>
                                        <select class="select" id="integrity" name="integrity">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Professionalism</label>
                                        <select class="select" id="professionalism" name="professionalism">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Team Work</label>
                                        <select class="select" id="team_work" name="team_work">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Critical Thinking</label>
                                        <select class="select" id="critical_thinking" name="critical_thinking">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Conflict Management</label>
                                        <select class="select" id="conflict_management" name="conflict_management">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Attendance</label>
                                        <select class="select" id="attendance" name="attendance">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Ability To Meet Deadline</label>
                                        <select class="select" id="ability_to_meet_deadline"
                                            name="ability_to_meet_deadline">
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Status</label>
                                        <select class="select" id="status" name="status">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Performance Indicator Modal -->

        <!-- Edit Performance Indicator Modal -->
        <div id="edit_indicator" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Performance Indicator</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('form/performance/indicator/update') }}" method="POST">
                            @csrf
                            <input type="hidden" id="e_id" name="id" value="">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Designation</label>
                                        <select class="select" id="e_designation" name="designation">
                                            <option value="">-- Select Designation --</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->department }}">
                                                    {{ $department->department }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <h4 class="modal-sub-title">Technical</h4>

                                    <div class="form-group">
                                        <label class="col-form-label">Customer Experience</label>
                                        <select class="select" id="e_customer_eperience" name="customer_eperience">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Marketing</label>
                                        <select class="select" id="e_marketing" name="marketing">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Management</label>
                                        <select class="select" id="e_management" name="management">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Administration</label>
                                        <select class="select" id="e_administration" name="administration">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Presentation Skill</label>
                                        <select class="select" id="e_presentation_skill" name="presentation_skill">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Quality Of Work</label>
                                        <select class="select" id="e_quality_of_Work" name="quality_of_Work">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Efficiency</label>
                                        <select class="select" id="e_efficiency" name="efficiency">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <h4 class="modal-sub-title">Organizational</h4>

                                    <div class="form-group">
                                        <label class="col-form-label">Integrity</label>
                                        <select class="select" id="e_integrity" name="integrity">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Professionalism</label>
                                        <select class="select" id="e_professionalism" name="professionalism">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Team Work</label>
                                        <select class="select" id="e_team_work" name="team_work">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Critical Thinking</label>
                                        <select class="select" id="e_critical_thinking" name="critical_thinking">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Conflict Management</label>
                                        <select class="select" id="e_conflict_management" name="conflict_management">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Attendance</label>
                                        <select class="select" id="e_attendance" name="attendance">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">Ability To Meet Deadline</label>
                                        <select class="select" id="e_ability_to_meet_deadline"
                                            name="ability_to_meet_deadline">
                                            <option value="">-- Select --</option>
                                            @foreach ($indicator as $indicators)
                                                <option value="{{ $indicators->per_name_list }}">
                                                    {{ $indicators->per_name_list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-form-label">Status</label>
                                        <select class="select" id="e_status" name="status">
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
        <!-- /Edit Performance Indicator Modal -->

        <!-- Delete Modal (make sure id matches data-target) -->
        <div class="modal custom-modal fade" id="delete_indicator_{{ $performance->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Performance Indicator</h3>
                            <p>Are you sure you want to delete?</p>
                        </div>

                        <div class="modal-btn delete-action">
                            <form id="deleteIndicatorForm" action="{{ route('form/performance/indicator/delete') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $performance->id }}">

                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit"
                                            class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Performance Indicator Modal -->
    </div>
    <!-- /Page Wrapper -->

    {{-- update js --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click', '.edit_indicator', function(e) {
                e.preventDefault();
                var btn = $(this);

                // hidden id
                $('#e_id').val(btn.data('id'));

                // populate selects / inputs
                $('#e_designation').val(btn.data('designation')).trigger('change');

                $('#e_customer_eperience').val(btn.data('customer_eperience')).trigger('change');
                $('#e_marketing').val(btn.data('marketing')).trigger('change');
                $('#e_management').val(btn.data('management')).trigger('change');
                $('#e_administration').val(btn.data('administration')).trigger('change');
                $('#e_presentation_skill').val(btn.data('presentation_skill')).trigger('change');
                $('#e_quality_of_Work').val(btn.data('quality_of_work')).trigger('change');
                $('#e_efficiency').val(btn.data('efficiency')).trigger('change');

                $('#e_integrity').val(btn.data('integrity')).trigger('change');
                $('#e_professionalism').val(btn.data('professionalism')).trigger('change');
                $('#e_team_work').val(btn.data('team_work')).trigger('change');
                $('#e_critical_thinking').val(btn.data('critical_thinking')).trigger('change');
                $('#e_conflict_management').val(btn.data('conflict_management')).trigger('change');
                $('#e_attendance').val(btn.data('attendance')).trigger('change');
                $('#e_ability_to_meet_deadline').val(btn.data('ability_to_meet_deadline')).trigger(
                    'change');

                $('#e_status').val(btn.data('status')).trigger('change');

                // show modal if not already shown by data-toggle
                $('#edit_indicator').modal('show');
            });
        });
    </script>
    {{-- delete model --}}

@endsection
