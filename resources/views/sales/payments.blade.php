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
                        <h3 class="page-title">Payments</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Payments</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item Name</th>
                                    <th>Purchase From</th>
                                    <th>Purchase Date</th>
                                    <th>Purchased By</th>
                                    <th>Amount</th>
                                    <th>Paid By</th>
                                    <th>Status</th>
                                    <th>Attachment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td>#{{ str_pad($expense->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $expense->item_name }}</td>
                                        <td>{{ $expense->purchase_from }}</td>
                                        <td>{{ \Carbon\Carbon::parse($expense->purchase_date)->format('d M, Y') }}</td>
                                        <td>{{ $expense->purchased_by }}</td>
                                        <td>${{ number_format((float) $expense->amount, 2) }}</td>
                                        <td>{{ $expense->paid_by }}</td>
                                        <td>
                                            @php
                                                $status = strtolower($expense->status);
                                                $badgeClass = match ($status) {
                                                    'approved' => 'badge badge-success',
                                                    'pending' => 'badge badge-warning',
                                                    default => 'badge badge-secondary',
                                                };
                                            @endphp
                                            <span class="{{ $badgeClass }}">{{ ucfirst($expense->status) }}</span>
                                        </td>
                                        <td>
                                            @if ($expense->attachments)
                                                @php
                                                    $fileUrl = asset('storage/expenses/' . $expense->attachments);
                                                    $ext = pathinfo($expense->attachments, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($ext), [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'gif',
                                                    ]);
                                                @endphp

                                                @if ($isImage)
                                                    <a href="{{ $fileUrl }}" target="_blank">
                                                        <img src="{{ $fileUrl }}" alt="attachment"
                                                            style="height:40px; width:auto; border-radius:4px;">
                                                    </a>
                                                @else
                                                    <a href="{{ $fileUrl }}"
                                                        target="_blank">{{ $expense->attachments }}</a>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No payments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

    @section('script')
    @endsection
@endsection
