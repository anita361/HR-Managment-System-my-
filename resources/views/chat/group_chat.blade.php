@extends('layouts.chat')

@section('content')

    <div class="page-wrapper">
        <div class="chat-main-row">
            <div class="container py-3">
                <div class="row justify-content-center">
                    <div class="col-lg-9 message-view task-view">



                        <div class="chat-window d-flex flex-column shadow-sm rounded bg-light">
                            <div
                                class="chat-header d-flex justify-content-between align-items-center p-3 border-bottom bg-white rounded-top">


                                <div class="d-flex align-items-center dropdown">
                                    <a href="#" class="dropdown-toggle nav-link d-flex align-items-center p-0"
                                        data-toggle="dropdown">
                                        <span class="user-img me-2">
                                            <img src="{{ $group->avatar ? URL::to('/assets/images/' . $group->avatar) : asset('assets/images/group.png') }}"
                                                alt="" class="rounded-circle"
                                                style="width:40px;height:40px;object-fit:cover;">
                                        </span>
                                        <h5 class="mb-0">{{ $group->name }}</h5>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('groups.show', $group->id) }}">
                                                <i class="fa fa-info-circle me-1"></i> Group Info
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('groups.members', $group->id) }}">
                                                <i class="fa fa-users me-1"></i> Members
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('groups.leave', $group->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to leave this group?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fa fa-sign-out-alt me-1"></i> Leave Group
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>


                                <ul class="nav custom-menu align-items-center">


                                    <li class="nav-item me-2">
                                        <div id="searchContainer" data-search-url="{{ route('search.grpmsg', $group->id) }}"
                                            class="search-box mb-0">
                                            <div class="input-group input-group-sm">
                                                <input type="text" placeholder="Search messages" class="form-control"
                                                    id="chatSearch">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn" id="searchBtn">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <ul id="searchResults"></ul>
                                    </li>



                                    <li class="nav-item">
                                        <a href="{{ route('group.call', $group->id) }}" class="nav-link"
                                            title="Group Voice Call">
                                            <i class="fa fa-phone"></i>
                                        </a>
                                    </li>

                                    <audio id="remoteAudio" autoplay></audio>


                                    <li class="nav-item">
                                        <a href="{{ route('group.video.call', $group->id) }}" class="nav-link"
                                            title="Video Call">
                                            <i class="fa fa-video-camera"></i>
                                        </a>
                                    </li>


                                    <li class="nav-item">
                                        <a class="nav-link task-chat profile-rightbar" id="task_chat" href="#task_window"><i
                                                class="fa fa-user"></i></a>
                                    </li>
                                    <li class="nav-item dropdown dropdown-action">
                                        <a aria-expanded="false" data-toggle="dropdown" class="nav-link dropdown-toggle"
                                            href="#">
                                            <i class="fa fa-cog"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right p-2">
                                            <form action="{{ route('group.conversations.delete', $group->id) }}"
                                                method="POST" class="px-2 py-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                                    onclick="return confirm('Are you sure you want to delete all group conversations?')">
                                                    <i class="fa fa-trash mr-1"></i> Delete All Conversations
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                </ul>


                                <audio id="remoteAudio" autoplay></audio>

                            </div>
                        </div>



                        <div id="chat-box" class="chat-box flex-grow-1 p-3 overflow-auto"
                            style="background:#eef1f5; position:relative;">

                            @foreach ($group->messages as $message)
                                @php
                                    $deletedFor = $message->deleted_for ?? [];
                                    if (!is_array($deletedFor)) {
                                        $deletedFor = json_decode($deletedFor, true) ?? [];
                                    }

                                    $isDeletedForUser = in_array(auth()->id(), $deletedFor);
                                    $isAuthSender = $message->sender_id == auth()->id();
                                    $avatarPath = $message->sender->avatar
                                        ? URL::to('/assets/images/' . $message->sender->avatar)
                                        : asset('assets/images/default-avatar.png');

                                    if ($isDeletedForUser) {
                                        continue;
                                    }
                                @endphp

                                <div id="message-{{ $message->id }}"
                                    class="d-flex mb-3 {{ $isAuthSender ? 'justify-content-end' : 'justify-content-start' }} align-items-end">

                                    @unless ($isAuthSender)
                                        <img src="{{ $avatarPath }}" class="rounded-circle me-2"
                                            style="width:40px;height:40px;object-fit:cover;">
                                    @endunless

                                    <div class="chat-bubble p-2 px-3 rounded shadow-sm {{ $isAuthSender ? 'bg-primary text-white' : 'bg-white text-dark' }}"
                                        style="position:relative;">


                                        @if ($isAuthSender && !$message->is_deleted)
                                            <div style="position:absolute;top:0;right:0;">
                                                <button onclick="toggleMenu({{ $message->id }})"
                                                    style="background:none;border:none;font-size:18px;cursor:pointer;">⋮</button>
                                                <div id="menu-{{ $message->id }}" class="chat-menu"
                                                    style="display:none;position:absolute;right:0;top:22px;background:#fff;border:1px solid #ddd;border-radius:4px;box-shadow:0 2px 6px rgba(0,0,0,0.15);z-index:100;min-width:140px; color:red;">
                                                    <div onclick='startEditMessage({{ $message->id }}, @json($message->body))'
                                                        style="padding:8px 12px; cursor:pointer;">✏️Edit</div>
                                                    <div onclick="deleteMessage({{ $message->id }}, false)"
                                                        style="padding:8px 12px;cursor:pointer;color:red;">🗑 Delete for me
                                                    </div>
                                                    <div onclick="deleteMessage({{ $message->id }}, true)"
                                                        style="padding:8px 12px;cursor:pointer;color:red;">🗑 Delete for
                                                        everyone</div>
                                                </div>
                                            </div>
                                        @endif


                                        <div class="mt-1 message-body" id="message-body-{{ $message->id }}">
                                            @if ($message->is_deleted)
                                                <em style="color:#888;">🚫 This message was deleted</em>
                                            @else
                                                <span class="message-text">{{ $message->body }}</span>


                                                @if (!empty($message->file_path))
                                                    @php
                                                        $fileUrl = asset($message->file_path);
                                                        $isImage = \Illuminate\Support\Str::startsWith(
                                                            $message->file_type,
                                                            'image',
                                                        );
                                                    @endphp

                                                    <div class="mt-2">
                                                        @if ($isImage)
                                                            <img src="{{ $fileUrl }}" class="img-fluid rounded"
                                                                style="max-width:220px;">
                                                        @else
                                                            <a href="{{ $fileUrl }}" target="_blank"
                                                                class="text-decoration-none">
                                                                📎 {{ $message->file_name }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if ($message->edited_at)
                                                    <small style="color:#888;"> (edited)</small>
                                                @endif
                                            @endif
                                        </div>

                                        {{-- <small
                                            class="text-muted float-end">{{ $message->created_at->format('M d, Y h:i A') }}</small> --}}
                                        <small class="text-muted float-end">
                                            {{ $message->created_at->timezone('Asia/Kolkata')->format('M d, Y h:i A') }}
                                        </small>

                                    </div>

                                    @if ($isAuthSender)
                                        <img src="{{ $avatarPath }}" class="rounded-circle ms-2"
                                            style="width:40px;height:40px;object-fit:cover;">
                                    @endif
                                </div>
                            @endforeach
                        </div>




                        <div class="chat-input p-3 border-top bg-white">
                            <form id="groupMessageForm" method="POST"
                                action="{{ route('group.message.send', $group->id) }}"
                                class="d-flex align-items-center gap-2" enctype="multipart/form-data">
                                @csrf


                                <button type="button" class="btn btn-light rounded-circle" data-toggle="modal"
                                    data-target="#drag_files">
                                    <i class="fa fa-paperclip"></i>
                                </button>

                                <textarea class="form-control rounded-pill" rows="1" id="group_message_id" name="message"
                                    placeholder="Type a message..." required></textarea>
                                <input type="hidden" id="group_id" value="{{ $group->id }}">
                                <button class="btn btn-primary rounded-circle px-3" type="submit" id="groupSendBtn">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </form>
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
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="js-upload-form" method="POST" enctype="multipart/form-data"
                        action="{{ route('chat.group.uploadFiles') }}">
                        @csrf

                        <input type="file" name="file[]" id="file-input" multiple hidden>
                        <input type="hidden" name="group_id" value="{{ $group->id }}">

                        <div id="drop-zone" class="upload-drop-zone"
                            style="padding:30px;border:2px dashed #ccc;text-align:center;cursor:pointer;">
                            Drag & Drop, Paste, or Click to Upload
                        </div>

                        <ul id="upload-list"></ul>

                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-danger">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>



    {{-- <style>
        .chat-bubble {
            max-width: 70%;
            word-wrap: break-word;
        }

        .chat-box::-webkit-scrollbar {
            width: 6px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }
    </style> --}}


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
                            Drag & Drop, Paste, or Click to Upload
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
                    if (items[i].kind === 'file') {
                        const file = items[i].getAsFile();
                        if (file) pastedFiles.push(file);
                    }
                }

                if (pastedFiles.length > 0) addFiles(pastedFiles);
            });

            function addFiles(files) {
                [...files].forEach(file => {
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) selectedFiles.push(file);
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


            function showMessage(type, text) {
                document.getElementById('upload-message')?.remove();
                const div = document.createElement('div');
                div.id = 'upload-message';
                div.className = `alert alert-${type} mt-2`;
                div.textContent = text;
                form.prepend(div);
                setTimeout(() => div.remove(), 4000);
            }


            form.onsubmit = function(e) {
                e.preventDefault();

                const groupId = document.getElementById('group_id').value;

                if (!groupId) {
                    showMessage('warning', 'Group not selected.');
                    return;
                }

                if (selectedFiles.length === 0) {
                    showMessage('warning', 'Please select at least one file.');
                    return;
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('group_id', groupId);
                selectedFiles.forEach(file => formData.append('file[]', file));

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('chat.group.uploadFiles') }}', true);
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
                        showMessage('danger', 'Invalid server response');
                        return;
                    }

                    if (xhr.status === 200) {
                        showMessage('success', res.message);
                        selectedFiles = [];
                        displayFiles();
                        fetchGroupFiles();
                    } else {
                        showMessage('danger', res.message || 'Upload failed');
                    }
                };

                xhr.onerror = () => showMessage('danger', 'Network error occurred');
                xhr.send(formData);
            };

            displayFiles();
        });


        function fetchGroupFiles() {
            let groupId = document.getElementById('group_id').value;

            $.get('{{ url('/chat/group/files') }}/' + groupId, function(files) {

                let html = '';

                files.forEach(file => {
                    let url = '/' + file.file_path;
                    let date = new Date(file.created_at).toLocaleString();

                    html += `
            <li>
                <div class="files-cont">
                    <div class="file-type">
                        <span class="files-icon"><i class="fa fa-file-o"></i></span>
                    </div>
                    <div class="files-info">
                        <span class="file-name text-ellipsis">${file.file_name}</span>
                        <span class="file-author">${file.sender.name}</span>
                        <span class="file-date">${date}</span>
                    </div>
                    <ul class="files-action">
                        <li>
                            <a class="dropdown-item" href="${url}" download>Download</a>
                        </li>
                    </ul>
                </div>
            </li>`;
                });

                $('#group-files-list').html(html || '<li>No files found</li>');
            });
        }
    </script>
    <script>
        let editMessageId = null;

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('groupMessageForm');
            const textarea = document.getElementById('group_message_id');
            if (!form || !textarea) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = textarea.value.trim();
                if (!message) return;

                if (editMessageId) {
                    fetch(`/chat/group/message/${editMessageId}/update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                body: message
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status) {
                                const messageEl = document.querySelector(
                                    `#message-body-${editMessageId} .message-text`);
                                if (messageEl) messageEl.innerText = message;
                                editMessageId = null;
                                textarea.value = '';
                                textarea.placeholder = "Type a message...";
                            } else {
                                alert(data.error || 'Failed to update message.');
                            }
                        });
                } else {

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status) {
                                textarea.value = '';
                                console.log('Message sent');
                            } else {
                                alert(data.error || 'Failed to send message.');
                            }
                        }).catch(err => console.error(err));
                }
            });


            window.startEditMessage = function(id, body) {
                editMessageId = id;
                textarea.value = body;
                textarea.focus();
                textarea.placeholder = "Editing message...";
                const menu = document.getElementById('menu-' + id);
                if (menu) menu.style.display = 'none';
            };


            window.deleteMessage = function(id, forEveryone) {

                if (!confirm(`Delete this message ${forEveryone ? 'for everyone' : 'for yourself'}?`)) return;

                fetch(`/chat/group/message/${id}/delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            for_everyone: forEveryone ? 1 : 0
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            const messageEl = document.getElementById('message-' + id);
                            const bodyEl = document.getElementById('message-body-' + id);

                            if (forEveryone && bodyEl) {
                                bodyEl.innerHTML =
                                    '<em style="color:#888;">🚫 This message was deleted</em>';
                            } else if (!forEveryone) {
                                if (messageEl) messageEl.remove();
                            }
                        } else {
                            alert(data.error || 'Failed to delete message.');
                        }
                    })
                    .catch(err => console.error(err));
            };


            window.toggleMenu = function(id) {
                const menu = document.getElementById('menu-' + id);
                if (!menu) return;
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            };
        });
    </script>

    {{-- <script>
        $(function() {


            function escapeHtml(text) {
                return text.replace(/[&<>"']/g, function(m) {
                    return ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    })[m];
                });
            }


            function highlightText(text, query) {
                if (!query) return escapeHtml(text);
                const regex = new RegExp(`(${query.replace(/[-/\\^$*+?.()|[\]{}]/g,'\\$&')})`, 'gi');
                return escapeHtml(text).replace(regex, '<mark>$1</mark>');
            }

            const searchUrl = $('#searchContainer').data('search-url');

            function performSearch() {
                const query = $('#chatSearch').val().trim();

                if (!query) {
                    $('#searchResults').html('');
                    return;
                }

                $.ajax({
                    url: searchUrl,
                    type: 'GET',
                    data: {
                        q: query
                    },
                    success: function(res) {
                        let html = '';

                        if (res.length === 0) {
                            html = '<div class="text-muted">No messages found</div>';
                        } else {
                            res.forEach(msg => {
                                html += `
                            <div class="d-flex align-items-start mb-2">
                                <img src="${msg.sender_avatar}" class="rounded-circle me-2" width="35" height="35">
                                <div>
                                    <div class="small text-muted">${msg.sender_name} • ${msg.time}</div>
                                    <div>${highlightText(msg.body, query)}</div>
                                </div>
                            </div>
                        `;
                            });
                        }

                        $('#searchResults').html(html);
                    },
                    error: function(err) {
                        console.error('AJAX error:', err);
                        $('#searchResults').html(
                            '<div class="text-danger">Error fetching messages</div>');
                    }
                });
            }


            $('#searchBtn').on('click', performSearch);


            $('#chatSearch').on('keypress', function(e) {
                if (e.which === 13) performSearch();
            });


            let typingTimer;
            $('#chatSearch').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(performSearch, 300);
            });

        });
    </script>
 --}}
    <script>
        $(function() {


            function escapeHtml(text) {
                return text.replace(/[&<>"']/g, function(m) {
                    return ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    })[m];
                });
            }


            function highlightText(text, query) {
                if (!query) return escapeHtml(text);
                const regex = new RegExp(`(${query.replace(/[-/\\^$*+?.()|[\]{}]/g,'\\$&')})`, 'gi');
                return escapeHtml(text).replace(regex, '<mark>$1</mark>');
            }

            const searchUrl = $('#searchContainer').data('search-url');


            function performSearch() {
                const query = $('#chatSearch').val().trim();


                if (!query) {
                    $('#searchResults').html('');
                    return;
                }


                $('#searchResults').html('<div class="text-muted">Searching...</div>');

                $.ajax({
                    url: searchUrl,
                    type: 'GET',
                    data: {
                        q: query
                    },
                    success: function(res) {
                        let html = '';

                        if (!res || res.length === 0) {
                            html = '<div class="text-muted">No messages found</div>';
                        } else {
                            res.forEach(msg => {
                                html += `
                            <div class="d-flex align-items-start mb-2">
                                <img src="${msg.sender_avatar}" class="rounded-circle me-2" width="35" height="35">
                                <div>
                                    <div class="small text-muted">${msg.sender_name} • ${msg.time}</div>
                                    <div>${highlightText(msg.body, query)}</div>
                                </div>
                            </div>
                        `;
                            });
                        }

                        $('#searchResults').html(html);
                    },
                    error: function(err) {
                        console.error('AJAX error:', err);
                        $('#searchResults').html(
                            '<div class="text-danger">Error fetching messages</div>');
                    }
                });
            }


            $('#searchBtn').on('click', performSearch);

            $('#chatSearch').on('keypress', function(e) {
                if (e.which === 13) performSearch();
            });


            let typingTimer;
            $('#chatSearch').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(performSearch, 300);
            });

        });
    </script>


    <script>
        let localStream;
        let peers = {};
        let groupId = {{ $group->id }};


        const config = {
            iceServers: [{
                urls: "stun:stun.l.google.com:19302"
            }]
        };


        async function startGroupCall() {
            console.log("Starting group call for group:", groupId);


            try {
                localStream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });
                console.log("Local audio stream ready", localStream);
            } catch (err) {
                alert("Microphone access denied: " + err);
                return;
            }


            Echo.join(`group-call.${groupId}`)
                .here(users => {

                    users.forEach(u => {
                        if (u.id !== {{ auth()->id() }}) {
                            createPeer(u.id, true);
                        }
                    });
                })
                .joining(user => {
                    if (user.id !== {{ auth()->id() }}) {
                        createPeer(user.id, false);
                    }
                })
                .leaving(user => {
                    if (peers[user.id]) {
                        peers[user.id].close();
                        delete peers[user.id];
                        console.log("User left call:", user.id);
                    }
                })
                .listen('GroupCallSignal', async e => {
                    await handleSignal(e.from, e.signal);
                });

            console.log("Joined group call channel:", groupId);
        }


        async function createPeer(userId, isInitiator) {
            const pc = new RTCPeerConnection(config);
            peers[userId] = pc;


            localStream.getTracks().forEach(track => pc.addTrack(track, localStream));


            pc.ontrack = event => {
                const remoteAudio = document.getElementById("remoteAudio");
                if (!remoteAudio.srcObject) {
                    remoteAudio.srcObject = event.streams[0];
                }
            };

            // ICE candidate
            pc.onicecandidate = e => {
                if (e.candidate) {
                    sendSignal(userId, {
                        ice: e.candidate
                    });
                }
            };

            // Initiator creates offer
            if (isInitiator) {
                const offer = await pc.createOffer();
                await pc.setLocalDescription(offer);
                sendSignal(userId, {
                    sdp: pc.localDescription
                });
            }

            return pc;
        }

        // Handle incoming signals
        async function handleSignal(from, signal) {
            let pc = peers[from] || await createPeer(from, false);

            if (signal.sdp) {
                await pc.setRemoteDescription(new RTCSessionDescription(signal.sdp));

                if (signal.sdp.type === "offer") {
                    const answer = await pc.createAnswer();
                    await pc.setLocalDescription(answer);
                    sendSignal(from, {
                        sdp: pc.localDescription
                    });
                }
            }

            if (signal.ice) {
                await pc.addIceCandidate(new RTCIceCandidate(signal.ice));
            }
        }

        // Send signal to Laravel route
        function sendSignal(to, signal) {
            axios.post('/group-call/signal', {
                group_id: groupId,
                to: to,
                signal: signal
            });
        }
    </script>


    <script>
        let localStream;
        let peerConnection;
        const config = {
            iceServers: [{
                urls: 'stun:stun.l.google.com:19302'
            }]
        };

        async function startVideoCall() {

            localStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });
            document.getElementById('localVideo').srcObject = localStream;


            peerConnection = new RTCPeerConnection(config);
            localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));


            peerConnection.ontrack = e => {
                document.getElementById('remoteVideo').srcObject = e.streams[0];
            };


            peerConnection.onicecandidate = e => {
                if (e.candidate) {

                }
            };


            const offer = await peerConnection.createOffer();
            await peerConnection.setLocalDescription(offer);


        }

        document.addEventListener('DOMContentLoaded', () => {
            startVideoCall();
        });
    </script>
@endsection
