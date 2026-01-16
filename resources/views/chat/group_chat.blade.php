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
                                    // 🔹 Hide message if deleted for this user
                                    $deletedFor = $message->deleted_for ?? [];
                                    if (!is_array($deletedFor)) {
                                        $deletedFor = json_decode($deletedFor, true) ?? [];
                                    }
                                    if (in_array(auth()->id(), $deletedFor)) {
                                        continue;
                                    }

                                    $avatarPath = $message->sender->avatar
                                        ? URL::to('/assets/images/' . $message->sender->avatar)
                                        : asset('assets/images/default-avatar.png');

                                    $isAuthSender = $message->sender_id == auth()->id();
                                @endphp

                                <div class="d-flex mb-3 {{ $isAuthSender ? 'justify-content-end' : 'justify-content-start' }} align-items-end"
                                    style="position:relative;" id="message-{{ $message->id }}">

                                    {{-- Avatar for others --}}
                                    @unless ($isAuthSender)
                                        <img src="{{ $avatarPath }}" class="rounded-circle me-2"
                                            style="width:40px;height:40px;object-fit:cover;">
                                    @endunless

                                    <div class="chat-bubble p-2 px-3 rounded shadow-sm
                        {{ $isAuthSender ? 'bg-primary text-white' : 'bg-white text-dark' }}"
                                        style="position:relative;">

                                        {{-- Message menu for sender --}}
                                        @if ($isAuthSender && !$message->is_deleted)
                                            <div style="position:absolute;top:0;right:0;">
                                                <button onclick="toggleMenu({{ $message->id }})"
                                                    style="background:none;border:none;font-size:18px;cursor:pointer;">⋮</button>

                                                <div id="menu-{{ $message->id }}" class="chat-menu"
                                                    style="display:none;position:absolute;right:0;top:22px;background:#fff;
                                    border:1px solid #ddd;border-radius:4px;
                                    box-shadow:0 2px 6px rgba(0,0,0,0.15);z-index:100;min-width:140px;">

                                                    <div onclick='startEditMessage({{ $message->id }}, @json($message->body))'
                                                        style="padding:8px 12px; cursor:pointer;">
                                                        ✏️ Edit
                                                    </div>

                                                    <div onclick="deleteMessage({{ $message->id }}, false)"
                                                        style="padding:8px 12px;cursor:pointer;color:red;">
                                                        🗑 Delete for me
                                                    </div>

                                                    <div onclick="deleteMessage({{ $message->id }}, true)"
                                                        style="padding:8px 12px;cursor:pointer;color:red;">
                                                        🗑 Delete for everyone
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-1 message-body" id="message-body-{{ $message->id }}">
                                            @if ($message->is_deleted)
                                                <em style="color:#888;">🚫 This message was deleted</em>
                                            @else
                                                <span class="message-text">{{ $message->body }}</span>
                                            @endif
                                        </div>

                                        <small class="text-muted float-end">
                                            {{ $message->created_at->format('h:i A') }}
                                        </small>
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
                                action="{{ route('group.message.send', $group->id) }}" class="d-flex gap-2">
                                @csrf
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
        let editMessageId = null;
        let textarea = null;


        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('groupMessageForm');
            const textarea = document.getElementById('group_message_id');

            if (!form || !textarea) return;


            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const message = textarea.value.trim();
                if (!message) return;

                const groupId = document.getElementById('group_id').value;

                if (editMessageId) {

                    fetch(`/chat/message/${editMessageId}/update`, {
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
                        });
                }
            });


            window.startEditMessage = function(id, body) {
                editMessageId = id;

                const textarea = document.getElementById('group_message_id');
                if (!textarea) return;

                textarea.value = body;
                textarea.focus();
                textarea.placeholder = "Editing message...";

                const menu = document.getElementById('menu-' + id);
                if (menu) menu.style.display = 'none';
            };


            window.deleteMessage = function(id, forEveryone) {
                if (!confirm(`Delete this message ${forEveryone ? 'for everyone' : 'for yourself'}?`)) return;

                fetch(`/chat/message/${id}/delete`, {
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
                            if (forEveryone) {

                                const el = document.getElementById('message-body-' + id);
                                if (el) {
                                    el.innerHTML =
                                        '<em style="color:#888;">🚫 This message was deleted</em>';
                                }
                            } else {

                                const row = document.getElementById('message-' + id);
                                if (row) row.remove();
                            }
                        } else {
                            alert(data.error || 'Failed to delete message');
                        }
                    });
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
            const $chatWindow = $('#chatWindow'); // Your chat container

            $('#groupMessageForm').on('submit', function(e) {
                e.preventDefault(); // Prevent normal form submit

                const message = $('#group_message_id').val().trim();
                const groupId = $('#group_id').val();
                if (!message) return;

                $.ajax({
                    url: "{{ route('group.message.send', $group->id) }}",
                    type: 'POST',
                    data: {
                        message: message,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(msg) {
                        // Append new message to chat
                        const html = `
                    <div class="chat-message d-flex align-items-start mb-2" id="msg-${msg.id}">
                        <img src="${msg.sender_avatar}" class="rounded-circle me-2" width="35" height="35">
                        <div>
                            <div class="small text-muted">${msg.sender_name} • ${msg.time}</div>
                            <div>${msg.body}</div>
                        </div>
                    </div>
                `;
                        $chatWindow.append(html);

                        // Scroll to bottom
                        $chatWindow.scrollTop($chatWindow[0].scrollHeight);

                        // Clear textarea
                        $('#group_message_id').val('').focus();
                    },
                    error: function(err) {
                        console.error(err);
                        alert('Error sending message');
                    }
                });
            });
        });
    </script> --}}





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
