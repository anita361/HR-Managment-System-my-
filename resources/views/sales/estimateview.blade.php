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
                        <h3 class="page-title">Estimate</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('index.html') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Estimate</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Export buttons">
                            <a class="btn btn-white" style="color: green"
                                href="{{ url('extra/report/excel') . '?user_id=' . ($users->user_id ?? '') }}">
                                <i class="fa fa-file-excel-o"></i> Excel
                            </a>

                            <a class="btn btn-white" style="color: red"
                                href="{{ url('extra/report/pdf') . '?user_id=' . ($users->user_id ?? '') }}"
                                target="_blank">
                                <i class="fa fa-file-pdf-o"></i> PDF
                            </a>

                            <a class="btn btn-white" style="color: black" href="#"
                                onclick="window.print(); return false;">
                                <i class="fa fa-print fa-lg"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            @if (isset($estimatesJoin) && $estimatesJoin->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 m-b-20">
                                        @if (!empty($users->avatar))
                                            <img src="{{ asset('assets/images/' . $users->avatar) }}" class="inv-logo"
                                                alt="{{ $users->name }}">
                                        @endif

                                        <ul class="list-unstyled">
                                            <li>{{ $estimatesJoin[0]->client ?? '' }}</li>
                                            <li>{{ $estimatesJoin[0]->client_address ?? '' }}</li>
                                            <li>{{ $estimatesJoin[0]->billing_address ?? '' }}</li>
                                            <li>{{ $estimatesJoin[0]->tax ?? '' }}</li>
                                        </ul>
                                    </div>

                                    <div class="col-sm-6 m-b-20">
                                        <div class="invoice-details">
                                            <h3 class="text-uppercase">
                                                Estimate #{{ $estimatesJoin[0]->estimate_number ?? '' }}
                                            </h3>
                                            <ul class="list-unstyled">
                                                <li>
                                                    Create Date:
                                                    <span>
                                                        {{ !empty($estimatesJoin[0]->estimate_date) ? date('d F, Y', strtotime($estimatesJoin[0]->estimate_date)) : '' }}
                                                    </span>
                                                </li>
                                                <li>
                                                    Expiry date:
                                                    <span>
                                                        {{ !empty($estimatesJoin[0]->expiry_date) ? date('d F, Y', strtotime($estimatesJoin[0]->expiry_date)) : '' }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-lg-12 m-b-20">
                                        <h5>Estimate to: {{ $estimatesJoin[0]->client ?? '' }}</h5>
                                        <ul class="list-unstyled">
                                            <li>
                                                <a
                                                    href="mailto:{{ $estimatesJoin[0]->email ?? '' }}">{{ $estimatesJoin[0]->email ?? '' }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ITEM</th>
                                                <th class="d-none d-sm-table-cell">DESCRIPTION</th>
                                                <th>UNIT COST</th>
                                                <th>QUANTITY</th>
                                                <th class="text-right">AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($estimatesJoin as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->item }}</td>
                                                    <td class="d-none d-sm-table-cell">{{ $item->description }}</td>
                                                    <td>${{ number_format($item->unit_cost ?? 0, 2) }}</td>
                                                    <td>{{ $item->qty ?? 0 }}</td>
                                                    <td class="text-right">${{ number_format($item->amount ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row invoice-payment">
                                    <div class="col-sm-7"></div>

                                    <div class="col-sm-5">
                                        <div class="m-b-20">
                                            <div class="table-responsive no-border">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th>Subtotal:</th>
                                                            <td class="text-right">
                                                                ${{ number_format($estimatesJoin[0]->subtotal ?? ($estimatesJoin[0]->total ?? 0), 2) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Tax: <span class="text-regular">(25%)</span></th>
                                                            <td class="text-right">
                                                                ${{ number_format($estimatesJoin[0]->tax_1 ?? 0, 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total:</th>
                                                            <td class="text-right text-primary">
                                                                <h5>${{ number_format($estimatesJoin[0]->total ?? 0, 2) }}
                                                                </h5>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="invoice-info">
                                    <h5>Other information</h5>
                                    <p class="text-muted">{{ $estimatesJoin[0]->other_information ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    No estimates found.
                </div>
            @endif
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection

@section('script')
    {{-- Add your page-specific JS here --}}
@endsection
