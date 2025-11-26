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
                        <h3 class="page-title">Invoice Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Invoice Report</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating">
                            <option>Select Client</option>
                            <option>Global Technologies</option>
                            <option>Delta Infotech</option>
                        </select>
                        <label class="focus-label">Client</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
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
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Client</th>
                                    <th>Created Date</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                           
                            <tbody>
                                @foreach ($invoices as $i => $inv)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>

                                        {{-- Invoice Number --}}
                                        <td>
                                            
                                            <a href="{{ url('estimate/view/'.$inv->estimate_number) }}">
                                                #INV-{{ sprintf('%04d', $inv->invoice_number ?? $inv->id) }}
                                            </a>
                                        </td>

                                        {{-- Client Name --}}
                                        <td>{{ $inv->client_name }}</td>

                                        {{-- Created Date --}}
                                        <td>{{ \Carbon\Carbon::parse($inv->created_at)->format('d M Y') }}</td>

                                        {{-- Due Date --}}
                                        <td>{{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</td>

                                        {{-- Amount --}}
                                        <td>${{ number_format($inv->amount, 2) }}</td>

                                        {{-- Status (Only if your table has status column) --}}
                                        <td>
                                            @php
                                                $status = $inv->status ?? 'Draft';
                                                $badge = match ($status) {
                                                    'Paid' => 'bg-inverse-success',
                                                    'Sent' => 'bg-inverse-info',
                                                    'Overdue' => 'bg-inverse-danger',
                                                    'Draft' => 'bg-inverse-warning',
                                                    default => 'bg-inverse-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badge }}">{{ $status }}</span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">

                                                    <a class="dropdown-item" href="{{ url('invoice/edit/' . $inv->id) }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>

                                                    <a class="dropdown-item" href="{{ url('invoice/view/' . $inv->id) }}">
                                                        <i class="fa fa-eye m-r-5"></i> View
                                                    </a>

                                                    <a class="dropdown-item"
                                                        href="{{ url('invoice/download/' . $inv->id) }}">
                                                        <i class="fa fa-file-pdf-o m-r-5"></i> Download
                                                    </a>

                                                    <a class="dropdown-item" href="{{ url('invoice/delete/' . $inv->id) }}"
                                                        onclick="return confirm('Are you sure?')">
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
    </div>
    <!-- /Page Wrapper -->
@endsection
