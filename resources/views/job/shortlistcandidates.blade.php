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
                        <h3 class="page-title">Shortlist Candidates</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item active">Shortlist Candidates</li>
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
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobs as $index => $job)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ route('profile_user', $job->id) }}" class="avatar">
                                                    <img alt="" src="{{ asset($job->avatar) }}">
                                                </a>
                                                <a href="{{ route('profile_user', $job->id) }}">{{ $job->name }}
                                                    <span>{{ $job->job_title }}</span>
                                                </a>
                                            </h2>
                                        </td>
                                         <td>{{ $job->job_title }}</td>
                                        {{-- <td><a href="{{ route('job.details', $job->id) }}">{{ $job->job_title }}</a></td> --}}
                                        <td>{{ $job->department }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#">
                                                    <i
                                                        class="fa fa-dot-circle-o 
                            @if ($job->status == 'Offered') text-danger 
                            @elseif($job->status == 'Hired') text-success 
                            @else text-warning @endif"></i>
                                                    {{ $job->status }}
                                                </a>
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
