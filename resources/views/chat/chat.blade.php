@extends('layouts.chat')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Chat Main Row -->
        <div class="chat-main-row">

            <!-- Chat Main Wrapper -->
            <div class="chat-main-wrapper">

                <!-- Chats View -->
                <div class="col-lg-9 message-view task-view">
                    <div class="chat-window">
                        <div class="fixed-header">
                            <div class="navbar">
                                <div class="user-details mr-auto">
                                    <div class="float-left user-img">
                                        <a class="avatar" href="{{ route('profile_user') }}" title="Mike Litorus">
                                            <img src="assets/img/profiles/avatar-05.jpg" alt=""
                                                class="rounded-circle">
                                            <span class="status online"></span>
                                        </a>
                                    </div>
                                    <div class="user-info float-left">
                                        <a href="{{ route('profile_user') }}" title="Mike Litorus"><span>Mike Litorus</span>
                                            <i class="typing-text">Typing...</i></a>
                                        <span class="last-seen">Last seen today at 7:50 AM</span>
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
                                            <div class="chat chat-right">
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>Hello. What can I do for you?</p>
                                                            <span class="chat-time">8:30 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-line">
                                                <span class="chat-date">October 8th, 2018</span>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt="" src="assets/img/profiles/avatar-05.jpg">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>I'm just looking around.</p>
                                                            <p>Will you tell me something about yourself? </p>
                                                            <span class="chat-time">8:35 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>Are you there? That time!</p>
                                                            <span class="chat-time">8:40 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-right">
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>Where?</p>
                                                            <span class="chat-time">8:35 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>OK, my name is Limingqiang. I like singing, playing
                                                                basketballand so on.</p>
                                                            <span class="chat-time">8:42 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt="" src="assets/img/profiles/avatar-05.jpg">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>You wait for notice.</p>
                                                            <span class="chat-time">8:30 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>Consectetuorem ipsum dolor sit?</p>
                                                            <span class="chat-time">8:50 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>OK?</p>
                                                            <span class="chat-time">8:55 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble">
                                                        <div class="chat-content img-content">
                                                            <div class="chat-img-group clearfix">
                                                                <p>Uploaded 3 Images</p>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">placeholder.jpg</div>
                                                                        <div class="chat-file-desc">842 KB</div>
                                                                    </div>
                                                                </a>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">842 KB</div>
                                                                    </div>
                                                                </a>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">placeholder.jpg</div>
                                                                        <div class="chat-file-desc">842 KB</div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <span class="chat-time">9:00 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-right">
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>OK!</p>
                                                            <span class="chat-time">9:00 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt="" src="assets/img/profiles/avatar-05.jpg">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>Uploaded 3 files</p>
                                                            <ul class="attach-list">
                                                                <li><i class="fa fa-file"></i> <a
                                                                        href="#">example.avi</a></li>
                                                                <li><i class="fa fa-file"></i> <a
                                                                        href="#">activity.psd</a></li>
                                                                <li><i class="fa fa-file"></i> <a
                                                                        href="#">example.psd</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>Consectetuorem ipsum dolor sit?</p>
                                                            <span class="chat-time">8:50 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>OK?</p>
                                                            <span class="chat-time">8:55 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-right">
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content img-content">
                                                            <div class="chat-img-group clearfix">
                                                                <p>Uploaded 6 Images</p>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">placeholder.jpg</div>
                                                                        <div class="chat-file-desc">842 KB</div>
                                                                    </div>
                                                                </a>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">842 KB</div>
                                                                    </div>
                                                                </a>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">placeholder.jpg</div>
                                                                        <div class="chat-file-desc">842 KB</div>
                                                                    </div>
                                                                </a>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">placeholder.jpg</div>
                                                                        <div class="chat-file-desc">842 KB</div>
                                                                    </div>
                                                                </a>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">placeholder.jpg</div>
                                                                        <div class="chat-file-desc">842 KB</div>
                                                                    </div>
                                                                </a>
                                                                <a class="chat-img-attach" href="#">
                                                                    <img width="182" height="137" alt=""
                                                                        src="assets/img/placeholder.jpg">
                                                                    <div class="chat-placeholder">
                                                                        <div class="chat-img-name">placeholder.jpg</div>
                                                                        <div class="chat-file-desc">842 KB</div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <span class="chat-time">9:00 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt="" src="assets/img/profiles/avatar-05.jpg">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <ul class="attach-list">
                                                                <li class="pdf-file"><i class="fa fa-file-pdf-o"></i> <a
                                                                        href="#">Document_2016.pdf</a></li>
                                                            </ul>
                                                            <span class="chat-time">9:00 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-right">
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <ul class="attach-list">
                                                                <li class="pdf-file"><i class="fa fa-file-pdf-o"></i> <a
                                                                        href="#">Document_2016.pdf</a></li>
                                                            </ul>
                                                            <span class="chat-time">9:00 am</span>
                                                        </div>
                                                        <div class="chat-action-btns">
                                                            <ul>
                                                                <li><a href="#" class="share-msg" title="Share"><i
                                                                            class="fa fa-share-alt"></i></a></li>
                                                                <li><a href="#" class="edit-msg"><i
                                                                            class="fa fa-pencil"></i></a></li>
                                                                <li><a href="#" class="del-msg"><i
                                                                            class="fa fa-trash-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat chat-left">
                                                <div class="chat-avatar">
                                                    <a href="{{ route('profile_user') }}" class="avatar">
                                                        <img alt="" src="assets/img/profiles/avatar-05.jpg">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p>Typing ...</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-footer">
                            <div class="message-bar">
                                <div class="message-inner">
                                    <a class="link attach-icon" href="#" data-toggle="modal"
                                        data-target="#drag_files"><img src="assets/img/attachment.png"
                                            alt=""></a>
                                    <div class="message-area">
                                        <div class="input-group">
                                            <textarea class="form-control" placeholder="Type message..."></textarea>
                                            <span class="input-group-append">
                                                <button class="btn btn-custom" type="button"><i
                                                        class="fa fa-send"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Chats View -->

                <!-- Chat Right Sidebar -->
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
                                                        <img alt="" src="assets/img/profiles/avatar-02.jpg">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">You</span> <span
                                                                class="chat-time">8:35 am</span>
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
                                                        <img alt="" src="assets/img/profiles/avatar-02.jpg">
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
                                                        <img alt="" src="assets/img/profiles/avatar-05.jpg">
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
                                                        <img alt="" src="assets/img/profiles/avatar-02.jpg">
                                                    </a>
                                                </div>
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <span class="task-chat-user">You</span> <span
                                                                class="chat-time">8:35 am</span>
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
                                                        <img src="assets/img/profiles/avatar-02.jpg" alt="">
                                                        <span class="change-img">Change Image</span>
                                                    </div>
                                                    <h3 class="user-name m-t-10 mb-0">{{ Session::get('name') }}</h3>
                                                    <small class="text-muted">{{ Session::get('position') }}</small>
                                                    <a href="javascript:void(0);" class="btn btn-primary edit-btn"><i
                                                            class="fa fa-pencil"></i></a>
                                                </div>
                                                <div class="chat-profile-info">
                                                    <ul class="user-det-list">
                                                        <li>
                                                            <span>Username:</span>
                                                            <span class="float-right text-muted">johndoe</span>
                                                        </li>
                                                        <li>
                                                            <span>DOB:</span>
                                                            <span class="float-right text-muted">24 July</span>
                                                        </li>
                                                        <li>
                                                            <span>Email:</span>
                                                            <span class="float-right text-muted">johndoe@example.com</span>
                                                        </li>
                                                        <li>
                                                            <span>Phone:</span>
                                                            <span class="float-right text-muted">9876543210</span>
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
                <!-- /Chat Right Sidebar -->

            </div>
            <!-- /Chat Main Wrapper -->

        </div>
        <!-- /Chat Main Row -->

        <!-- Drogfiles Modal -->
        <div id="drag_files" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Drag and drop files upload</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="js-upload-form">
                            <div class="upload-drop-zone" id="drop-zone">
                                <i class="fa fa-cloud-upload fa-2x"></i> <span class="upload-text">Just drag and drop
                                    files here</span>
                            </div>
                            <h4>Uploading</h4>
                            <ul class="upload-list">
                                <li class="file-list">
                                    <div class="upload-wrap">
                                        <div class="file-name">
                                            <i class="fa fa-photo"></i>
                                            photo.png
                                        </div>
                                        <div class="file-size">1.07 gb</div>
                                        <button type="button" class="file-close">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </div>
                                    <div class="progress progress-xs progress-striped">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                                    </div>
                                    <div class="upload-process">37% done</div>
                                </li>
                                <li class="file-list">
                                    <div class="upload-wrap">
                                        <div class="file-name">
                                            <i class="fa fa-file"></i>
                                            task.doc
                                        </div>
                                        <div class="file-size">5.8 kb</div>
                                        <button type="button" class="file-close">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </div>
                                    <div class="progress progress-xs progress-striped">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                                    </div>
                                    <div class="upload-process">37% done</div>
                                </li>
                                <li class="file-list">
                                    <div class="upload-wrap">
                                        <div class="file-name">
                                            <i class="fa fa-photo"></i>
                                            dashboard.png
                                        </div>
                                        <div class="file-size">2.1 mb</div>
                                        <button type="button" class="file-close">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </div>
                                    <div class="progress progress-xs progress-striped">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                                    </div>
                                    <div class="upload-process">Completed</div>
                                </li>
                            </ul>
                        </form>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Drogfiles Modal -->

        <!-- Add Group Modal -->
        <div id="add_group" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create a group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Groups are where your team communicates. They’re best when organized around a topic — #leads, for
                            example.</p>
                        <form>
                            <div class="form-group">
                                <label>Group Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label>Send invites to: <span class="text-muted-light">(optional)</span></label>
                                <input class="form-control" type="text">
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Group Modal -->


        <!-- Add Chat User Modal -->
        <div id="add_chat_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Direct Chat</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- Add User Form -->
                        <form id="addUserForm" class="mb-3">
                            @csrf
                            <div class="form-row">
                                <div class="col">
                                    <input name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="col">
                                    <input name="email" type="email" class="form-control"
                                        placeholder="Email (optional)">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-success" type="submit">Add</button>
                                </div>
                            </div>
                        </form>

                        <h5>Users</h5>
                        <ul class="chat-user-list list-group" id="chatUserList">
                            @foreach ($users as $chatUser)
                                <li class="list-group-item" id="chat-user-{{ $chatUser->id }}">
                                    <a href="#" class="chat-user d-flex align-items-center"
                                        data-id="{{ $chatUser->id }}">
                                        <span class="avatar align-self-center mr-2">
                                            <img src="{{ asset('assets/img/profiles/avatar-01.jpg') }}" alt=""
                                                style="width:40px;height:40px;border-radius:50%;">
                                        </span>
                                        <div class="media-body">
                                            <div class="user-name">{{ $chatUser->name }}</div>
                                            <span class="designation">{{ $chatUser->role_name ?? 'Employee' }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>


                        <div class="chat-box mt-3" style="display:none;">
                            <div id="chatHeader" class="mb-2"><strong id="chatWith"></strong></div>

                            <div id="messages"
                                style="height:250px; overflow-y:scroll; border:1px solid #ccc; padding:10px;"></div>

                            <form id="chatForm" class="mt-2">
                                @csrf
                                <input type="hidden" name="receiver_id" id="receiver_id" value="">
                                <input type="text" name="message" id="message" class="form-control"
                                    placeholder="Type a message..." autocomplete="off">
                                <button type="submit" class="btn btn-primary mt-2">Send</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Chat User Modal -->

        <!-- Share Files Modal -->
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
        <!-- /Share Files Modal -->

    </div>
    <!-- /Page Wrapper -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.chat-user', function(e) {
            e.preventDefault();
            receiverId = $(this).data('id');
            $('#receiver_id').val(receiverId);
            $('#messages').html('');
            showChatBox();

            // Load messages
            $.get('/chat/messages/' + receiverId, function(data) {
                data.forEach(function(msg) {
                    const who = (msg.sender_id === MY_ID) ? "You" : $(e.target).closest(
                        '.list-group-item').find('.user-name').text() || "Them";
                    $('#messages').append('<div><strong>' + (msg.sender_id === MY_ID ? "You" :
                            "Them") + ': </strong>' + $('<div>').text(msg.message).html() +
                        '</div>');
                });
                $('#messages').scrollTop($('#messages')[0].scrollHeight);
            });
        });

        // Submit sending message
        $('#chatForm').submit(function(e) {
            e.preventDefault();
            if (!receiverId) {
                alert('Select a user first!');
                return;
            }

            const payload = $(this).serialize();

            $.post('{{ route('chat.send') }}', payload, function(data) {
                if (data.status === 'success') {
                    $('#messages').append('<div><strong>You: </strong>' + $('<div>').text(data.message
                        .message).html() + '</div>');
                    $('#message').val('');
                    $('#messages').scrollTop($('#messages')[0].scrollHeight);
                } else {
                    alert('Message failed to send');
                }
            }).fail(function(xhr) {
                alert('Error sending message: ' + (xhr.responseJSON?.message || xhr.statusText));
            });
        });

        // Add new chat user via AJAX
        $('#addUserForm').submit(function(e) {
            e.preventDefault();
            const $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true);

            $.ajax({
                url: '{{ route('chat.user.add') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status === 'success') {
                        const user = res.user;
                        const li = `<li class="list-group-item" id="chat-user-${user.id}">
                        <a href="#" class="chat-user d-flex align-items-center" data-id="${user.id}">
                            <span class="avatar align-self-center mr-2">
                                <img src="{{ asset('assets/img/profiles/avatar-01.jpg') }}" alt="" style="width:40px;height:40px;border-radius:50%;">
                            </span>
                            <div class="media-body">
                                <div class="user-name">${user.name}</div>
                                <span class="designation">${user.role_name ?? 'Employee'}</span>
                            </div>
                        </a>
                    </li>`;
                        $('#chatUserList').prepend(li); // add to top
                        $('#addUserForm')[0].reset();

                        // Auto-click the new user to open chat
                        $('#chat-user-' + user.id).find('.chat-user').trigger('click');
                    } else {
                        alert('Failed to add user');
                    }
                },
                error: function(xhr) {
                    let err = 'Error';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        err = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        err = xhr.responseJSON.message;
                    }
                    alert(err);
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        });
    </script>
@endsection
