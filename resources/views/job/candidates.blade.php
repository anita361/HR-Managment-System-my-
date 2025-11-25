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
                        <h3 class="page-title">Candidates List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item active">Candidates List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" data-toggle="modal" data-target="#add_employee" class="btn add-btn"> Add
                            Candidates</a>
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
                                    <th>Email</th>
                                    <th>Number</th>
                                    <th>Created Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($candidates as $candidate)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $candidate->first_name }} {{ $candidate->last_name }}</td>
                                        <td>{{ $candidate->email }}</td>
                                        <td>{{ $candidate->phone }}</td>

                                        <td>{{ \Carbon\Carbon::parse($candidate->created_at)->format('d M Y') }}</td>

                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="material-icons">more_vert</i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="dropdown-item edit_candidate"
                                                        data-toggle="modal" data-target="#edit_candidate"
                                                        data-id="{{ $candidate->id }}"
                                                        data-first_name="{{ $candidate->first_name }}"
                                                        data-last_name="{{ $candidate->last_name }}"
                                                        data-email="{{ $candidate->email }}"
                                                        data-employee_id="{{ $candidate->employee_id }}"
                                                        data-created_date="{{ $candidate->created_date }}"
                                                        data-phone="{{ $candidate->phone }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item deleteCandidateBtn" href="javascript:void(0);"
                                                        data-id="{{ $candidate->id }}">
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

        <!-- Add Employee Modal -->
        <div id="add_employee" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Candidates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('candidates.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="first_name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Last Name</label>
                                        <input class="form-control" type="text" name="last_name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                        <select class="form-control" name="employee_id">
                                            <option value="">Select Employee</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->employee_id }} -
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Created Date <span
                                                class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="created_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Phone </label>
                                        <input class="form-control" type="text" name="phone">
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
        <!-- /Add Employee Modal -->

        <!-- Edit Candidate Modal -->
        <div id="edit_candidate" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Candidate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="editCandidateForm" method="POST" action="{{ route('candidates.update') }}">
                            @csrf
                            <input type="hidden" name="id" id="candidate_id">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input id="first_name" name="first_name" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input id="last_name" name="last_name" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input id="email" name="email" class="form-control" type="email">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Employee ID</label>
                                        <input id="employee_id" name="employee_id" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Created Date</label>
                                        <input id="created_date" name="created_date" class="form-control datetimepicker"
                                            type="text">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input id="phone" name="phone" class="form-control" type="text">
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
        <!-- /Edit Candidate Modal -->

        <!-- Delete Candidate Modal -->
        <div class="modal custom-modal fade" id="delete_candidate" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete</h3>
                            <p>Are you sure you want to delete this candidate?</p>
                        </div>
                        <form id="deleteCandidateForm" method="POST" action="{{ route('candidates.delete') }}">
                            @csrf
                            <input type="hidden" name="id" id="candidate_id" value="">
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-primary cancel-btn"
                                            data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Job Modal -->

        <!-- /Page Wrapper -->
    @endsection
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit_candidate', function() {
                $('#candidate_id').val($(this).data('id'));
                $('#first_name').val($(this).data('first_name'));
                $('#last_name').val($(this).data('last_name'));
                $('#email').val($(this).data('email'));
                $('#employee_id').val($(this).data('employee_id'));
                $('#created_date').val($(this).data('created_date'));
                $('#phone').val($(this).data('phone'));
            });
        });
        $(document).on('click', '.deleteCandidateBtn', function() {
            let candidateId = $(this).data('id');
            $('#candidate_id').val(candidateId);
            $('#delete_candidate').modal('show');
        });
        $(document).on('submit', '#deleteCandidateForm', function(e) {
            e.preventDefault();

            let candidateId = $('#candidate_id').val();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: {
                    id: candidateId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#delete_candidate').modal('hide');
                    $('#candidate-row-' + candidateId).remove();
                    alert('Candidate deleted successfully!');
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON); // This will log Laravel validation errors
                    alert('Something went wrong!');
                }
            });
        });
    </script>
