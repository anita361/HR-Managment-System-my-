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
                    <div class="col-sm-12">
                        <h3 class="page-title">Performance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Performance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <section class="review-section information">
                <div class="review-header text-center">
                    <h3 class="review-title">Employee Basic Information</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>

                <form method="POST" action="{{ route('form.performance.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-nowrap review-table mb-0">
                                    <tbody>
                                        <tr>
                                            <!-- Column 1 -->
                                            <td>
                                                <div class="form-group">
                                                    <label class="form-group">Name</label>
                                                    <select class="form-control" id="employee_name" name="name">
                                                        <option value="">-- Select --</option>
                                                        @foreach ($userList as $key => $user)
                                                            <option value="{{ $user->name }}"
                                                                data-employee_id={{ $user->user_id }}
                                                                data-email={{ $user->email }}>{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select class="form-control" name="department" id="department">
                                                        <option selected disabled> --Select --</option>
                                                        @foreach ($department as $departments)
                                                            <option value="{{ $departments->department }}">
                                                                {{ $departments->department }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="form-group">
                                                    <label>Designation</label>
                                                    <select class="form-control" name="designation" id="position">
                                                        <option selected disabled> --Select --</option>
                                                        @foreach ($position as $positions)
                                                            <option value="{{ $positions->position }}">
                                                                {{ $positions->position }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="form-group">
                                                    <label>Qualification</label>
                                                    <input type="text" name="qualification" class="form-control">
                                                </div>
                                            </td>

                                            <!-- Column 2 -->
                                            <td>
                                                <div class="form-group">
                                                    <label>Emp ID</label>
                                                    <input type="text" name="emp_id" id="emp_id" class="form-control"
                                                        value="Auto generated" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Date of Join</label>
                                                    <input type="date" name="date_of_join" id="date_of_join"
                                                        class="form-control" value="Auto Fill" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Date of Confirmation</label>
                                                    <input type="date" name="date_of_confirmation"
                                                        class="form-control datetimepicker">
                                                </div>

                                                <div class="form-group">
                                                    <label>Previous Experience (Years)</label>
                                                    <input type="text" name="previous_experience" class="form-control">
                                                </div>
                                            </td>

                                            <!-- Column 3 (Optional – not stored yet) -->
                                            <td>
                                                <div class="form-group">
                                                    <label>RO's Name</label>
                                                    <input type="text" name="ro_name"class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label>RO Designation</label>
                                                    <input type="text" name="ro_designation" class="form-control">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary mt-3">
                                    Save Performance
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>


            <section class="review-section professional-excellence">
                <div class="review-header text-center">
                    <h3 class="review-title">Professional Excellence</h3>
                    <p class="text-muted">Fill in the self and RO scores for evaluation</p>
                </div>

                <form method="POST" action="{{ route('form.performance.prostore') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered review-table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th>Key Result Area</th>
                                            <th>Key Performance Indicators</th>
                                            <th>Weightage</th>
                                            <th>Percentage achieved <br>( Self Score )</th>
                                            <th>Points Scored <br>( Self )</th>
                                            <th>Percentage achieved <br>( RO's Score )</th>
                                            <th>Points Scored <br>( RO )</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Production -->
                                        <tr>
                                            <td rowspan="2">1</td>
                                            <td rowspan="2">Production</td>
                                            <td>Quality</td>
                                            <td><input type="text" name="weight_quality" class="form-control" readonly
                                                    value="30"></td>
                                            <td><input type="text" name="self_percentage_quality" class="form-control">
                                            </td>
                                            <td><input type="text" name="self_points_quality" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="ro_percentage_quality" class="form-control">
                                            </td>
                                            <td><input type="text" name="ro_points_quality" class="form-control" readonly
                                                    value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>TAT (turn around time)</td>
                                            <td><input type="text" name="weight_tat" class="form-control" readonly
                                                    value="30"></td>
                                            <td><input type="text" name="self_percentage_tat" class="form-control">
                                            </td>
                                            <td><input type="text" name="self_points_tat" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="ro_percentage_tat" class="form-control"></td>
                                            <td><input type="text" name="ro_points_tat" class="form-control" readonly
                                                    value="0"></td>
                                        </tr>

                                        <!-- Process Improvement -->
                                        <tr>
                                            <td>2</td>
                                            <td>Process Improvement</td>
                                            <td>PMS, New Ideas</td>
                                            <td><input type="text" name="weight_process" class="form-control" readonly
                                                    value="10"></td>
                                            <td><input type="text" name="self_percentage_process"
                                                    class="form-control"></td>
                                            <td><input type="text" name="self_points_process" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="ro_percentage_process" class="form-control">
                                            </td>
                                            <td><input type="text" name="ro_points_process" class="form-control"
                                                    readonly value="0"></td>
                                        </tr>

                                        <!-- Team Management -->
                                        <tr>
                                            <td>3</td>
                                            <td>Team Management</td>
                                            <td>Team Productivity, dynamics, attendance, attrition</td>
                                            <td><input type="text" name="weight_team" class="form-control" readonly
                                                    value="5"></td>
                                            <td><input type="text" name="self_percentage_team" class="form-control">
                                            </td>
                                            <td><input type="text" name="self_points_team" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="ro_percentage_team" class="form-control">
                                            </td>
                                            <td><input type="text" name="ro_points_team" class="form-control" readonly
                                                    value="0"></td>
                                        </tr>

                                        <!-- Knowledge Sharing -->
                                        <tr>
                                            <td>4</td>
                                            <td>Knowledge Sharing</td>
                                            <td>Sharing knowledge for team productivity</td>
                                            <td><input type="text" name="weight_knowledge" class="form-control"
                                                    readonly value="5"></td>
                                            <td><input type="text" name="self_percentage_knowledge"
                                                    class="form-control"></td>
                                            <td><input type="text" name="self_points_knowledge" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="ro_percentage_knowledge"
                                                    class="form-control"></td>
                                            <td><input type="text" name="ro_points_knowledge" class="form-control"
                                                    readonly value="0"></td>
                                        </tr>

                                        <!-- Reporting and Communication -->
                                        <tr>
                                            <td>5</td>
                                            <td>Reporting and Communication</td>
                                            <td>Emails/Calls/Reports and Other Communication</td>
                                            <td><input type="text" name="weight_reporting" class="form-control"
                                                    readonly value="5"></td>
                                            <td><input type="text" name="self_percentage_reporting"
                                                    class="form-control"></td>
                                            <td><input type="text" name="self_points_reporting" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="ro_percentage_reporting"
                                                    class="form-control"></td>
                                            <td><input type="text" name="ro_points_reporting" class="form-control"
                                                    readonly value="0"></td>
                                        </tr>

                                        <!-- Total -->
                                        <tr>
                                            <td colspan="3" class="text-center">Total</td>
                                            <td><input type="text" class="form-control" readonly value="85"></td>
                                            <td><input type="text" name="total_self_percentage" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="total_self_points" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="total_ro_percentage" class="form-control"
                                                    readonly value="0"></td>
                                            <td><input type="text" name="total_ro_points" class="form-control"
                                                    readonly value="0"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary mt-3">Save Professional Excellence</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>




            <section class="review-section personal-excellence">
                <div class="review-header text-center">
                    <h3 class="review-title">Personal Excellence</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Personal Attributes</th>
                                        <th>Key Indicators</th>
                                        <th>Weightage</th>
                                        <th>Percentage achieved <br>( self Score )</th>
                                        <th>Points Scored <br>( self )</th>
                                        <th>Percentage achieved <br>( RO's Score )</th>
                                        <th>Points Scored <br>( RO )</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2">1</td>
                                        <td rowspan="2">Attendance</td>
                                        <td>Planned or Unplanned Leaves</td>
                                        <td><input type="text" class="form-control" readonly value="2"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Time Consciousness</td>
                                        <td><input type="text" class="form-control" readonly value="2"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">2</td>
                                        <td rowspan="2">Attitude & Behavior</td>
                                        <td>Team Collaboration</td>
                                        <td><input type="text" class="form-control" readonly value="2"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>Professionalism & Responsiveness</td>
                                        <td><input type="text" class="form-control" readonly value="2"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Policy & Procedures </td>
                                        <td>Adherence to policies and procedures</td>
                                        <td><input type="text" class="form-control" readonly value="2"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Initiatives</td>
                                        <td>Special Efforts, Suggestions,Ideas,etc.</td>
                                        <td><input type="text" class="form-control" readonly value="2"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Continuous Skill Improvement</td>
                                        <td>Preparedness to move to next level & Training utilization</td>
                                        <td><input type="text" class="form-control" readonly value="3"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-center">Total </td>
                                        <td><input type="text" class="form-control" readonly value="15"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-center"><b>Total Percentage(%)</b></td>
                                        <td colspan="5" class="text-center"><input type="text"
                                                class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="grade-span">
                                                <h4>Grade</h4>
                                                <span class="badge bg-inverse-danger">Below 65 Poor</span>
                                                <span class="badge bg-inverse-warning">65-74 Average</span>
                                                <span class="badge bg-inverse-info">75-84 Satisfactory</span>
                                                <span class="badge bg-inverse-purple">85-92 Good</span>
                                                <span class="badge bg-inverse-success">Above 92 Excellent</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Special Initiatives, Achievements, contributions if any</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-review review-table mb-0" id="table_achievements">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>By Self</th>
                                        <th>RO's Comment</th>
                                        <th>HOD's Comment</th>
                                        <th style="width: 64px;"><button type="button"
                                                class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table_achievements_tbody">
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>


            
            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Comments on the role</h3>
                    <p class="text-muted">alterations if any requirred like addition/deletion of responsibilities</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-review review-table mb-0" id="table_alterations">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>By Self</th>
                                        <th>RO's Comment</th>
                                        <th>HOD's Comment</th>
                                        <th style="width: 64px;"><button type="button"
                                                class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table_alterations_tbody">
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Comments on the role</h3>
                    <p class="text-muted">alterations if any requirred like addition/deletion of responsibilities</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Strengths</th>
                                        <th>Area's for Improvement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Appraisee's Strengths and Areas for Improvement perceived by the Reporting
                        officer</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Strengths</th>
                                        <th>Area's for Improvement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Appraisee's Strengths and Areas for Improvement perceived by the Head of the
                        Department</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Strengths</th>
                                        <th>Area's for Improvement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Personal Goals</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Goal Achieved during last year</th>
                                        <th>Goal set for current year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Personal Updates</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Last Year</th>
                                        <th>Yes/No</th>
                                        <th>Details</th>
                                        <th>Current Year</th>
                                        <th>Yes/No</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Married/Engaged?</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                        <td>Marriage Plans</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Higher Studies/Certifications?</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                        <td>Plans For Higher Study</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Health Issues?</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                        <td>Certification Plans</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Others</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                        <td>Others</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Professional Goals Achieved for last year</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-review review-table mb-0" id="table_goals">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>By Self</th>
                                        <th>RO's Comment</th>
                                        <th>HOD's Comment</th>
                                        <th style="width: 64px;"><button type="button"
                                                class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table_goals_tbody">
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Professional Goals for the forthcoming year</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-review review-table mb-0" id="table_forthcoming">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>By Self</th>
                                        <th>RO's Comment</th>
                                        <th>HOD's Comment</th>
                                        <th style="width: 64px;"><button type="button"
                                                class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table_forthcoming_tbody">
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Training Requirements</h3>
                    <p class="text-muted">if any to achieve the Performance Standard Targets completely</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-review review-table mb-0" id="table_targets">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>By Self</th>
                                        <th>RO's Comment</th>
                                        <th>HOD's Comment</th>
                                        <th style="width: 64px;"><button type="button"
                                                class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="table_targets_tbody">
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Any other general comments, observations, suggestions etc.</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-review review-table mb-0" id="general_comments">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Self</th>
                                        <th>RO</th>
                                        <th>HOD</th>
                                        <th style="width: 64px;"><button type="button"
                                                class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="general_comments_tbody">
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">For RO's Use Only</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Yes/No</th>
                                        <th>If Yes - Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>The Team member has Work related Issues</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>The Team member has Leave Issues</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>The team member has Stability Issues</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>The Team member exhibits non-supportive attitude</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Any other points in specific to note about the team member</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Overall Comment /Performance of the team member</td>
                                        <td>
                                            <select class="form-control select">
                                                <option>Select</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">For HRD's Use Only</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Overall Parameters</th>
                                        <th>Available Points</th>
                                        <th>Points Scored</th>
                                        <th>RO's Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>KRAs Target Achievement Points (will be considered from the overall score
                                            specified in this document by the Reporting officer)</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Professional Skills Scores(RO's Points furnished in the skill & attitude
                                            assessment sheet will be considered)</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Personal Skills Scores(RO's Points furnished in the skill & attitude assessment
                                            sheet will be considered)</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Special Achievements Score (HOD to furnish)</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Overall Total Score</td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                        <td><input type="text" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered review-table mb-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Signature</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Employee</td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td>Reporting Officer</td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td>HOD</td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td>HRD</td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                    <td><input type="text" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@section('script')
    <!-- Add Table Row JS -->
    <script>
        $(function() {
            $(document).on("click", '.btn-add-row', function() {
                var id = $(this).closest("table.table-review").attr('id'); // Id of particular table
                console.log(id);
                var div = $("<tr />");
                div.html(GetDynamicTextBox(id));
                $("#" + id + "_tbody").append(div);
            });
            $(document).on("click", "#comments_remove", function() {
                $(this).closest("tr").prev().find('td:last-child').html(
                    '<button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button>'
                );
                $(this).closest("tr").remove();
            });

            function GetDynamicTextBox(table_id) {
                $('#comments_remove').remove();
                var rowsLength = document.getElementById(table_id).getElementsByTagName("tbody")[0]
                    .getElementsByTagName("tr").length + 1;
                return '<td>' + rowsLength + '</td>' +
                    '<td><input type="text" name = "DynamicTextBox" class="form-control" value = "" ></td>' +
                    '<td><input type="text" name = "DynamicTextBox" class="form-control" value = "" ></td>' +
                    '<td><input type="text" name = "DynamicTextBox" class="form-control" value = "" ></td>' +
                    '<td><button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button></td>'
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#employee_name').on('change', function() {
                var name = $(this).val();

                if (name !== '') {
                    $.ajax({
                        url: '/all/employee/employee-info',
                        type: 'GET',
                        data: {
                            name: name
                        },
                        success: function(response) {
                            $('#emp_id').val(response.emp_id || 'Auto generated');
                            if (response.join_date) {
                                let date = new Date(response.join_date);
                                let formattedDate = date.toISOString().split('T')[0];
                                $('#date_of_join').val(formattedDate);
                            } else {
                                $('#date_of_join').val('');
                            }
                        },
                        error: function() {
                            alert('Error fetching employee data');
                        }
                    });
                } else {
                    $('#emp_id').val('Auto generated');
                    $('#date_of_join').val('');
                }
            });
        });
    </script>
@endsection
@endsection
