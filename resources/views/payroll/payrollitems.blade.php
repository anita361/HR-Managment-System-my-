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
                        <h3 class="page-title">Payslip</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Payroll Items</a></li>
                            <li class="breadcrumb-item active">Payroll Items</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Tab -->
            <div class="page-menu">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab_additions">Additions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_overtime">Overtime</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab_deductions">Deductions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Tab -->

            <!-- Tab Content -->
            <div class="tab-content">

                <!-- Additions Tab -->
                <div class="tab-pane show active" id="tab_additions">

                    <!-- Add Addition Button -->
                    <div class="text-right mb-4 clearfix">
                        <button class="btn btn-primary add-btn" type="button" data-toggle="modal"
                            data-target="#add_addition"><i class="fa fa-plus"></i> Add Addition</button>
                    </div>
                    <!-- /Add Addition Button -->

                    <!-- Payroll Additions Table -->
                    <div class="payroll-table card">
                        <div class="table-responsive">
                            <table class="table table-hover table-radius">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Default/Unit Amount</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payrollItems as $item)
                                        <tr>
                                            <td class="name">{{ $item->name }}</td>
                                            <td class="category">{{ $item->category }}</td>
                                            <td class="unit_amount">${{ number_format($item->unit_amount, 2) }}</td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit-addition-btn" href="#"
                                                            data-toggle="modal" data-target="#edit_addition"
                                                            data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-category="{{ $item->category }}"
                                                            data-unit_calculation="{{ $item->unit_calculation ? 1 : 0 }}"
                                                            data-unit_amount="{{ $item->unit_amount }}"
                                                            data-assignee="{{ $item->assignee }}"
                                                            data-employee_id="{{ $item->employee_id }}">
                                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item delete-addition-btn" href="#"
                                                            data-id="{{ $item->id }}">
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
                    <!-- /Payroll Additions Table -->

                </div>
                <!-- Additions Tab -->

                <!-- Overtime Tab -->
                <div class="tab-pane" id="tab_overtime">

                    <!-- Add Overtime Button -->
                    <div class="text-right mb-4 clearfix">
                        <button class="btn btn-primary add-btn" type="button" data-toggle="modal"
                            data-target="#add_overtime"><i class="fa fa-plus"></i> Add Overtime</button>
                    </div>
                    <!-- /Add Overtime Button -->

                    <!-- Payroll Overtime Table -->
                    <div class="payroll-table card">
                        <div class="table-responsive">
                            <table class="table table-hover table-radius">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Rate</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payrollOvertimes as $overtime)
                                        <tr>
                                            <td>{{ $overtime->name }}</td>

                                            {{-- If rate is stored as numeric or string adjust display as needed --}}
                                            <td>
                                                @if (isset($overtime->rate))
                                                    {{-- show exact value --}}
                                                    {{ $overtime->rate }}
                                                @elseif(isset($overtime->multiplier))
                                                    {{-- alternative column name --}}
                                                    Hourly {{ $overtime->multiplier }}
                                                @else
                                                    {{-- fallback --}}
                                                    N/A
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        {{-- Edit: we include data attributes so a JS modal can populate fields --}}
                                                        <a class="dropdown-item edit-overtime-btn" href="#"
                                                            data-toggle="modal" data-target="#edit_overtime"
                                                            data-id="{{ $overtime->id }}"
                                                            data-name="{{ $overtime->name }}"
                                                            data-rate-type="{{ $overtime->rate_type ?? '' }}"
                                                            data-rate="{{ $overtime->rate ?? ($overtime->multiplier ?? '') }}">
                                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                                        </a>


                                                        {{-- Delete: you can either open a modal or call a route --}}
                                                        <a class="dropdown-item delete-overtime-btn" href="#"
                                                            data-toggle="modal" data-target="#delete_overtime"
                                                            data-id="{{ $overtime->id }}">
                                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No payroll overtime items found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /Payroll Overtime Table -->

                </div>
                <!-- /Overtime Tab -->

                <!-- Deductions Tab -->
                <div class="tab-pane" id="tab_deductions">

                    <!-- Add Deductions Button -->
                    <div class="text-right mb-4 clearfix">
                        <button class="btn btn-primary add-btn" type="button" data-toggle="modal"
                            data-target="#add_deduction"><i class="fa fa-plus"></i> Add Deduction</button>
                    </div>
                    <!-- /Add Deductions Button -->

                    <!-- Payroll Deduction Table -->
                    <div class="payroll-table card">
                        <div class="table-responsive">
                            <table class="table table-hover table-radius">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Default/Unit Amount</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deductions as $deduction)
                                        <tr>
                                            <th>{{ $deduction->name }}</th>
                                            <td>${{ number_format($deduction->amount, 2) }}</td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <!-- Edit Button -->
                                                        <a class="dropdown-item edit-btn" href="#"
                                                            data-toggle="modal" data-target="#edit_deduction"
                                                            data-id="{{ $deduction->id }}"
                                                            data-name="{{ $deduction->name }}"
                                                            data-amount="{{ $deduction->amount }}">
                                                            <i class="fa fa-pencil m-r-5 text-primary"></i> Edit
                                                        </a>

                                                        <!-- Divider -->
                                                        <div class="dropdown-divider"></div>

                                                        <!-- Delete Button -->
                                                        <form
                                                            action="{{ route('payroll.deductions.destroy', $deduction->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this deduction?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No deductions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /Payroll Deduction Table -->

                </div>
                <!-- /Deductions Tab -->

            </div>
            <!-- Tab Content -->
        </div>
        <!-- /Page Content -->

        <!-- Add Addition Modal -->
        <div id="add_addition" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Addition</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('form/payroll/addition/store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required>
                            </div>

                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select class="select" name="category" required>
                                    <option value="">Select a category</option>
                                    <option value="Monthly remuneration">Monthly remuneration</option>
                                    <option value="Additional remuneration">Additional remuneration</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <div class="status-toggle">
                                    <input type="checkbox" id="unit_calculation" class="check" name="unit_calculation"
                                        value="1">
                                    <label for="unit_calculation" class="checktoggle">checkbox</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Unit Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" class="form-control"
                                        name="unit_amount">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="d-block">Assignee</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assignee" id="addition_no_emp"
                                        value="none" checked>
                                    <label class="form-check-label" for="addition_no_emp">
                                        No assignee
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assignee" id="addition_all_emp"
                                        value="all">
                                    <label class="form-check-label" for="addition_all_emp">
                                        All employees
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assignee"
                                        id="addition_single_emp" value="single">
                                    <label class="form-check-label" for="addition_single_emp">
                                        Select Employee
                                    </label>
                                </div>

                                <div class="form-group mt-2">
                                    <select class="select" name="employee_id">
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
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
        <!-- /Add Addition Modal -->

        <!-- Edit Addition Modal (keep only one copy on page) -->
        <div id="edit_addition" class="modal custom-modal fade" role="dialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Addition</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('form/payroll/addition/update') }}" method="POST" id="editAdditionForm">
                            @csrf
                            <input type="hidden" name="id" id="edit_addition_id">

                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="edit_addition_name"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select class="select form-control" name="category" id="edit_addition_category" required>
                                    <option value="">Select a category</option>
                                    <option value="Monthly remuneration">Monthly remuneration</option>
                                    <option value="Additional remuneration">Additional remuneration</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <!-- Hidden default so checkbox always submits either 0 or 1 -->
                                <input type="hidden" name="unit_calculation" value="0">
                                <input type="checkbox" id="edit_unit_calculation" name="unit_calculation" value="1"
                                    class="mr-2">
                                <label for="edit_unit_calculation">Use unit calculation</label>
                            </div>

                            <div class="form-group">
                                <label>Unit Amount</label>
                                <input type="text" class="form-control" name="unit_amount"
                                    id="edit_addition_unit_amount" placeholder="e.g. 100.00">
                            </div>

                            <div class="form-group">
                                <label class="d-block">Assignee</label>
                                <label class="mr-2"><input type="radio" name="assignee" value="no"
                                        class="edit_assignee"> No assignee</label>
                                <label class="mr-2"><input type="radio" name="assignee" value="all"
                                        class="edit_assignee"> All employees</label>
                                <label><input type="radio" name="assignee" value="single" class="edit_assignee">
                                    Select Employee</label>
                            </div>

                            <div class="form-group">
                                <select class="select form-control" name="employee_id" id="edit_addition_employee"
                                    disabled>
                                    <option value="">-</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn" type="submit">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Delete Addition Modal -->
        <div class="modal custom-modal fade" id="delete_addition" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">

                        <form method="POST" action="{{ route('form/payroll/addition/delete') }}">
                            @csrf

                            <div class="form-header">
                                <h3>Delete Addition</h3>
                                <p>Are you sure want to delete?</p>
                            </div>

                            <input type="hidden" name="id" id="delete_addition_id">

                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn w-100">
                                            Delete
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn w-100">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Addition Modal -->

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
                        <form action="{{ route('form/payroll/overtime/add') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Rate Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="rate_type" required>
                                    <option value="">-</option>
                                    <option value="Daily Rate">Daily Rate</option>
                                    <option value="Hourly Rate">Hourly Rate</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Rate <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" step="0.01" name="rate" required>

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
        <div id="edit_overtime" class="modal custom-modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Overtime</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="editOvertimeForm" method="POST" action="{{ route('form/payroll/overtime/update') }}">
                            @csrf
                            <input type="hidden" name="id" id="overtime_id">

                            <!-- Name -->
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="overtime_name" required>
                            </div>

                            <!-- Rate Type -->
                            <div class="form-group">
                                <label>Rate Type <span class="text-danger">*</span></label>
                                <select class="select form-control" name="rate_type" id="overtime_rate_type" required>
                                    <option value="">Select Rate Type</option>
                                    <option value="Daily Rate">Daily Rate</option>
                                    <option value="Hourly Rate">Hourly Rate</option>
                                </select>
                            </div>

                            <!-- Rate -->
                            <div class="form-group">
                                <label>Rate <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input class="form-control" type="number" step="0.01" name="rate" id="overtime_rate" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">
                                    Save
                                </button>
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
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Overtime</h3>
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
        <!-- /Delete Overtime Modal -->

        <!-- Add Deduction Modal -->
        <div id="add_deduction" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Deduction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="deductionForm" action="{{ route('payroll.deductions.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id" id="deduction_id"> <!-- used for edit if needed -->

                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <div class="status-toggle">
                                    <input type="checkbox" id="unit_calculation_deduction" class="check"
                                        name="unit_calculation">
                                    <label for="unit_calculation_deduction" class="checktoggle">checkbox</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Unit Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control" name="unit_amount" id="unit_amount"
                                        value="{{ old('unit_amount') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="d-block">Assignee</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assignee" id="deduction_no_emp"
                                        value="none" checked>
                                    <label class="form-check-label" for="deduction_no_emp">No assignee</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assignee"
                                        id="deduction_all_emp" value="all">
                                    <label class="form-check-label" for="deduction_all_emp">All employees</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="assignee"
                                        id="deduction_single_emp" value="single">
                                    <label class="form-check-label" for="deduction_single_emp">Select Employee</label>
                                </div>

                                <div class="form-group mt-2" id="employee_select_wrapper" style="display:none;">
                                    <select class="form-control" name="employee_id" id="employee_id">
                                        <option value="">-</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Deduction Modal -->

        <!-- Edit Deduction Modal -->
        <div id="edit_deduction" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Deduction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Unit calculation</label>
                                <div class="status-toggle">
                                    <input type="checkbox" id="edit_unit_calculation_deduction" class="check">
                                    <label for="edit_unit_calculation_deduction" class="checktoggle">checkbox</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unit Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Assignee</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_deduction_assignee"
                                        id="edit_deduction_no_emp" value="option1" checked>
                                    <label class="form-check-label" for="edit_deduction_no_emp">
                                        No assignee
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_deduction_assignee"
                                        id="edit_deduction_all_emp" value="option2">
                                    <label class="form-check-label" for="edit_deduction_all_emp">
                                        All employees
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_deduction_assignee"
                                        id="edit_deduction_single_emp" value="option3">
                                    <label class="form-check-label" for="edit_deduction_single_emp">
                                        Select Employee
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select class="select">
                                        <option value="">-</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Addition Modal -->

        <!-- Delete Deduction Modal -->
        <div class="modal custom-modal fade" id="delete_deduction" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Deduction</h3>
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
        <!-- /Delete Deduction Modal -->
    </div>
    <!-- /Page Content -->
@endsection
<script src="{{ URL::to('assets/js/jquery-3.5.1.min.js') }}"></script>


<script>
    $(document).on('click', '.delete-addition-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var id = $(this).data('id');
        $('#delete_addition_id').val(id);

        $('#delete_addition').modal('show');
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-addition-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const id = this.dataset.id || '';
                const name = this.dataset.name || '';
                const category = this.dataset.category || '';
                const unitCalc = (this.dataset.unit_calculation == '1') ? true : false;
                const unitAmount = this.dataset.unit_amount || '';
                const assignee = this.dataset.assignee || 'no';
                const employeeId = this.dataset.employee_id || '';

                document.getElementById('edit_addition_id').value = id;
                document.getElementById('edit_addition_name').value = name;
                document.getElementById('edit_addition_category').value = category;
                document.getElementById('edit_addition_unit_amount').value = unitAmount;

                const chk = document.getElementById('edit_unit_calculation');
                chk.checked = unitCalc;
                document.querySelectorAll('#edit_addition form input[name="assignee"]').forEach(
                    r => {
                        r.checked = (r.value === assignee);
                    });

                const empSelect = document.getElementById('edit_addition_employee');
                empSelect.value = employeeId || '';
                empSelect.disabled = (assignee !== 'single');
            });
        });

        document.querySelectorAll('.edit_assignee').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const empSelect = document.getElementById('edit_addition_employee');
                empSelect.disabled = (this.value !== 'single');
                if (this.value !== 'single') empSelect.value = '';
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        $(document).on('click', '.edit-overtime-btn', function(e) {
            e.preventDefault();
            var overtime = $(this).data('overtime');

            if (!overtime) {
                console.error('No overtime data found on button.');
                return;
            }


            $('#overtime_id').val(overtime.id);
            $('#overtime_name').val(overtime.name);
            $('#overtime_rate_type').val(overtime.rate_type);
            $('#overtime_rate').val(overtime.rate);


            $('#edit_overtime').modal('show');
        });
    });
</script>
