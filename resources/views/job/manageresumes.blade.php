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

            <!-- Top Apply Button -->
            <div class="row mb-3">
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#apply_job">
                        Apply for a Job
                    </button>
                </div>
            </div>

            <!-- Resumes Table -->
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
                                        <td class="job_title">{{ $job->job_title }}</td>
                                        <td class="department">{{ $job->department }}</td>
                                        <td class="start_date">{{ date('d F, Y', strtotime($job->start_date)) }}</td>
                                        <td class="expired_date">{{ date('d F, Y', strtotime($job->expired_date)) }}</td>
                                        <td class="text-center job_type">
                                            <span class="badge badge-info">{{ $job->job_type }}</span>
                                        </td>
                                        <td class="text-center status">
                                            <span class="badge badge-success">{{ $job->status }}</span>
                                        </td>
                                        {{-- <td>
                                            <a href="{{ url('download-resume/' . $job->job_id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                        </td> --}}

                                        <td>
                                            @if (!empty($job->cv_upload))
                                                <a href="{{ route('download.resume', $job->application_id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @else
                                                <span class="text-muted">No resume</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit_job" data-toggle="modal"
                                                        data-target="#edit_job">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item delete_job" data-toggle="modal"
                                                        data-target="#delete_job">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                    <a class="dropdown-item apply_job_modal" data-toggle="modal"
                                                        data-target="#apply_job" data-job="{{ $job->job_title }}">
                                                        <i class="fa fa-paper-plane m-r-5"></i> Apply
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
                            <input type="hidden" id="e_id" name="id" value="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Title</label>
                                        <input class="form-control" type="text" id="e_job_title" name="job_title">
                                    </div>
                                </div>
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
                            <!-- Add other fields as needed (salary, age, experience, dates, etc.) -->
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

        <!-- Apply Job Modal -->
        <div class="modal custom-modal fade" id="apply_job" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Your Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="apply_jobs" action="{{ route('form/apply/job/save') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="tel"
                                    name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email Address</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Job Title</label>
                                <!-- visible but not submitted -->
                                <input class="form-control" type="text" id="display_job_title" value="">
                                <!-- hidden input that will be submitted -->
                                <input type="hidden" name="job_title" id="apply_job_title"
                                    value="{{ old('job_title') }}">
                                @error('job_title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" name="message">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Upload your CV</label>
                                <div class="custom-file">
                                    <input type="file"class="custom-file-input @error('cv_upload') is-invalid @enderror"
                                        id="cv_upload" name="cv_upload" multiple>
                                    <label class="custom-file-label" for="cv_upload">Choose file</label>
                                </div>
                                @error('cv_upload')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
        <!-- /Apply Job Modal -->

    </div>
    <!-- /Page Wrapper -->

    {{-- Scripts --}}
    <script>
        // Populate Edit Job modal
        $(document).on('click', '.edit_job', function() {
            var _this = $(this).closest('tr');
            $('#e_id').val(_this.find('.job_title').text()); // adjust fields as needed
            $('#e_job_title').val(_this.find('.job_title').text());
            $('#e_department').val(_this.find('.department').text());
            $('#e_job_type').val(_this.find('.job_type span').text());
            $('#e_status').val(_this.find('.status span').text());
            $('#e_start_date').val(_this.find('.start_date').text());
            $('#e_expired_date').val(_this.find('.expired_date').text());
        });

        // Populate Apply Job modal with job title
        $(document).on('click', '.apply_job_modal', function() {
            var jobTitle = $(this).data('job');
            $('#apply_job_title').val(jobTitle);
        });

        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
        });

        // jQuery Validation for Apply Job form
        // $('#apply_jobs').validate({
        //     rules: {
        //         name: 'required',
        //         phone: 'required',
        //         email: 'required',
        //         message: 'required',
        //         cv_upload: 'required',
        //     },
        //     messages: {
        //         name: 'Please input your name',
        //         phone: 'Please input your phone number',
        //         email: 'Please input your email',
        //         message: 'Please input your message',
        //         cv_upload: 'Please upload your CV',
        //     },
        //     submitHandler: function(form) {
        //         form.submit();
        //     }
        // });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Fill job title when modal opens
            $('#apply_job').on('show.bs.modal', function(e) {
                let jobTitle = $(e.relatedTarget).data('job-title') || '';
                $('#display_job_title, #apply_job_title').val(jobTitle);
            });

            // Sync visible and hidden job title fields
            $('#display_job_title').on('input', function() {
                $('#apply_job_title').val($(this).val());
            });

            // Ensure hidden field has value on submit
            $('#apply_jobs').on('submit', function() {
                $('#apply_job_title').val($('#display_job_title').val());
            });

            // Show selected filename
            $(document).on('change', '.custom-file-input', function(e) {
                $(this).next('.custom-file-label').html(e.target.files[0]?.name || 'Choose file');
            });

        });
    </script>
@endsection
