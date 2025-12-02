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
                        <h3 class="page-title">Assets</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Assets</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_asset"><i
                                class="fa fa-plus"></i> Add Asset</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">Employee Name</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating">
                            <option value=""> -- Select -- </option>
                            <option value="0"> Pending </option>
                            <option value="1"> Approved </option>
                            <option value="2"> Returned </option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-focus">
                                <div class="cal-icon">
                                    <input class="form-control floating datetimepicker" type="text">
                                </div>
                                <label class="focus-label">From</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-focus">
                                <div class="cal-icon">
                                    <input class="form-control floating datetimepicker" type="text">
                                </div>
                                <label class="focus-label">To</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
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
                                    <th>Asset User</th>
                                    <th>Asset Name</th>
                                    <th>Asset Id</th>
                                    <th>Purchase Date</th>
                                    <th>Warrenty</th>
                                    <th>Warrenty End</th>
                                    <th>Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assets as $asset)
                                    <tr>
                                        <td>{{ $asset->user->name ?? 'N/A' }}</td>
                                        <td><strong>{{ $asset->name }}</strong></td>
                                        <td>{{ $asset->asset_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') }}</td>
                                        <td>{{ $asset->warranty_months }}</td>
                                        <td>{{ \Carbon\Carbon::parse($asset->warranty_end)->format('d M Y') }}</td>
                                        <td>${{ $asset->value }}</td>
                                        <td class="text-center">
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                                    data-toggle="dropdown">
                                                    @if ($asset->status == 'Pending')
                                                        <i class="fa fa-dot-circle-o text-danger"></i> Pending
                                                    @elseif($asset->status == 'Approved')
                                                        <i class="fa fa-dot-circle-o text-success"></i> Approved
                                                    @else
                                                        <i class="fa fa-dot-circle-o text-info"></i> Returned
                                                    @endif
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fa fa-dot-circle-o text-danger"></i> Pending</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fa fa-dot-circle-o text-success"></i> Approved</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fa fa-dot-circle-o text-info"></i> Returned</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                    data-toggle="dropdown"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="dropdown-item" data-toggle="modal"
                                                        data-target="#edit_asset" data-id="{{ $asset->id }}"
                                                        data-name="{{ $asset->name }}"
                                                        data-purchase="{{ $asset->purchase_date }}"
                                                        data-purchasefrom="{{ $asset->purchase_from }}"
                                                        data-manufacturer="{{ $asset->manufacturer }}"
                                                        data-model="{{ $asset->model }}"
                                                        data-serial="{{ $asset->serial_number }}"
                                                        data-supplier="{{ $asset->supplier }}"
                                                        data-condition="{{ $asset->condition }}"
                                                        data-warranty="{{ $asset->warranty_months }}"
                                                        data-value="{{ $asset->value }}"
                                                        data-assetuser="{{ $asset->asset_user_id }}"
                                                        data-description="{{ $asset->description }}"
                                                        data-status="{{ $asset->status }}">
                                                        Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#delete_asset" data-id="{{ $asset->id }}"><i
                                                            class="fa fa-trash-o m-r-5"></i> Delete</a>
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

        <!-- Add Asset Modal -->
        <div id="add_asset" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Asset</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('assets.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asset Name</label>
                                        <input name="name" value="{{ old('name') }}" class="form-control"
                                            type="text" required>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asset Id</label>
                                        <input name="asset_id" value="{{ old('asset_id') }}" class="form-control"
                                            type="text">
                                    </div>
                                </div> --}}
                            </div>

                            <!-- Second row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase Date</label>
                                        <input name="purchase_date" value="{{ old('purchase_date') }}"
                                            class="form-control datetimepicker" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase End</label>
                                        <input name="purchase_from" value="{{ old('purchase_from') }}"
                                            class="form-control datetimepicker" type="text">
                                    </div>
                                </div>
                            </div>

                            <!-- more fields, follow same pattern -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Manufacturer</label>
                                        <input name="manufacturer" value="{{ old('manufacturer') }}"
                                            class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <input name="model" value="{{ old('model') }}" class="form-control"
                                            type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input name="serial_number" value="{{ old('serial_number') }}"
                                            class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <input name="supplier" value="{{ old('supplier') }}" class="form-control"
                                            type="text">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Condition</label>
                                        <input name="condition" value="{{ old('condition') }}" class="form-control"
                                            type="text">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Warranty</label>
                                        <input name="warranty_months" value="{{ old('warranty_months') }}"
                                            class="form-control" type="text" placeholder="In Months">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Value</label>
                                        <input name="value" value="{{ old('value') }}" placeholder="$1800"
                                            class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asset User</label>
                                        <select name="asset_user_id" class="select form-control">
                                            <option value="">-- Select user --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('asset_user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="select form-control">
                                            <option value="Pending">Pending</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Deployed">Deployed</option>
                                            <option value="Damaged">Damaged</option>
                                        </select>
                                    </div>
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
        <!-- /Add Asset Modal -->

        <!-- Edit Asset Modal -->
        <div id="edit_asset" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Asset</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('assets.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $asset->id }}">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asset Name</label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ old('name', $asset->name) }}" required>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asset Id</label>
                                        <input class="form-control" type="text" name="asset_id"
                                            value="{{ old('asset_id', $asset->asset_id) }}" readonly>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase Date</label>
                                        <input class="form-control datetimepicker" type="text" name="purchase_date"
                                            value="{{ old('purchase_date', $asset->purchase_date) }}">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase End</label>
                                        <input class="form-control" type="text" name="purchase_from"
                                            value="{{ old('purchase_from', $asset->purchase_from) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Manufacturer</label>
                                        <input class="form-control" type="text" name="manufacturer"
                                            value="{{ old('manufacturer', $asset->manufacturer) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <input class="form-control" type="text" name="model"
                                            value="{{ old('model', $asset->model) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input class="form-control" type="text" name="serial_number"
                                            value="{{ old('serial_number', $asset->serial_number) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <input class="form-control" type="text" name="supplier"
                                            value="{{ old('supplier', $asset->supplier) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Condition</label>
                                        <input class="form-control" type="text" name="condition"
                                            value="{{ old('condition', $asset->condition) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Warranty</label>
                                        <input class="form-control" type="number" name="warranty_months"
                                            placeholder="In Months"
                                            value="{{ old('warranty_months', $asset->warranty_months) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Value</label>
                                        <input class="form-control" type="text" name="value" placeholder="$1800"
                                            value="{{ old('value', $asset->value) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Asset User</label>
                                        <select class="select" name="asset_user_id">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $asset->asset_user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description">{{ old('description', $asset->description) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="select" name="status">
                                            <option value="Pending" {{ $asset->status == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Approved" {{ $asset->status == 'Approved' ? 'selected' : '' }}>
                                                Approved</option>
                                            <option value="Deployed" {{ $asset->status == 'Deployed' ? 'selected' : '' }}>
                                                Deployed</option>
                                            <option value="Damaged" {{ $asset->status == 'Damaged' ? 'selected' : '' }}>
                                                Damaged</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit Asset Modal -->

        <!-- Delete Asset Modal -->
        <div class="modal custom-modal fade" id="delete_asset" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Asset</h3>
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
        <!-- /Delete Asset Modal -->
    </div>
    <!-- /Page Wrapper -->

@section('script')
@endsection
<script>
    $(document).ready(function() {
        $('#edit_asset').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            var modalData = {
                id: button.data('id'),
                name: button.data('name'),
                purchase_date: button.data('purchase'),
                purchase_from: button.data('purchasefrom'),
                manufacturer: button.data('manufacturer'),
                model: button.data('model'),
                serial_number: button.data('serial'),
                supplier: button.data('supplier'),
                condition: button.data('condition'),
                warranty_months: button.data('warranty'),
                value: button.data('value'),
                asset_user_id: button.data('assetuser'),
                description: button.data('description'),
                status: button.data('status')
            };

            var modal = $(this);
            modal.find('input[name="id"]').val(modalData.id);
            modal.find('input[name="name"]').val(modalData.name);
            modal.find('input[name="purchase_date"]').val(modalData.purchase_date);
            modal.find('input[name="purchase_from"]').val(modalData.purchase_from);
            modal.find('input[name="manufacturer"]').val(modalData.manufacturer);
            modal.find('input[name="model"]').val(modalData.model);
            modal.find('input[name="serial_number"]').val(modalData.serial_number);
            modal.find('input[name="supplier"]').val(modalData.supplier);
            modal.find('input[name="condition"]').val(modalData.condition);
            modal.find('input[name="warranty_months"]').val(modalData.warranty_months);
            modal.find('input[name="value"]').val(modalData.value);
            modal.find('select[name="asset_user_id"]').val(modalData.asset_user_id).trigger('change');
            modal.find('textarea[name="description"]').val(modalData.description);
            modal.find('select[name="status"]').val(modalData.status).trigger('change');
        });
    });
</script>
