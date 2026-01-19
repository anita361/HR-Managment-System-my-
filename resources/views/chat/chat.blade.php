@extends('layouts.chat')
@section('content')
    <div class="page-wrapper">

        <div class="chat-main-row">

            <div class="chat-main-wrapper">

                <div class="col-lg-9 message-view task-view">
                    <div class="chat-window">
                        <div class="fixed-header">
                            <div class="navbar">
                                <div class="user-details mr-auto">
                                    <div class="float-left user-img">
                                        <a class="avatar" href="{{ route('employee.profile', $selectedUser->user_id) }}">
                                            <img src="{{ URL::to('/assets/images/' . $selectedUser->avatar) }}"
                                                alt="" class="rounded-circle">

                                            @php
                                                use Carbon\Carbon;

                                                if (auth()->check() && auth()->id() === $selectedUser->id) {
                                                    $isOnline = true;
                                                } else {
                                                    $isOnline =
                                                        !empty($selectedUser->last_seen) &&
                                                        Carbon::parse($selectedUser->last_seen)->greaterThan(
                                                            now()->subMinutes(5),
                                                        );
                                                }
                                            @endphp


                                            <span class="status"
                                                style="background-color: {{ $isOnline ? 'green' : 'gray' }};">
                                            </span>
                                        </a>
                                    </div>

                                    <div class="user-info float-left">
                                        <a href="{{ route('employee.profile', $selectedUser->user_id) }}">
                                            <span>{{ $selectedUser->name }}</span>
                                            <i class="typing-text"></i>
                                        </a>


                                        @if ($isOnline)
                                            <span class="last-seen">Online</span>
                                        @elseif (!empty($selectedUser->last_seen))
                                            <span class="last-seen">
                                                Last seen {{ Carbon::parse($selectedUser->last_seen)->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="last-seen">Never seen</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="search-box mb-2">
                                    <div class="input-group input-group-sm">
                                        <input type="text" placeholder="Search messages" class="form-control"
                                            id="chatSearch" data-search-url="{{ route('msg.search') }}">

                                        <span class="input-group-append">
                                            <button type="button" class="btn" id="searchBtn">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <ul id="search-results"></ul>



                                <ul class="nav custom-menu">
                                    <li class="nav-item">
                                        <a class="nav-link task-chat profile-rightbar float-right" id="task_chat"
                                            href="#task_window"><i class="fa fa-user"></i></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="voice-call.html" class="nav-link"><i class="fa fa-phone"></i></a>
                                    </li>
                                    {{-- 
                                      <li class="nav-item">
                                        <a href="javascript:void(0)" id="startCall" class="nav-link">
                                            <i class="fa fa-phone"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" id="endCall" class="nav-link">
                                            <i class="fa fa-phone-slash"></i>
                                        </a>
                                    </li> --}}


                                    <audio id="remoteAudio" autoplay></audio>

                                    <li class="nav-item">
                                        <a href="video-call.html" class="nav-link"><i class="fa fa-video-camera"></i></a>
                                    </li>
                                    <li class="nav-item dropdown dropdown-action">
                                        <a aria-expanded="false" data-toggle="dropdown" class="nav-link dropdown-toggle"
                                            href=""><i class="fa fa-cog"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="javascript:void(0)" class="dropdown-item">Delete Conversations</a>
                                            <a href="javascript:void(0)" class="dropdown-item">Settings</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="chat-contents">
                            <div class="chat-content-wrap">
                                <div class="chat-wrap-inner">
                                    <div class="chat-box">
                                        <div class="chats">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="chat-footer">
                            <div class="message-bar">
                                <div class="message-inner">
                                    <a class="link attach-icon" href="#" data-toggle="modal"
                                        data-target="#drag_files"><img src="{{ asset('assets/img/attachment.png') }}"
                                            alt=""></a>
                                    <div class="message-area">
                                        <form id="chatForm">
                                            @csrf
                                            <input type="hidden" id="receiver_id" name="receiver_id"
                                                value="{{ $selectedUser->id }}">


                                            <div class="input-group">
                                                <textarea class="form-control" placeholder="Type message..." name="message" id="message_id"></textarea>
                                                <input type="hidden" id="editMessageId">

                                                <span class="input-group-append">
                                                    <button class="btn btn-custom" id="sendBtn" type="submit">
                                                        <i class="fa fa-send"></i>
                                                    </button>
                                                </span>
                                            </div>


                                            <small class="text-danger d-none" id="msgError"></small>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 message-view chat-profile-view chat-sidebar" id="task_window">
                    <div class="chat-window video-window">
                        <div class="fixed-header">
                            <ul class="nav nav-tabs nav-tabs-bottom">
                                <li class="nav-item"><a class="nav-link" href="#calls_tab" data-toggle="tab">Calls</a>
                                </li>
                                <li class="nav-item"><a class="nav-link active" href="#profile_tab"
                                        data-toggle="tab">Profile</a></li>
                            </ul>
                        </div>
                        <div class="tab-content chat-contents">
                            <div class="content-full tab-pane" id="calls_tab">
                                <div class="chat-wrap-inner">
                                    <div class="chat-box">
                                        <div class="chats">
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt=""
                                                            src="{{ URL::to('/assets/images/' . $selectedUser->avatar) }}">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">{{ $selectedUser->name }}</span>
                                                            <span class="chat-time">{{ $selectedUser->last_login }}</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">phone_missed</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details">
                                                                        <span class="call-description">Jeffrey Warden
                                                                            missed the call</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt=""
                                                            src="{{ asset('assets/img/profiles/avatar-02.jpg') }}">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">John Doe</span> <span
                                                                class="chat-time">8:35 am</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">call_end</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details"><span
                                                                            class="call-description">This call has
                                                                            ended</span></div>
                                                                    <div class="call-timing">Duration: <strong>5 min 57
                                                                            sec</strong></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-line">
                                                <span class="chat-date">January 29th, 2019</span>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt=""
                                                            src="{{ asset('assets/img/profiles/avatar-05.jpg') }}">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">Richard Miles</span> <span
                                                                class="chat-time">8:35 am</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">phone_missed</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details">
                                                                        <span class="call-description">You missed the
                                                                            call</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt=""
                                                            src="{{ URL::to('/assets/images/' . $selectedUser->avatar) }}">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">{{ $selectedUser->name }}</span>
                                                            <span class="chat-time">{{ $selectedUser->last_login }}</span>
                                                            <div class="call-details">
                                                                <i class="material-icons">ring_volume</i>
                                                                <div class="call-info">
                                                                    <div class="call-user-details">
                                                                        <a href="#"
                                                                            class="call-description call-description--linked"
                                                                            data-qa="call_attachment_link">Calling John
                                                                            Smith ...</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-full tab-pane show active" id="profile_tab">
                                <div class="display-table">
                                    <div class="table-row">
                                        <div class="table-body">
                                            <div class="table-content">
                                                <div class="chat-profile-img">
                                                    <div class="edit-profile-img">
                                                        <img src="{{ URL::to('/assets/images/' . $selectedUser->avatar) }}"
                                                            alt="">
                                                        <span class="change-img">Change Image</span>
                                                    </div>
                                                    <h3 class="user-name m-t-10 mb-0">{{ $selectedUser->name }}</h3>
                                                    <small class="text-muted"></small>
                                                    <a href="javascript:void(0);" class="btn btn-primary edit-btn"><i
                                                            class="fa fa-pencil"></i></a>
                                                </div>
                                                <div class="chat-profile-info">
                                                    <ul class="user-det-list">
                                                        <li>
                                                            <span>Username:</span>
                                                            <span
                                                                class="float-right text-muted">{{ $selectedUser->name }}</span>
                                                        </li>
                                                        <li>
                                                            <span>DOB:</span>
                                                            <span
                                                                class="float-right text-muted">{{ $selectedUser->birth_date }}</span>
                                                        </li>
                                                        <li>
                                                            <span>Email:</span>
                                                            <span
                                                                class="float-right text-muted">{{ $selectedUser->email }}</span>
                                                        </li>
                                                        <li>
                                                            <span>Phone:</span>
                                                            <span
                                                                class="float-right text-muted">{{ $selectedUser->phone_number }}</span>
                                                        </li>
                                                    </ul>
                                                </div>


                                                <div class="transfer-files">
                                                    <ul class="nav nav-tabs nav-tabs-solid nav-justified mb-0">
                                                        <li class="nav-item"><a class="nav-link active" href="#all_files"
                                                                data-toggle="tab">All Files</a></li>
                                                        <li class="nav-item"><a class="nav-link" href="#my_files"
                                                                data-toggle="tab">My Files</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div class="tab-pane show active" id="all_files">
                                                            <ul class="files-list">
                                                                <li>
                                                                    <div class="files-cont">
                                                                        <div class="file-type">
                                                                            <span class="files-icon"><i
                                                                                    class="fa fa-file-pdf-o"></i></span>
                                                                        </div>
                                                                        <div class="files-info">
                                                                            <span class="file-name text-ellipsis">AHA
                                                                                Selfcare Mobile Application
                                                                                Test-Cases.xls</span>
                                                                            <span class="file-author"><a
                                                                                    href="#">Loren Gatlin</a></span>
                                                                            <span class="file-date">May 31st at 6:53
                                                                                PM</span>
                                                                        </div>
                                                                        <ul class="files-action">
                                                                            <li class="dropdown dropdown-action">
                                                                                <a href="" class="dropdown-toggle"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"><i
                                                                                        class="material-icons">more_horiz</i></a>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item"
                                                                                        href="javascript:void(0)">Download</a>
                                                                                    <a class="dropdown-item"
                                                                                        href="#" data-toggle="modal"
                                                                                        data-target="#share_files">Share</a>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-pane" id="my_files">
                                                            <ul class="files-list">
                                                                <li>
                                                                    <div class="files-cont">
                                                                        <div class="file-type">
                                                                            <span class="files-icon"><i
                                                                                    class="fa fa-file-pdf-o"></i></span>
                                                                        </div>
                                                                        <div class="files-info">
                                                                            <span class="file-name text-ellipsis">AHA
                                                                                Selfcare Mobile Application
                                                                                Test-Cases.xls</span>
                                                                            <span class="file-author"><a
                                                                                    href="#">John Doe</a></span>
                                                                            <span class="file-date">May 31st at 6:53
                                                                                PM</span>
                                                                        </div>
                                                                        <ul class="files-action">
                                                                            <li class="dropdown dropdown-action">
                                                                                <a href="" class="dropdown-toggle"
                                                                                    data-toggle="dropdown"
                                                                                    aria-expanded="false"><i
                                                                                        class="material-icons">more_horiz</i></a>
                                                                                <div class="dropdown-menu">
                                                                                    <a class="dropdown-item"
                                                                                        href="javascript:void(0)">Download</a>
                                                                                    <a class="dropdown-item"
                                                                                        href="#" data-toggle="modal"
                                                                                        data-target="#share_files">Share</a>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>




                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="drag_files" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Drag and Drop Files Upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="js-upload-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file[]" id="file-input" multiple hidden>
                            <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">

                            <div class="upload-drop-zone" id="drop-zone">
                                <i class="fa fa-cloud-upload fa-2x"></i>
                                <span class="upload-text">Drag & Drop, Paste, or Click to Upload</span>
                            </div>

                            <h4>Uploading</h4>
                            <ul class="upload-list" id="upload-list"></ul>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- CREATE GROUP MODAL --}}
        <div id="add_group" class="modal custom-modal fade" tabindex="-1" role="dialog"
            class="modal custom-modal fade" tabindex="-1" role="dialog" aria-labelledby="addGroupLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addGroupLabel">Create a Group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <form id="createGroupForm" action="{{ route('group.create') }}" method="POST">
                            @csrf

                            <!-- Group Name -->
                            <div class="form-group">
                                <label for="group_name">Group Name <span class="text-danger">*</span></label>
                                <input id="group_name" class="form-control" type="text" name="name"
                                    placeholder="Enter group name" required>
                            </div>

                            <!-- Add Participants -->
                            <div class="form-group">
                                <label>Add Participants</label>
                                <!-- Input for searching/filtering users -->
                                <input type="text" id="group_user_search" class="form-control mb-2"
                                    placeholder="Search users...">

                                <!-- Users dropdown -->
                                <div id="group_user_dropdown" class="user-dropdown border rounded p-2 mb-2">
                                    @foreach ($users as $user)
                                        <div class="user-item d-flex align-items-center p-1 mb-1 rounded"
                                            data-id="{{ $user->id }}"
                                            data-avatar="{{ URL::to('/assets/images/' . $user->avatar) }}"
                                            data-name="{{ $user->name }}" data-position="{{ $user->position }}"
                                            data-lastseen="{{ $user->last_seen }}">
                                            <img src="{{ URL::to('/assets/images/' . $user->avatar) }}"
                                                class="rounded-circle mr-2" width="30" height="30">
                                            <div>
                                                <div class="user-name">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->position }}</small>
                                            </div>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary ml-auto add-user-btn">+</button>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Selected Users -->
                                <div id="group_selected_users" class="selected-users d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                            <!-- Optional Invites -->
                            <div class="form-group">
                                <label for="group_invites">Send invites to (optional)</label>
                                <input id="group_invites" class="form-control" type="text" name="invites"
                                    placeholder="user1@example.com, user2@example.com">
                            </div>

                            <div class="submit-section text-right">
                                <button type="submit" class="btn btn-primary submit-btn">Create Group</button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
        <div id="all_group" class="modal custom-modal fade" tabindex="-1" role="dialog"
            aria-labelledby="allGroupLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="allGroupLabel">All Groups</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <ul class="list-group">
                            <!-- Loop through groups -->
                            @forelse($groups as $group)
                                <li class="list-group-item p-0">
                                    <a href="{{ route('group.chat', $group->id) }}"
                                        class="d-flex justify-content-between align-items-center flex-column flex-md-row text-decoration-none text-dark p-3">

                                        <div>
                                            <strong>{{ $group->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Created by: {{ $group->creator->name ?? 'N/A' }}
                                            </small>
                                        </div>

                                    </a>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">No groups found.</li>
                            @endforelse

                        </ul>
                    </div>

                    <!-- Modal Footer -->
                    {{-- <div class="modal-footer">
                        <a href="{{ route('group.create') }}" class="btn btn-success">Create New Group</a>
                    </div> --}}

                </div>
            </div>
        </div>




        <div id="add_chat_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Direct Chat</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">


                        <div class="input-group mb-3">
                            <input id="searchUserInput" data-search-url="{{ route('user.search') }}"
                                placeholder="Search user to start chat" class="form-control" type="text">
                            <span class="input-group-append">
                                <button id="searchUserBtn" type="button" class="btn btn-primary">Search</button>
                            </span>
                        </div>


                        <ul id="search-results" class="list-group mt-2"></ul>

                        <div class="mt-4">
                            <h5>Recent Conversations</h5>
                            <ul class="chat-user-list">
                                @foreach ($users as $user)
                                    <li>
                                        <a href="{{ route('chat', $user->user_id) }}">
                                            <div class="media">
                                                <span class="avatar align-self-center">
                                                    <img src="{{ URL::to('/assets/images/' . $user->avatar) }}"
                                                        alt="">
                                                </span>
                                                <div class="media-body align-self-center text-nowrap">
                                                    <div class="user-name">{{ $user->name }}</div>
                                                    <span class="designation">{{ $user->position }}</span>
                                                </div>
                                                <div class="text-nowrap align-self-center">
                                                    <div class="online-date">{{ $user->last_seen }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <div id="share_files" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="files-share-list">
                            <div class="files-cont">
                                <div class="file-type">
                                    <span class="files-icon"><i class="fa fa-file-pdf-o"></i></span>
                                </div>
                                <div class="files-info">
                                    <span class="file-name text-ellipsis">AHA Selfcare Mobile Application
                                        Test-Cases.xls</span>
                                    <span class="file-author"><a href="#">Bernardo Galaviz</a></span> <span
                                        class="file-date">May 31st at 6:53 PM</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Share With</label>
                            <input class="form-control" type="text">
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Share</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdown = document.getElementById('group_user_dropdown');
            const selectedUsersDiv = document.getElementById('group_selected_users');
            const searchInput = document.getElementById('group_user_search');


            const selectedUsers = new Map();

            // Add user
            userDropdown.querySelectorAll('.add-user-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const userItem = e.target.closest('.user-item');
                    const id = userItem.dataset.id;
                    if (!selectedUsers.has(id)) {
                        selectedUsers.set(id, userItem.dataset);
                        renderSelectedUsers();
                    }
                });
            });


            function renderSelectedUsers() {
                selectedUsersDiv.innerHTML = '';
                selectedUsers.forEach(user => {
                    const chip = document.createElement('div');
                    chip.className = 'selected-user-chip';
                    chip.innerHTML = `
                <img src="${user.avatar}" alt="${user.name}">
                <span>${user.name}</span>
                <button type="button" class="remove-user-chip">&times;</button>
            `;
                    chip.querySelector('.remove-user-chip').addEventListener('click', () => {
                        selectedUsers.delete(user.id);
                        renderSelectedUsers();
                    });
                    selectedUsersDiv.appendChild(chip);
                });
            }


            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                userDropdown.querySelectorAll('.user-item').forEach(item => {
                    const name = item.dataset.name.toLowerCase();
                    item.style.display = name.includes(query) ? 'flex' : 'none';
                });
            });


            const form = document.getElementById('createGroupForm');
            form.addEventListener('submit', function() {
                document.querySelectorAll('.selected-user-input').forEach(el => el.remove());
                selectedUsers.forEach(user => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'participants[]';
                    input.value = user.id;
                    input.className = 'selected-user-input';
                    form.appendChild(input);
                });
            });
        });
    </script>
    <script>
        $('#chatForm').on('submit', function(e) {
            e.preventDefault();

            let editId = $('#editMessageId').val().trim();

            if (editId) {

                updateMessage();
                return;
            }


            let message = $('#message_id').val().trim();
            let receiverId = $('#receiver_id').val();

            if (message === '') {
                $('#msgError').text('Message cannot be empty').removeClass('d-none');
                return;
            } else {
                $('#msgError').addClass('d-none');
            }

            $('#sendBtn').prop('disabled', true);

            $.ajax({
                url: "{{ route('chat.send') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    message: message,
                    receiver_id: receiverId
                },
                success: function(response) {
                    $('#message_id').val('');
                    $('#sendBtn').prop('disabled', false);
                    fetchMessages();
                },
                error: function(xhr) {
                    $('#sendBtn').prop('disabled', false);
                    console.log(xhr.responseText);
                    alert('Something went wrong!');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // Search when button clicked
            $('#add_chat_user').on('click', '#searchUserBtn', function(e) {
                e.preventDefault();
                searchUsers();
            });

            // Search when Enter key pressed
            $('#add_chat_user').on('keyup', '#searchUserInput', function(e) {
                if (e.key === 'Enter') {
                    searchUsers();
                }
            });

            function searchUsers() {
                let query = $('#searchUserInput').val().trim();
                let url = $('#searchUserInput').data('search-url');

                if (query.length < 2) {
                    $('#search-results').empty();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        q: query
                    },
                    success: function(res) {
                        $('#search-results').empty();

                        if (res.length === 0) {
                            $('#search-results').html(
                                '<li class="list-group-item text-muted">No users found</li>');
                            return;
                        }

                        res.forEach(user => {
                            $('#search-results').append(`
                        <li class="list-group-item user-item" data-id="${user.id}">
                            ${user.name} <small class="text-muted">(${user.email})</small>
                        </li>
                    `);
                        });
                    },
                    error: function(xhr) {
                        console.error("Search error:", xhr.responseText);
                    }
                });
            }

            // Click on user to open chat
            $('#add_chat_user').on('click', '.user-item', function() {
                let userId = $(this).data('id');
                if (userId) {
                    window.location.href = `/chat/${userId}`;
                }
            });

        });
    </script>










    <script>
        // function showToast(message, type = 'success') {
        //     let bgColor = type === 'success' ? '#28a745' : '#dc3545';

        //     let toast = document.createElement('div');
        //     toast.innerText = message;
        //     toast.style.position = 'fixed';
        //     toast.style.bottom = '20px';
        //     toast.style.right = '20px';
        //     toast.style.background = bgColor;
        //     toast.style.color = '#fff';
        //     toast.style.padding = '10px 15px';
        //     toast.style.borderRadius = '6px';
        //     toast.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
        //     toast.style.zIndex = '9999';
        //     toast.style.fontSize = '14px';

        //     document.body.appendChild(toast);

        //     setTimeout(() => {
        //         toast.style.opacity = '0';
        //         setTimeout(() => toast.remove(), 400);
        //     }, 2500);
        // }
        const myId = {{ auth()->id() }};
        const avatarBaseUrl = '{{ URL::to('/assets/images/') }}';


        function fetchMessages() {
            let receiverId = $('#receiver_id').val();
            let chatBox = $('.chats');

            $.ajax({
                url: '/chat/messages/' + receiverId,
                type: 'GET',
                dataType: 'json',
                success: function(messages) {
                    chatBox.html('');

                    messages.forEach(function(msg) {
                        let deletedFor = msg.deleted_for ? JSON.parse(msg.deleted_for) : [];

                        if (deletedFor.includes(myId)) return;

                        let senderAvatar = msg.sender.avatar ?
                            `${avatarBaseUrl}/${msg.sender.avatar}` :
                            `${avatarBaseUrl}/default-avatar.png`;

                        let content = `<div style="position:relative;padding-right:30px;">`;


                        if (msg.sender_id === myId) {
                            content += `
                        <div style="position:absolute;top:0;right:0;">
                            <button onclick="toggleMenu(${msg.id})"
                                style="background:none;border:none;font-size:18px;cursor:pointer;">⋮</button>

                            <div id="menu-${msg.id}" class="chat-menu"
                                style="display:none;position:absolute;right:0;top:22px;background:#fff;
                                border:1px solid #ddd;border-radius:4px;
                                box-shadow:0 2px 6px rgba(0,0,0,0.15);z-index:100;">

                               <div onclick="startEditMessage(${msg.id}, \`${msg.body ?? ''}\`)"
     style="padding:8px 12px;cursor:pointer;">✏️ Edit</div>


                                <div onclick="deleteMessage(${msg.id}, false)"
                                    style="padding:8px 12px;cursor:pointer;color:red;">
                                    🗑 Delete for me
                                </div>

                                <div onclick="deleteMessage(${msg.id}, true)"
                                    style="padding:8px 12px;cursor:pointer;color:red;">
                                    🗑 Delete for everyone
                                </div>
                            </div>
                        </div>
                    `;
                        }

                        // Message content
                        if (msg.is_deleted) {
                            // Deleted for everyone → show notice to both sender and receiver
                            content +=
                                `<p style="font-style:italic;color:#888;">🚫 This message was deleted</p>`;
                        } else {
                            // Normal message display
                            if (msg.body) content += `<p style="margin:0 0 5px;">${msg.body}</p>`;
                            if (msg.file) {
                                content += `<p style="margin:0;">
                            <a href="/assets/images/${msg.file}" target="_blank">${msg.file}</a>
                        </p>`;
                            }
                        }

                        content += `</div>`; // close content wrapper

                        // Render chat bubble
                        if (msg.sender_id === myId) {
                            let seenStatus = msg.is_seen == 1 ?
                                '<small class="text-primary">✔✔ Seen</small>' :
                                '<small class="text-muted">✔ Sent</small>';

                            chatBox.append(`
                        <div class="chat chat-right">
                            <div class="chat-avatar">
                                <a href="#" class="avatar">
                                    <img src="${senderAvatar}" alt="You">
                                </a>
                            </div>
                            <div class="chat-body">
                                <div class="chat-bubble">
                                    <div class="chat-content">
                                        ${content}
                                        <span class="chat-time">${new Date(msg.created_at).toLocaleTimeString()}</span>
                                        <div>${seenStatus}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                        } else {
                            // Receiver sees message (menu hidden)
                            chatBox.append(`
                        <div class="chat chat-left">
                            <div class="chat-avatar">
                                <a href="#" class="avatar">
                                    <img src="${senderAvatar}" alt="${msg.sender.name}">
                                </a>
                            </div>
                            <div class="chat-body">
                                <div class="chat-bubble">
                                    <div class="chat-content">
                                        ${content}
                                        <span class="chat-time">${new Date(msg.created_at).toLocaleTimeString()}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                        }
                    });

                    chatBox.scrollTop(chatBox[0].scrollHeight);
                },
                error: function() {
                    showToast('Failed to fetch messages', 'error');
                }
            });
        }

        // Toggle menu
        window.toggleMenu = function(id) {
            $('.chat-menu').hide();
            $('#menu-' + id).toggle();
        };

        // Start editing
        window.startEditMessage = function(id, text) {
            $('#editMessageId').val(id);
            $('#message_id').val(text);
            $('#message_id').focus();
            $('.chat-menu').hide();
        };

        // Update message
        window.updateMessage = function() {
            let id = $('#editMessageId').val().trim();
            let body = $('#message_id').val().trim();

            if (!id) return alert('Message ID missing');
            if (!body) return alert('Message cannot be empty');

            $('#sendBtn').prop('disabled', true);
            $.ajax({
                url: `/chat/message/${id}/update`,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    body: body
                },
                success: function() {
                    $('#editMessageId').val('');
                    $('#message_id').val('');
                    $('#sendBtn').prop('disabled', false);
                    showToast('Message updated');
                    fetchMessages();
                },
                error: function() {
                    $('#sendBtn').prop('disabled', false);
                    showToast('Failed to update message', 'error');
                }
            });
        };

        // Delete message
        window.deleteMessage = function(id, forEveryone = false) {
            let confirmText = forEveryone ?
                'Delete message for everyone?' :
                'Delete message for me?';

            if (!confirm(confirmText)) return;

            $.ajax({
                url: `/chat/message/${id}/delete`,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    for_everyone: forEveryone ? 1 : 0
                },
                success: function(res) {
                    if (res.status) {
                        showToast(
                            forEveryone ?
                            'Message deleted for everyone' :
                            'Message deleted for me'
                        );
                        fetchMessages();
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.error || 'Failed to delete message';
                    showToast(msg, 'error');
                }
            });
        };


        fetchMessages();
        setInterval(fetchMessages, 2000);
    </script>

    <script>
        let chatBox = $('.chats');
        const authId = {{ auth()->id() }};
        let activeUserId = null;

        let isSearching = false;
        let currentSearch = '';



        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }

        function highlightText(text, keyword) {
            if (!keyword) return escapeHtml(text);

            let escaped = escapeHtml(text);
            let regex = new RegExp(`(${keyword})`, 'gi');
            return escaped.replace(regex, '<mark class="search-highlight">$1</mark>');
        }



        function renderMessage(msg) {
            let isMe = msg.sender.id === authId;


            let avatar = msg.sender.avatar ?
                `{{ URL::to('/assets/images/') }}/${msg.sender.avatar}` :
                `{{ URL::to('/assets/images/default-avatar.png') }}`;

            chatBox.append(`
        <div class="chat ${isMe ? 'chat-right' : 'chat-left'}">
            <div class="chat-avatar">
                <img src="${avatar}" class="rounded-circle" width="40" height="40"
                     onerror="this.src='{{ URL::to('/assets/images/default-avatar.png') }}'">
            </div>
            <div class="chat-body">
                <div class="chat-bubble">
                    <div class="chat-content">
                        <p>${highlightText(msg.body, currentSearch)}</p>
                        <span class="chat-time">
                            ${new Date(msg.created_at).toLocaleTimeString()}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    `);
        }

        function fetchMessages(userId) {
            if (isSearching) return;

            activeUserId = userId;

            $.ajax({
                url: `/chat/messages/${userId}`,
                type: "GET",
                success: function(data) {
                    chatBox.empty();
                    currentSearch = '';
                    data.forEach(renderMessage);
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                }
            });
        }



        function searchMessages() {
            let query = $('#chatSearch').val().trim();

            if (!query) {
                isSearching = false;
                currentSearch = '';
                chatBox.html('<p class="text-center text-muted">Type to search messages</p>');
                return;
            }

            isSearching = true;
            currentSearch = query;

            $.ajax({
                url: "{{ route('msg.search') }}",
                type: "GET",
                data: {
                    query
                },
                success: function(data) {
                    chatBox.empty();

                    if (!data.length) {
                        chatBox.html('<p class="text-center text-muted">No messages found</p>');
                        return;
                    }

                    data.forEach(renderMessage);
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                }
            });
        }



        $('#searchBtn').on('click', searchMessages);

        $('#chatSearch').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                searchMessages();
            }
        });

        $('#chatSearch').on('input', function() {
            if (!this.value.trim()) {
                isSearching = false;
                currentSearch = '';
                chatBox.html('<p class="text-center text-muted">Type to search messages</p>');
            }
        });


        $('.user-item').on('click', function() {
            isSearching = false;
            currentSearch = '';
            fetchMessages($(this).data('id'));
        });


        setInterval(function() {
            if (!isSearching && activeUserId) {
                fetchMessages(activeUserId);
            }
        }, 5000);
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const dropZone = document.getElementById("drop-zone");
            const fileInput = document.getElementById("file-input");
            const uploadList = document.getElementById("upload-list");
            const form = document.getElementById("js-upload-form");

            let selectedFiles = [];

            /* ------------------ Display Files ------------------ */
            function displayFiles() {
                uploadList.innerHTML = '';

                if (selectedFiles.length === 0) {
                    uploadList.innerHTML = `
                <li class="file-list placeholder-item">
                    <div class="upload-wrap">
                        <div class="file-name">
                            <i class="fa fa-cloud-upload"></i>
                            Drag & Drop, or Click to Upload
                        </div>
                    </div>
                </li>`;
                    return;
                }

                selectedFiles.forEach((file, index) => {
                    uploadList.innerHTML += `
                <li class="file-list">
                    <div class="upload-wrap">
                        <div class="file-name">
                            <i class="fa ${file.type.startsWith('image') ? 'fa-photo' : 'fa-file'}"></i>
                            ${file.name}
                        </div>
                        <div class="file-size">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                        <button type="button" class="file-close" data-index="${index}">
                            <i class="fa fa-close"></i>
                        </button>
                    </div>
                    <div class="progress progress-xs">
                        <div class="progress-bar bg-success" style="width:0%"></div>
                    </div>
                    <div class="upload-process">0%</div>
                </li>`;
                });

                document.querySelectorAll('.file-close').forEach(btn => {
                    btn.onclick = () => {
                        selectedFiles.splice(btn.dataset.index, 1);
                        displayFiles();
                    };
                });
            }

            document.addEventListener('paste', function(e) {
                const items = (e.clipboardData || window.clipboardData).items;
                if (!items) return;

                let pastedFiles = [];

                for (let i = 0; i < items.length; i++) {
                    const item = items[i];

                    if (item.kind === 'file') {
                        const file = item.getAsFile();
                        if (file) pastedFiles.push(file);
                    }
                }

                if (pastedFiles.length > 0) {
                    addFiles(pastedFiles);
                }
            });


            function addFiles(files) {
                [...files].forEach(file => {
                    const exists = selectedFiles.some(
                        f => f.name === file.name && f.size === file.size
                    );
                    if (!exists) {
                        selectedFiles.push(file);
                    }
                });


                fileInput.value = '';

                displayFiles();
            }


            dropZone.onclick = () => fileInput.click();
            fileInput.onchange = e => addFiles(e.target.files);

            dropZone.ondragover = e => {
                e.preventDefault();
                dropZone.classList.add("drag-over");
            };

            dropZone.ondragleave = () => dropZone.classList.remove("drag-over");

            dropZone.ondrop = e => {
                e.preventDefault();
                dropZone.classList.remove("drag-over");
                addFiles(e.dataTransfer.files);
            };

            /* ------------------ Message Helper ------------------ */
            function showMessage(type, text) {
                document.getElementById('upload-message')?.remove();
                const div = document.createElement('div');
                div.id = 'upload-message';
                div.className = `alert alert-${type} mt-2`;
                div.textContent = text;
                form.prepend(div);
                setTimeout(() => div.remove(), 5000);
            }

            /* ------------------ Submit Upload ------------------ */
            form.onsubmit = function(e) {
                e.preventDefault();

                const receiverId = form.querySelector('input[name="receiver_id"]').value;
                if (!receiverId) {
                    showMessage('warning', 'Please select a receiver.');
                    return;
                }

                if (selectedFiles.length === 0) {
                    showMessage('warning', 'Please select at least one file.');
                    return;
                }

                const formData = new FormData(form);
                selectedFiles.forEach(file => formData.append('file[]', file));

                // Reset progress UI
                document.querySelectorAll('.progress-bar').forEach(b => b.style.width = '0%');
                document.querySelectorAll('.upload-process').forEach(p => p.textContent = '0%');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/upload-files', true);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = e => {
                    if (!e.lengthComputable) return;
                    const percent = Math.round((e.loaded / e.total) * 100);
                    document.querySelectorAll('.progress-bar').forEach(b => b.style.width = percent + '%');
                    document.querySelectorAll('.upload-process').forEach(p => p.textContent = percent +
                        '%');
                };

                xhr.onload = () => {
                    let res;
                    try {
                        res = JSON.parse(xhr.responseText);
                    } catch {
                        showMessage('danger', 'Invalid server response.');
                        return;
                    }

                    if (xhr.status === 200) {
                        showMessage('success', res.message);


                        selectedFiles = [];
                        fileInput.value = '';

                        displayFiles();
                    } else if (xhr.status === 422) {
                        showMessage('warning', Object.values(res.errors).flat().join(', '));
                    } else {
                        showMessage('danger', res.message || 'Upload failed.');
                    }
                };

                xhr.onerror = () => showMessage('danger', 'Network error occurred.');
                xhr.send(formData);
            };

            displayFiles();
        });

        /* ------------------ Open Modal Helper ------------------ */
        function openUploadModal(receiverId) {
            const input = document.querySelector('#drag_files input[name="receiver_id"]');
            if (input) input.value = receiverId;
            $('#drag_files').modal('show');
        }
    </script>


    <script>
        function fetchChatFiles() {

            let receiverId = $('#receiver_id').val();
            let authId = {{ auth()->id() }};

            $.get('/chat/files/' + receiverId, function(files) {

                let allHtml = '';
                let myHtml = '';

                files.forEach(file => {

                    let name = file.file.split('/').pop();
                    let url = '/storage/' + file.file;
                    let date = new Date(file.created_at).toLocaleString();

                    let html = `
            <li>
                <div class="files-cont">
                    <div class="file-type">
                        <span class="files-icon">
                            <i class="fa fa-file-o"></i>
                        </span>
                    </div>
                    <div class="files-info">
                        <span class="file-name text-ellipsis">${name}</span>
                        <span class="file-author">
                            <a href="#">${file.sender.name}</a>
                        </span>
                        <span class="file-date">${date}</span>
                    </div>
                    <ul class="files-action">
                        <li class="dropdown dropdown-action">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons">more_horiz</i>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="${url}" download>Download</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>`;

                    allHtml += html;

                    if (file.sender_id == authId) {
                        myHtml += html;
                    }
                });

                $('#all-files-list').html(allHtml || '<li>No files found</li>');
                $('#my-files-list').html(myHtml || '<li>No files uploaded by you</li>');
            });
        }

        // Load once (or on tab click)
        fetchChatFiles();
    </script>
@endsection
