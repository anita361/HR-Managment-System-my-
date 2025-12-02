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
                        <h3 class="page-title">Expense Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Expense Report</li>
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
                            <option>Select buyer</option>
                            <option>Loren Gatlin</option>
                            <option>Tarah Shropshire</option>
                        </select>
                        <label class="focus-label">Purchased By</label>
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
                                    <th>Item</th>
                                    <th>Purchase From</th>
                                    <th>Purchase Date</th>
                                    <th>Purchased By</th>
                                    <th>Amount</th>
                                    <th>Paid By</th>
                                    <th class="text-center">Status</th>
                                    {{-- <th class="text-right">Actions</th> --}}
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($expenses as $expense)
                                    <tr>
                                        {{-- <td><strong>{{ $expense->item }}</strong></td> --}}
                                        <td><strong>{{ $expense->item_name }}</strong></td>

                                        <td>{{ $expense->purchase_from }}</td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($expense->purchase_date)->format('d M Y') }}
                                        </td>

                                        <td>
                                            <a href="#" class="avatar avatar-xs">
                                                <img src="{{ $expense->purchaser_avatar
                                                    ? asset('uploads/avatars/' . $expense->purchaser_avatar)
                                                    : asset('assets/img/profiles/default-avatar.jpg') }}"
                                                    alt="">
                                            </a>
                                            <h2><a href="#">{{ $expense->purchased_by }}</a></h2>
                                        </td>

                                        <td>$ {{ number_format((float) ($expense->amount ?? 0), 2) }}</td>

                                        <td>{{ $expense->paid_by }}</td>

                                        <td class="text-center">
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                                    data-toggle="dropdown">
                                                    <i
                                                        class="fa fa-dot-circle-o 
                        {{ $expense->status == 'Approved' ? 'text-success' : 'text-danger' }}"></i>
                                                    {{ $expense->status }}
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fa fa-dot-circle-o text-danger"></i> Pending</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="fa fa-dot-circle-o text-success"></i> Approved</a>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#edit_expense_{{ $expense->id }}">
                                                        <i class="fa fa-pencil m-r-5"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#delete_expense_{{ $expense->id }}">
                                                        <i class="fa fa-trash-o m-r-5"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td> --}}
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
