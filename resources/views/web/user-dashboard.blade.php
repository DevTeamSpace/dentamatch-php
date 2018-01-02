@extends('web.layouts.dashboard')

@section('content')
<div class="container">
    <div class="dashboarFinalBox mr-t-25">

        <div class="alert alert-info customInfo">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <div class="infoContent">
                <h3>Announcements</h3>
                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.
            </div>
        </div>

        <div class="dashboarFinalInnerBox">
            <div class="row">
                <div class="col-sm-6">
                    <div class="commonBox welcomeLeft">
                        <div class="welcomeLeftheader">
                            <h6>WELCOME</h6>
                            <h4>{{ Session::get('userData.profile.office_name') }}</h4>
                            <a href="/edit-profile" id="dashEdit">Edit profile</a>
                        </div>
                        <div class="welcomeContent">
                            <h4>Monday<br>August 03, 2017</h4>
                            <div class="tHire">
                                <span>02</span>   
                                <p>Today’s Hire</p>
                            </div>

                            <ul class="dashboarFinalList"> 
                                <li>
                                    <div class="dashListImgBlock">
                                        <div class="dashListImg"></div>
                                        <div class="dashListImgContent">
                                            <h6>Elle Nelson</h6>
                                            <p>Dental Assistant</p>  
                                        </div>
                                        <a href="#" class="dashListRightPos">View Job details</a>
                                    </div>
                                    <div class="line"></div>
                                </li>
                                <li>
                                    <div class="dashListImgBlock">
                                        <div class="dashListImg"></div>
                                        <div class="dashListImgContent">
                                            <h6>Elle Nelson</h6>
                                            <p>Dental Assistant</p>  
                                        </div>
                                        <a href="#" class="dashListRightPos">View Job details</a>
                                    </div>
                                    <div class="line"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="commonBox ">
                        <div class="welcomeContent lastMsg">
                            <h4>Latest Messages</h4>
                            <div class="tHire unread">
                                {{ count($latestMessage) }} Unread
                            </div>

                            <ul class="dashboarFinalList"> 
                                @if(!empty($latestMessage))
                                @foreach ($latestMessage as $seeker)
                                    <li>
                                        <div class="dashListImgBlock">
                                            <div class="dashListImg">
                                                <img class="dashListImg" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
                                            </div>
                                            <div class="dashListImgContent msgImg">
                                                <h6>{{ $seeker['name'] }}</h6>
                                                <p>{{ $seeker['jobTitle'] }}</p>  
                                            </div>
                                            <div class="msgNotficaitonBlock">
                                                <span>{{ ($seeker['timestamp']) }}</span>
                                                <div class="msgNotification ">{{ ($seeker['chatCount']) }}</div></div>
                                        </div>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                            <div class="viewAll"><a href="/chat">View all</a></div>

                        </div>
                    </div>
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>Latest Notifications</h4>
                            <div class="tHire unread">
                                {{ $latestNotifications['total'] }} Unread
                            </div>
                            <ul class="dashboarFinalList  latestNotification">
                                @if(!empty($latestNotifications['data']))
                                @foreach($latestNotifications['data'] as $notification)
                                <?php $data = json_decode($notification->notification_data); ?>
                                    <li>
                                        <div class="dashListImgBlock">
                                            <div class="dashListImg"></div>
                                            <div class="dashListImgContent msgImg">
                                                <h6 class="media-heading"><?php echo strip_tags($data->message); ?></p></h6>
                                                <p class="justNow"><span class="icon-clock"></span>{{ $notification->created_at->diffForHumans() }}</p> 
                                            </div>
                                            <div class="onlineDot border-radius">
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="viewAll"><a href="/notification-lists">View all</a></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>What to do next?</h4>
                            <ul class="dashboadPostBlock">
                                <li>
                                    <a class="modalClick btn btn-link mr-r-20" data-toggle="modal" data-target="#jobTemplate"><img src="{{asset('web/images/dentamatch-folder.png')}}" width="45"></a>
                                    Post New Job
                                </li>
                                <li>
                                    <a href="/jobtemplates/create"><img src="{{asset('web/images/dentamatch-plussign.png')}}" width="45"></a>
                                    Create Template
                                </li>
                                <li>
                                    <a href="/job/lists"><img src="{{asset('web/images/dentamatch-foldercurrentJob.png')}}" width="45"></a>
                                    View Current Jobs
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>This week’s view</h4>
                            <ul class="weekList">
                                <li class="weekActive">Aug 3 - Mon</li>
                                <li>Aug 4 -  TUE
                                    <div class="dental">
                                        <p>Dental Assistant</p>
                                        <div class="dentalImg">
                                            <img src="{{asset('web/images/defaultImg.png') }}" width="22" class="img-circle">
                                            <img src="{{asset('web/images/defaultImg.png') }}" width="22" class="img-circle">
                                            <img src="{{asset('web/images/defaultImg.png') }}" width="22" class="img-circle">
                                        </div>
                                    </div>
                                </li>
                                <li>Aug 5 - WED  
                                    <div class="dental">
                                        <p>Dental Hygienist</p>
                                        <div class="dentalImg">
                                            <img src="{{asset('web/images/defaultImg.png') }}" width="22" class="img-circle">
                                            <img src="{{asset('web/images/defaultImg.png') }}" width="22" class="img-circle">

                                            <div class="dentalNumber img-circle">2+</div>

                                        </div>
                                    </div>
                                    <a href="#" class="moreJobs pull-right">4 More Jobs</a>
                                </li>
                                <li>Aug 6 - THU  </li>
                                <li>Aug 7 - FRI </li>
                                <li class="weekHolidday">Aug 8 - SAT </li>
                                <li class="weekHolidday">Aug 9 - SAN </li>
                            </ul>
                            <div class="viewAll">View calendar</div>
                        </div>
                    </div>
                    <div class="commonBox">
                        <div class="welcomeContent lastMsg">
                            <h4>Recently Posted Jobs</h4>
                            @if(count($jobList)>0)
                                @include('web.recuriterJob.dashboard-job-data')
                            @endif
                        </div>
                        <div class="viewAll"><a href="/job/lists">View all</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    @endsection
    @section('js')
    @endsection