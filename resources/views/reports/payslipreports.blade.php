@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Payslip Reports</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Payslip Reports</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Content Starts -->
            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">Employee Name</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <select class="form-control floating select">
                                <option>
                                    Jan
                                </option>
                                <option>
                                    Feb
                                </option>
                                <option>
                                    Mar
                                </option>
                            </select>
                        </div>
                        <label class="focus-label">Month</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <select class="form-control floating select">
                                <option>
                                    2018
                                </option>
                                <option>
                                    2019
                                </option>
                                <option>
                                    2020
                                </option>
                            </select>
                        </div>
                        <label class="focus-label">Year</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="#" class="btn btn-success btn-block"> Search </a>
                </div>
            </div>
            <!-- /Search Filter -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Employee ID</th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th>Email</th>
                                    <th>Join Date</th>
                                    <th>Role</th>
                                    <th>Salary</th>
                                    <th hidden></th>
                                    <th>Payslip</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $items)
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ url('employee/profile/' . $items->user_id) }}" class="avatar"><img
                                                        alt=""
                                                        src="{{ URL::to('/assets/images/' . $items->avatar) }}"></a>
                                                <a
                                                    href="{{ url('employee/profile/' . $items->user_id) }}">{{ $items->name }}<span>{{ $items->position }}</span></a>
                                            </h2>
                                        </td>
                                        <td>{{ $items->user_id }}</td>
                                        <td hidden class="id">{{ $items->id }}</td>
                                        <td hidden class="name">{{ $items->name }}</td>
                                        <td hidden class="basic">{{ $items->basic }}</td>
                                        <td hidden class="da">{{ $items->da }}</td>
                                        <td hidden class="hra">{{ $items->hra }}</td>
                                        <td hidden class="conveyance">{{ $items->conveyance }}</td>
                                        <td hidden class="allowance">{{ $items->allowance }}</td>
                                        <td hidden class="medical_allowance">{{ $items->medical_allowance }}</td>
                                        <td hidden class="tds">{{ $items->tds }}</td>
                                        <td hidden class="esi">{{ $items->esi }}</td>
                                        <td hidden class="pf">{{ $items->pf }}</td>
                                        <td hidden class="leave">{{ $items->leave }}</td>
                                        <td hidden class="prof_tax">{{ $items->prof_tax }}</td>
                                        <td hidden class="labour_welfare">{{ $items->labour_welfare }}</td>
                                        <td>{{ $items->email }}</td>
                                        <td>{{ $items->join_date }}</td>
                                        <td>{{ $items->role_name }}</td>
                                        <td>${{ $items->salary }}</td>
                                        <td hidden class="salary">{{ $items->salary }}</td>
                                        <td><a class="btn btn-sm btn-primary"
                                                href="{{ url('form/salary/view/' . $items->user_id) }}"
                                                target="_blank">Generate Slip</a></td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item userSalary" href="#" data-toggle="modal"
                                                        data-target="#edit_salary"><i class="fa fa-pencil m-r-5"></i>
                                                        Edit</a>
                                                    <a class="dropdown-item salaryDelete" href="#" data-toggle="modal"
                                                        data-target="#delete_salary"><i class="fa fa-trash-o m-r-5"></i>
                                                        Delete</a>
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
            <!-- /Content End -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
