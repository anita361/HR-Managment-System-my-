@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h3 class="page-title">Manage Resumes</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item active">Manage Resumes</li>
                        </ul>
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
                                    <th>Name</th>
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th>Start Date</th>
                                    <th>Expire Date</th>
                                    <th class="text-center">Job Type</th>
                                    <th class="text-center">Status</th>
                                    <th>Resume</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                           
                            <tbody>
                                @foreach ($manageResumes as $key => $job)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ route('profile_user', $job->job_id) }}" class="avatar">
                                                    <img alt="" src="{{ asset($job->avatar) }}">
                                                </a>
                                                <a href="{{ route('profile_user', $job->job_id) }}">{{ $job->name }}
                                                    <span>{{ $job->job_title }}</span>
                                                </a>
                                            </h2>
                                        </td>
                                        <td>{{ $job->job_title }}</td>
                                        <td>{{ $job->department }}</td>
                                        <td>{{ date('d F, Y', strtotime($job->start_date)) }}</td>
                                        <td>{{ date('d F, Y', strtotime($job->expired_date)) }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $job->job_type }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success">{{ $job->status }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ url('download-resume/' . $job->job_id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit_job" data-toggle="modal"
                                                        data-target="#edit_job_{{ $job->job_id }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item delete_job" data-toggle="modal"
                                                        data-target="#delete_job_{{ $job->job_id }}">
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

        <!-- Edit Job Modal -->
        <div id="edit_job" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Job</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/apply/job/update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Title</label>
                                        <input class="form-control" type="text" id="e_job_title" name="job_title">
                                    </div>
                                </div>
                                <input class="form-control" type="hidden" id="e_id" name="id" value="">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="select" id="e_department" name="department">
                                            @foreach ($department as $value)
                                                <option value="{{ $value->department }}">{{ $value->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Location</label>
                                        <input class="form-control" type="text" id="e_job_location" name="job_location"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No of Vacancies</label>
                                        <input class="form-control" type="text" id="e_no_of_vacancies"
                                            name="no_of_vacancies" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Experience</label>
                                        <input class="form-control" type="text" id="e_experience" name="experience"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input class="form-control" type="text" id="e_age" name="age"
                                            value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Salary From</label>
                                        <input type="text" class="form-control" id="e_salary_from" name="salary_from"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Salary To</label>
                                        <input type="text" class="form-control" id="e_salary_to" name="salary_to"
                                            value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Type</label>
                                        <select class="select" id="e_job_type" name="job_type">
                                            @foreach ($type_job as $job)
                                                <option value="{{ $job->name_type_job }}">{{ $job->name_type_job }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="select" id="e_status" name="status">
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="text" class="form-control datetimepicker" id="e_start_date"
                                            name="start_date" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expired Date</label>
                                        <input type="text" class="form-control datetimepicker" id="e_expired_date"
                                            name="expired_date" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" rows="5" id="e_description" name="description"></textarea>
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
        <!-- /Edit Job Modal -->

        <!-- Delete Job Modal -->
        <div class="modal custom-modal fade" id="delete_job" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Job</h3>
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
        <!-- /Delete Job Modal -->

    </div>
    <!-- /Page Wrapper -->

    {{-- update --}}
    <script>
        $(document).on('click', '.edit_job', function() {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_job_title').val(_this.find('.job_title').text());
            $('#e_job_location').val(_this.find('.job_location').text());
            $('#e_no_of_vacancies').val(_this.find('.no_of_vacancies').text());
            $('#e_experience').val(_this.find('.experience').text());
            $('#e_salary_from').val(_this.find('.salary_from').text());
            $('#e_salary_to').val(_this.find('.salary_to').text());
            $('#e_start_date').val(_this.find('.start_date').text());
            $('#e_expired_date').val(_this.find('.expired_date').text());
            $('#e_age').val(_this.find('.age').text());
            $('#e_description').val(_this.find('.description').text());

            // department
            var department = (_this.find(".department").text());
            var _option = '<option selected value="' + department + '">' + _this.find('.department').text() +
                '</option>'
            $(_option).appendTo("#e_department");

            // job type
            var job_type = (_this.find(".job_type").text());
            var _option = '<option selected value="' + job_type + '">' + _this.find('.job_type').text() +
                '</option>'
            $(_option).appendTo("#e_job_type");

            // status
            var status = (_this.find(".status").text());
            var _option = '<option selected value="' + status + '">' + _this.find('.status').text() + '</option>'
            $(_option).appendTo("#e_status");
        });
    </script>
@endsection
