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
                        <h3 class="page-title">Designations</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Designations</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_designation"><i
                                class="fa fa-plus"></i> Add Designation</a>
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
                                    <th>Designation </th>
                                    <th>Department </th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($designations as $index => $d)
                                    <tr data-id="{{ $d->id }}">
                                        <td>{{ $index + 1 }}</td>

                                        <!-- Designation Name -->
                                        <td class="designation-name">{{ $d->designation_name }}</td>

                                        <!-- Department Name -->
                                        <td class="department-name">
                                            {{ optional($d->department)->department ?? 'N/A' }}
                                        </td>

                                        <!-- Action Buttons -->
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit-designation" href="#"
                                                        data-toggle="modal" data-target="#edit_designation"
                                                        data-id="{{ $d->id }}" data-name="{{ $d->designation_name }}"
                                                        data-department-id="{{ optional($d->department)->id ?? $d->department_id }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item delete-designation" href="#"
                                                        data-id="{{ $d->id }}"
                                                        data-name="{{ $d->designation_name }}">
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

        <!-- Add Designation Modal -->
        <div id="add_designation" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ route('form.designations.save') }}" method="POST">
                        @csrf


                        <div class="modal-header">
                            <h5 class="modal-title">Add Designation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Designation Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="designation_name" type="text" required>
                                @error('designation_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Department <span class="text-danger">*</span></label>
                                <select class="form-control select" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->department }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /Add Designation Modal -->

        <!-- Edit Designation Modal -->
        <div id="edit_designation" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Designation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('form/designations/update') }}" method="POST" id="editDesignationForm">
                            @csrf
                            <input type="hidden" name="id" id="edit_designation_id" />

                            <div class="form-group">
                                <label>Designation Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="designation_name" id="edit_designation_name"
                                    type="text" required>
                            </div>

                            <div class="form-group">
                                <label>Department <span class="text-danger">*</span></label>
                                <select class="select form-control" name="department_id" id="edit_department_id"
                                    required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->department }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Edit Designation Modal -->


        <!-- Delete Designation Modal -->
        <div class="modal custom-modal fade" id="delete_designation" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Designation</h3>
                            <p>Are you sure you want to delete <strong class="designation_name text-danger"></strong>?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <form action="{{ route('form/designations/delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" class="e_id">
                                        <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                                    </form>
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
        <!-- /Delete Designation Modal -->
    </div>
    <!-- /Page Wrapper -->

@section('script')
    <script>
        $(document).on('click', '.edit-designation', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var departmentId = $(this).data('department-id');

            $('#edit_designation_id').val(id);
            $('#edit_designation_name').val(name);
            $('#edit_department_id').val(departmentId);
        });
    </script>

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-designation')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-designation');
                const id = btn.dataset.id;
                const name = btn.dataset.name;

                document.querySelectorAll('.e_id').forEach(el => el.value = id);
                document.querySelectorAll('.designation_name').forEach(el => el.textContent = name);


                const modalEl = document.getElementById('delete_designation');
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();
            }
        });
    </script>
@endsection
@endsection
