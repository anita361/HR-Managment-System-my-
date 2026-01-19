@extends('layouts.chat')

@section('content')
    <div class="page-wrapper">
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

                            {{-- Search --}}
                            <div id="searchContainer" data-search-url="{{ route('search.grpmsg', $group->id) }}"
                                class="d-flex align-items-center mb-2">
                                <input type="text" id="chatSearch" class="form-control form-control-sm me-1"
                                    placeholder="Search messages...">
                                <button type="button" id="searchBtn" class="btn btn-sm btn-light">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>

                            <div id="searchResults" class="mt-2"></div>

                            {{-- Settings --}}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Delete Conversations</a></li>
                                    <li><a class="dropdown-item" href="#">Settings</a></li>
                                </ul>
                            </div>
                        </div>

                        {{-- ================= CHAT BODY ================= --}}
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

                                    // Hide message if deleted for this user
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

                                        {{-- Sender menu --}}
                                        @if ($isAuthSender && !$message->is_deleted)
                                            <div style="position:absolute;top:0;right:0;">
                                                <button onclick="toggleMenu({{ $message->id }})"
                                                    style="background:none;border:none;font-size:18px;cursor:pointer;">⋮</button>
                                                <div id="menu-{{ $message->id }}" class="chat-menu"
                                                    style="display:none;position:absolute;right:0;top:22px;background:#fff;border:1px solid #ddd;border-radius:4px;box-shadow:0 2px 6px rgba(0,0,0,0.15);z-index:100;min-width:140px;">
                                                    <div onclick='startEditMessage({{ $message->id }}, @json($message->body))'
                                                        style="padding:8px 12px; cursor:pointer;">✏️ Edit</div>
                                                    <div onclick="deleteMessage({{ $message->id }}, false)"
                                                        style="padding:8px 12px;cursor:pointer;color:red;">🗑 Delete for me
                                                    </div>
                                                    <div onclick="deleteMessage({{ $message->id }}, true)"
                                                        style="padding:8px 12px;cursor:pointer;color:red;">🗑 Delete for
                                                        everyone</div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Message body --}}
                                        <div class="mt-1 message-body" id="message-body-{{ $message->id }}">
                                            @if ($message->is_deleted)
                                                <em style="color:#888;">🚫 This message was deleted</em>
                                            @else
                                                <span class="message-text">{{ $message->body }}</span>
                                                @if ($message->edited_at)
                                                    <small style="color:#888;"> (edited)</small>
                                                @endif
                                            @endif
                                        </div>

                                        <small
                                            class="text-muted float-end">{{ $message->created_at->format('h:i A') }}</small>
                                    </div>

                                    @if ($isAuthSender)
                                        <img src="{{ $avatarPath }}" class="rounded-circle ms-2"
                                            style="width:40px;height:40px;object-fit:cover;">
                                    @endif
                                </div>
                            @endforeach
                        </div>


                        {{-- ================= INPUT ================= --}}
                        <div class="chat-input p-3 border-top bg-white">
                            <form id="groupMessageForm" method="POST"
                                action="{{ route('group.message.send', $group->id) }}"
                                class="d-flex align-items-center gap-2" enctype="multipart/form-data">
                                @csrf

                                <!-- 📎 Upload Button -->
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

    {{-- ========== UPLOAD MODAL ========== --}}
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

                        <div class="upload-drop-zone" id="drop-zone">
                            <i class="fa fa-cloud-upload fa-2x"></i>
                            <span class="upload-text">Drag & Drop, Paste, or Click to Upload</span>
                        </div>

                        <ul class="upload-list" id="upload-list"></ul>

                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
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
    </style>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert('deefgrgfhgfhfghghgrfhfgrhgfr');

            const dropZone = document.getElementById("drop-zone");
            const fileInput = document.getElementById("file-input");
            const uploadList = document.getElementById("upload-list");
            const form = document.getElementById("js-upload-form");
            const chatBox = document.getElementById("chat-box");
             
            if (!form) {
                console.error('Upload form not found');
                return;
            }

            let selectedFiles = [];

            /* ---------- Display Files ---------- */
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

            /* ---------- Add Files ---------- */
            function addFiles(files) {
                [...files].forEach(file => {
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) selectedFiles.push(file);
                });
                fileInput.value = '';
                displayFiles();
            }

            // Click to select
            dropZone.onclick = () => fileInput.click();
            fileInput.onchange = e => addFiles(e.target.files);

            // Drag & Drop
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

            // Paste files
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
                if (pastedFiles.length > 0) addFiles(pastedFiles);
            });

            /* ---------- Show Message ---------- */
            function showMessage(type, text) {
                document.getElementById('upload-message')?.remove();
                const div = document.createElement('div');
                div.id = 'upload-message';
                div.className = `alert alert-${type} mt-2`;
                div.textContent = text;
                form.prepend(div);
                setTimeout(() => div.remove(), 5000);
            }

            /* ---------- Append Message ---------- */
            function appendMessage(msg) {
                const isAuth = msg.sender_id == {{ auth()->id() }};
                const avatar = msg.sender.avatar ? `/assets/images/${msg.sender.avatar}` :
                    `/assets/images/default-avatar.png`;
                const fileHtml = msg.file ?
                    `<br><a href="/storage/${msg.file}" target="_blank">📎 ${msg.filename || 'Download file'}</a>` :
                    '';

                const html = `
            <div id="message-${msg.id}" class="d-flex mb-3 ${isAuth ? 'justify-content-end' : 'justify-content-start'} align-items-end">
                ${!isAuth ? `<img src="${avatar}" class="rounded-circle me-2" style="width:40px;height:40px;">` : ''}
                <div class="chat-bubble p-2 px-3 rounded shadow-sm ${isAuth ? 'bg-primary text-white' : 'bg-white text-dark'}">
                    <div class="mt-1 message-body">
                        <span class="message-text">${msg.body || ''}</span>
                        ${fileHtml}
                    </div>
                    <small class="text-muted float-end">${new Date(msg.created_at).toLocaleTimeString()}</small>
                </div>
                ${isAuth ? `<img src="${avatar}" class="rounded-circle ms-2" style="width:40px;height:40px;">` : ''}
            </div>`;

                chatBox.insertAdjacentHTML('beforeend', html);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            /* ---------- Submit Upload ---------- */
            form.onsubmit = function(e) {
                e.preventDefault();

                if (selectedFiles.length === 0) {
                    showMessage('warning', 'Please select at least one file.');
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const formData = new FormData(form);
                formData.append('_token', csrfToken);

                selectedFiles.forEach(file => formData.append('file[]', file));

                // Reset progress
                document.querySelectorAll('.progress-bar').forEach(b => b.style.width = '0%');
                document.querySelectorAll('.upload-process').forEach(p => p.textContent = '0%');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/chat/group/upload-files', true);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = function(e) {
                    if (!e.lengthComputable) return;
                    const percent = Math.round((e.loaded / e.total) * 100);
                    document.querySelectorAll('.progress-bar').forEach(b => b.style.width = percent + '%');
                    document.querySelectorAll('.upload-process').forEach(p => p.textContent = percent +
                    '%');
                };

                xhr.onload = function() {
                    let res;
                    try {
                        res = JSON.parse(xhr.responseText);
                    } catch {
                        return showMessage('danger', 'Invalid server response.');
                    }

                    if (xhr.status === 200) {
                        showMessage('success', res.message || 'Files uploaded');

                        if (res.files) {
                            res.files.forEach(f => appendMessage(f));
                        }

                        selectedFiles = [];
                        fileInput.value = '';
                        displayFiles();
                    } else if (xhr.status === 422) {
                        showMessage('warning', Object.values(res.errors).flat().join(', '));
                    } else {
                        showMessage('danger', res.message || 'Upload failed');
                    }
                };

                xhr.onerror = () => showMessage('danger', 'Network error occurred.');
                xhr.send(formData);
            };

            displayFiles();
        });
    </script>


    <script>
        let lastMessageId = 0;

        function fetchNewMessages() {
            fetch("{{ route('group.messages.fetch', $group->id) }}?last_id=" + lastMessageId)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.messages.length > 0) {
                        data.messages.forEach(msg => appendMessage(msg));
                        lastMessageId = data.messages[data.messages.length - 1].id;

                        // Auto scroll
                        const box = document.getElementById('chat-box');
                        box.scrollTop = box.scrollHeight;
                    }
                })
                .catch(err => console.error('Fetch error:', err));
        }

        function appendMessage(msg) {
            const isAuth = msg.sender_id == {{ auth()->id() }};
            const avatar = msg.sender.avatar ?
                `/assets/images/${msg.sender.avatar}` :
                `/assets/images/default-avatar.png`;

            const html = `
        <div id="message-${msg.id}" class="d-flex mb-3 ${isAuth ? 'justify-content-end' : 'justify-content-start'} align-items-end">
            ${!isAuth ? `<img src="${avatar}" class="rounded-circle me-2" style="width:40px;height:40px;">` : ''}
            <div class="chat-bubble p-2 px-3 rounded shadow-sm ${isAuth ? 'bg-primary text-white' : 'bg-white text-dark'}">
                <div class="mt-1 message-body" id="message-body-${msg.id}">
                    <span class="message-text">${msg.body ?? ''}</span>
                </div>
                <small class="text-muted float-end">${new Date(msg.created_at).toLocaleTimeString()}</small>
            </div>
            ${isAuth ? `<img src="${avatar}" class="rounded-circle ms-2" style="width:40px;height:40px;">` : ''}
        </div>`;

            document.getElementById('chat-box').insertAdjacentHTML('beforeend', html);
        }

        // Poll every 2.5 seconds
        setInterval(fetchNewMessages, 2500);
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
                    // Send new message
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

            // Edit
            window.startEditMessage = function(id, body) {
                editMessageId = id;
                textarea.value = body;
                textarea.focus();
                textarea.placeholder = "Editing message...";
                const menu = document.getElementById('menu-' + id);
                if (menu) menu.style.display = 'none';
            };

            // Delete
            window.deleteMessage = function(id, forEveryone) {
                // alert('fdfghghjyhjhjmhjm');
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

            // Toggle menu
            window.toggleMenu = function(id) {
                const menu = document.getElementById('menu-' + id);
                if (!menu) return;
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            };
        });
    </script>

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
@endsection
