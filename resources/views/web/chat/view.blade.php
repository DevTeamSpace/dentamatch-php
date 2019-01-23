@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/plugins/custom-scroller/css/mCustomScrollbar.min.css')}}">
@endsection
@section('content')
<div class="container padding-container-template">
    <div class="row">
        <div class="col-md-4 col-sm-5 col-xs-4">
            <div class="chat-list chat-box">
                <div class="chat-list-title">All Messages</div>
                <div class="content mCustomScrollbar light" data-mcs-theme="minimal-dark">
                    <ul class="list-group nav-stacked" role="tablist">
                        @if(count($seekerList))
                        @foreach ($seekerList as $seeker)
                        <li id="li_{{ $seeker['seekerId'] }}" class="nav-item leftSeekerPanelRow" data-loaded="0" data-user="{{ $seeker['seekerId'] }}">
                            <a data-toggle="tab" href="#pr_{{ $seeker['seekerId'] }}" role="tab">
                                <div class="media ">
                                    <div class="media-left">
                                        <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
                                    </div>
                                    <div class="media-body">
                                        <div class="user-chat-details">
                                            <div class="media-heading">{{ $seeker['name'] }}</div>
                                            <p>{{ $seeker['message'] }}</p>
                                        </div>
                                        <div class="chat-timestamp">
                                            <span class="time">{{ ($seeker['timestamp']) }}</span>
                                            <span class="badge hide">0</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                        @else
                        <li class="mr-l-20 mr-t-10"><p>No messages yet</p></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-7 col-xs-8 mob-chat-dialog">
            <!-- Tab panes -->
            <div class="tab-content">
                @foreach ($seekerList as $seeker)
                <div class="tab-pane " id="pr_{{ $seeker['seekerId'] }}" data-page="0" role="tabpanel">
                    <div class="users-chat-outerbox chat-box">
                        <div class="user-chat-info-action">
                            <div class="media ">
                                <div class="media-left">
                                    <img class="media-object img-circle cir-32" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
                                </div>
                                <div class="media-body">
                                    <div class="user-chat-details">
                                        <div class="media-heading">{{ $seeker['name'] }}</div>
                                        <p>{{ $seeker['jobTitle'] }}</p>
                                    </div>
                                </div>
                                <div class="chat-action">
                                    <div class="dropdown icon-upload-ctn1">
                                        <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                                        <ul class="actions text-left dropdown-menu {{ ($seeker['recruiterBlock']==1)?'action-disabled':'' }}">
                                            <li>
                                                <button class="btn btn-link-noline btn-block " {{ ($seeker['recruiterBlock']==1)?'disabled="disabled"':'' }}> Block</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="chat-scroller{{ $seeker['seekerId'] }}" class="chatScroller content mCustomScrollbar light" data-mcs-theme="minimal-dark">
                            <div id="user-chat-content_{{ $seeker['seekerId'] }}" class="user-chat-content">
                                
                            </div>
                        </div>
                        <div class="unblock-con text-center " style="display:{{ ($seeker['recruiterBlock']==1)?'block':'none' }};">
                            <p>You have blocked this jobseeker</p>
                            <button  class="btn-link chat-unblock" data-seekerid="{{ $seeker['seekerId'] }}">UNBLOCK</button>
                        </div>
                        <div class="message-textarea">
                            <div class="input-group msgDiv">
                                <textarea {{ ($seeker['recruiterBlock']==1)?'disabled="disabled"':'' }} class="comment type-text" placeholder="Type your message here"></textarea>
                                <span class="input-group-btn">
                                    <button {{ ($seeker['recruiterBlock']==1)?'disabled="disabled"':'' }} class="chatSend btn btn-primary {{ ($seeker['recruiterBlock']==1)?'action-disabled':'' }}" type="button" data-seekerid="{{ $seeker['seekerId'] }}">Send</button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div> 
@include('web.chat.chat_modal')
@endsection

@section('js')
<script src="{{ asset('web/scripts/optionDropDown.js')}}"></script>
<script src="{{ asset('web/scripts/custom.js')}}"></script>
<script src="{{ asset('web/plugins/custom-scroller/js/mCustomScrollbar.js')}}"></script>
@endsection

