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
                                        <a class="avatar" href="{{ route('employee.profile', $selectedUser->user_id) }}"
                                            title="Mike Litorus">
                                            <img src="{{ URL::to('/assets/images/' . $selectedUser->avatar) }}"
                                                alt="" class="rounded-circle">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="user-info float-left">
                                        <a href="{{ route('employee.profile', $selectedUser->user_id) }}"
                                            title="Mike Litorus"><span>{{ $selectedUser->name }}</span> <i
                                                class="typing-text">{{ $selectedUser->is_online }}</i></a>
                                        <span class="last-seen">{{ $selectedUser->last_seen }}</span>
                                    </div>
                                </div>

                                <div class="search-box">
                                    <div class="input-group input-group-sm">
                                        <input type="text" placeholder="Search" class="form-control">
                                        <span class="input-group-append">
                                            <button type="button" class="btn"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <ul class="nav custom-menu">
                                    <li class="nav-item">
                                        <a class="nav-link task-chat profile-rightbar float-right" id="task_chat"
                                            href="#task_window"><i class="fa fa-user"></i></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="voice-call.html" class="nav-link"><i class="fa fa-phone"></i></a>
                                    </li>
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
        <div id="add_group" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create a group</h5>
                        <button type="button" class="close" data-dismiss="modal" data-target="#add_group"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Groups are where your team communicates. They're best when organized around a topic — #leads, for
                            example.</p>
                        <form action="{{ route('group.create') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Group Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required>
                            </div>

                            <div class="form-group">
                                <label>Send invites to: <span class="text-muted-light">(optional)</span></label>
                                <input class="form-control" type="text" name="invites">
                            </div>

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="add_chat_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Direct Chat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group m-b-30">
                            <input id="chat-search" placeholder="Search to start a chat"
                                class="form-control search-input" type="text">
                            <span class="input-group-append">
                                <button id="chat-search-btn" class="btn btn-primary">Search</button>
                            </span>
                        </div>
                        <div>
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
                                                    <div class="online-date">1 day ago</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
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
        $('#chatForm').on('submit', function(e) {
            e.preventDefault();

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
        $(document).on('keyup', '#chat-search', function() {
            alert('om namaha shivay');

            let q = $(this).val().trim();

            if (q.length === 0) {
                $('#search-results').html('');
                return;
            }

            $.ajax({
                url: "{{ route('chat.search') }}",
                type: "GET",
                data: {
                    q: q
                },
                success: function(res) {

                    $('#search-results').html('');

                    if (res.length === 0) {
                        $('#search-results').html('<li>No users found</li>');
                        return;
                    }

                    res.forEach(function(user) {

                        let avatar = user.avatar ?
                            '/storage/' + user.avatar :
                            '/assets/img/default-avatar.png';

                        $('#search-results').append(`
                    <li>
                        <img src="${avatar}" width="30" style="border-radius:50%">
                        ${user.name} (${user.email})
                    </li>
                `);
                    });
                }
            });

        });
    </script>


    {{-- 
$(function() {
    function doSearch() {
       
        let q = $('#chat-search').val().trim();
        if (!q) return;

        $.get("{{ route('chat.search') }}", { q }, function(res) {
            let list = $('#users-list').empty();

            if (!res.length) {
                return list.append('<li><div class="p-2">No users found</div></li>');
            }

            res.forEach(u => {
                let avatar = u.avatar ?
                    "{{ url('assets/images') }}/" + u.avatar :
                    "{{ asset('assets/img/profiles/default.jpg') }}";

                list.append(`
                    <li>
                        <a href="#" class="start-chat" data-user-id="${u.id}">
                            <div class="media">
                                <span class="avatar align-self-center">
                                    <img src="${avatar}" alt="Avatar">
                                </span>
                                <div class="media-body align-self-center text-nowrap">
                                    <div class="user-name">${u.name}</div>
                                    <span class="designation">${u.email}</span>
                                </div>
                                <div class="text-nowrap align-self-center">
                                    <div class="online-date">—</div>
                                </div>
                            </div>
                        </a>
                    </li>
                `);
            });
        });
    }

    $('#chat-search-btn').click(doSearch);
    $('#chat-search').on('keypress', e => {
        if (e.which === 13) doSearch();
    });

    $(document).on('click', '.start-chat', function(e) {
        e.preventDefault();
        let userId = $(this).data('user-id');

        $.ajax({
            url: '{{ route('chat.start') }}',
            type: 'POST',
            data: { user_id: userId },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                console.log('Chat started:', res);
            }
        });
    });
}); --}}

    <script>
        $('#sendMessageForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('chat.send') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    receiver_id: $('#receiver_id').val(),
                    message: $('#message').val()
                },
                success: function(res) {
                    $('#message').val('');
                    loadMessages($('#receiver_id').val());
                },
                error: function(err) {
                    console.log(err.responseJSON);
                }
            });
        });
    </script>



    <script>
        function fetchMessages() {
            let receiverId = $('#receiver_id').val();


            let avatarBaseUrl = '{{ URL::to('/assets/images/') }}';

            $.ajax({
                url: '/chat/messages/' + receiverId,
                type: 'GET',
                success: function(messages) {

                    let chatBox = $('.chats');
                    chatBox.html('');

                    messages.forEach(function(msg) {


                        let senderAvatar = msg.sender.avatar ?
                            `${avatarBaseUrl}/${msg.sender.avatar}` :
                            `${avatarBaseUrl}/default-avatar.png`;

                        let receiverAvatar = msg.receiver.avatar ?
                            `${avatarBaseUrl}/${msg.receiver.avatar}` :
                            `${avatarBaseUrl}/default-avatar.png`;


                        if (msg.sender_id == {{ auth()->id() }}) {

                            let seenStatus = msg.is_seen == 1 ?
                                '<small class="text-primary">✔✔ Seen</small>' :
                                '<small class="text-muted">✔ Sent</small>';

                            chatBox.append(() => {
                        if (!msg.body && !msg.file) return '';
                        let content = '';

                        if (msg.body) {
                            content += `<p>${msg.body}</p>`;
                        }

                        if (msg.file) {
                            content += `<p><a href="/assets/images/${msg.file}" target="_blank">${msg.file}</a></p>`;
                        }

                        return `
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
                        `;
                    });


                        } else {

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
                                        <p>${msg.body}</p>
                                        <span class="chat-time">
                                            ${new Date(msg.created_at).toLocaleTimeString()}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                        }
                    });


                    chatBox.scrollTop(chatBox[0].scrollHeight);
                }
            });
        }


        setInterval(fetchMessages, 2000);
        fetchMessages();
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById("drop-zone");
            const fileInput = document.getElementById("file-input");
            const uploadList = document.getElementById("upload-list");
            const form = document.getElementById("js-upload-form");

            let selectedFiles = [];

            // Click to open file picker
            dropZone.addEventListener("click", () => fileInput.click());

            // Drag over effect
            dropZone.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropZone.classList.add("drag-over");
            });

            dropZone.addEventListener("dragleave", () => dropZone.classList.remove("drag-over"));

            // Handle drop
            dropZone.addEventListener("drop", (e) => {
                e.preventDefault();
                dropZone.classList.remove("drag-over");
                addFiles(e.dataTransfer.files);
            });

            // File input change
            fileInput.addEventListener("change", (e) => addFiles(e.target.files));

            // Add files to array
            function addFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    // Prevent duplicates by name + size
                    if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);
                    }
                }
                displayFiles();
            }

            // Display files in the list
            function displayFiles() {
                uploadList.innerHTML = '';
                if (selectedFiles.length === 0) {
                    uploadList.innerHTML = '<li>No files selected</li>';
                    return;
                }

                selectedFiles.forEach((file, index) => {
                    const li = document.createElement('li');
                    li.classList.add('file-list');

                    li.innerHTML = `
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
                <div class="progress progress-xs progress-striped">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="upload-process">0% done</div>
            `;
                    uploadList.appendChild(li);
                });

                // Remove file button
                document.querySelectorAll('.file-close').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const index = e.currentTarget.getAttribute('data-index');
                        selectedFiles.splice(index, 1);
                        displayFiles();
                    });
                });
            }


            form.addEventListener("submit", function(e) {
                e.preventDefault();
                console.log("Submit button clicked!");

                if (selectedFiles.length === 0) {
                    alert("Please select at least one file!");
                    return;
                }

                const formData = new FormData();
                selectedFiles.forEach(file => formData.append('file[]', file));

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "/upload-files", true);


                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (tokenMeta) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', tokenMeta.getAttribute('content'));
                }


                xhr.upload.addEventListener("progress", function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        document.querySelectorAll('.progress-bar').forEach(pb => pb.style.width =
                            percent + '%');
                        document.querySelectorAll('.upload-process').forEach(up => up.textContent =
                            percent + '% done');
                    }
                });

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.querySelectorAll('.progress-bar').forEach(pb => pb.style.width =
                            '100%');
                        document.querySelectorAll('.upload-process').forEach(up => up.textContent =
                            'Completed');

                        try {
                            const res = JSON.parse(xhr.responseText);
                            alert(res.message);
                        } catch (err) {
                            alert('Upload completed, but response could not be read.');
                        }


                        selectedFiles = [];
                        fileInput.value = '';
                        displayFiles();
                    } else {
                        document.querySelectorAll('.upload-process').forEach(up => up.textContent =
                            'Failed');
                        document.querySelectorAll('.progress-bar').forEach(pb => pb.classList.add(
                            'bg-danger'));
                        alert('Upload failed!');
                    }
                };

                xhr.onerror = function() {
                    alert('Upload failed due to a network error.');
                }

                xhr.send(formData);
            });


            displayFiles();
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const dropZone = document.getElementById("drop-zone");
            const fileInput = document.getElementById("file-input");
            const uploadList = document.getElementById("upload-list");
            const form = document.getElementById("js-upload-form");
            let selectedFiles = [];

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

            function addFiles(files) {
                [...files].forEach(file => {
                    if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);
                    }
                });
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
            function showMessage(type, text) {
                document.getElementById('upload-message')?.remove();
                const div = document.createElement('div');
                div.id = 'upload-message';
                div.className = `alert alert-${type} mt-2`;
                div.textContent = text;
                form.prepend(div);
                setTimeout(() => div.remove(), 5000);
            }
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
                        console.error(xhr.responseText);
                        showMessage('danger', 'Invalid server response.');
                        return;
                    }

                    if (xhr.status === 200) {
                        showMessage('success', res.message);
                        selectedFiles = [];
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

        /* ------------------ Helper to open modal with receiver ------------------ */
        function openUploadModal(receiverId) {
            const input = document.querySelector('#drag_files input[name="receiver_id"]');
            if (input) input.value = receiverId;
            $('#drag_files').modal('show');
        }
    </script>
@endsection
