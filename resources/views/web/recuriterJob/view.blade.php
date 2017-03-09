@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/style.css')}}">

@endsection
@section('content')
<div class="container padding-container-template">
    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ url('job/lists') }}">Jobs Listing</a></li>
        <li class="active">Jobs Detail</li>
    </ul>
    <!--/breadcrumb-->

    <!--Job Detail-->
    <section class="job-detail-box">
        <div class="template-job-information mr-t-15">
            <div class="template-job-information-left">
                <h4>{{ $job['jobtitle_name'] }}</h4>
            </div>
            <div class="template-job-information-right">
                <span >Posted : 
                 @if($job['days']>0)
                 {{ $job['days'].' day'.(($job['days']>1)?'s':'') }} ago
                 @else
                 Today
                 @endif
             </span>
         </div> 
     </div>    
     <div class="job-type-detail">
        @if($job['job_type']==\App\Models\RecruiterJobs::FULLTIME)
        <span class="drk-green statusBtn mr-r-5">Full Time</span>
        @elseif($job['job_type']==\App\Models\RecruiterJobs::PARTTIME)
        <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
        <span> | 
            @php 
            $dayArr = [];
            ($job['is_monday']==1)?array_push($dayArr,'Monday'):'';
            ($job['is_tuesday']==1)?array_push($dayArr,'Tuesday'):'';
            ($job['is_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
            ($job['is_thursday']==1)?array_push($dayArr,'Thursday'):'';
            ($job['is_friday']==1)?array_push($dayArr,'Friday'):'';
            ($job['is_saturday']==1)?array_push($dayArr,'Saturday'):'';
            ($job['is_sunday']==1)?array_push($dayArr,'Sunday'):'';
            @endphp
            {{ implode(', ',$dayArr) }}
        </span>
        @else
        <span class="bg-ember statusBtn mr-r-5">Temporary</span>
        <span class="dropdown date-drop">
            @php 
            $dates = explode(',',$job['temp_job_dates']);
            @endphp
            <span class=" dropdown-toggle"  data-toggle="dropdown"><span class="day-drop">{{ date('l, d M Y',strtotime($dates[0])) }}</span>
            <span class="caret"></span></span>
            <ul class="dropdown-menu">
                @foreach ($dates as $date)
                <li>{{ date('l, d M Y',strtotime($date)) }}</li>
                @endforeach
            </ul>
        </span>
        @endif
    </div>
    <div class="template-job-information mr-t-30">
        <div class="template-job-information-left j-i-m-l">
            <address>
                <strong>{{ $job['office_name'] }}</strong><br>
                {{ $job['officetype_name'] }}<br>
                {{ $job['address'].' '.$job['zipcode'] }}<br>
                @if($job['job_type']==\App\Models\RecruiterJobs::TEMPORARY)
                <span>Total Job Opening: {{ $job['no_of_jobs'] }}</span>
                @endif
            </address> 

        </div>    
        <div class="template-job-information-right j-i-m-r">
            <div class="job-information-detail">
                <div class="search-seeker">
                    <a href="{{ url('job/search',[$job['id']]) }}" class="btn btn-primary pd-l-30 pd-r-20 btn-block">Search Seekers</a>
                    @if(count($seekerList)==0)
                    <a href="{{ url('job/edit',[$job['id']]) }}" class="btn btn-primary pd-l-30 pd-r-20 mr-t-10 btn-block">Edit</a>
                    @endif
                </div>

            </div> 
        </div>    
    </div> 
    <div class="template-job-information mr-t-15 width-job-info">
        <div class="job-information-detail ">
            <ul class="job-detail">
                <li>
                    <span>Job Description</span>
                    <p >{{ $job['template_desc'] }}</p>
                </li>
                <li>
                    <span>Dental Office Description</span>
                    <p >{{ $job['office_desc'] }}</p>
                </li>
                @foreach($skills as $skill)
                <li>
                    <span>{{ $skill['parent_skill_name'] }}</span>
                    <p>{{ $skill['skill_name'] }}</p>
                </li>
                @endforeach
            </ul>

        </div> 
    </div>
</section>

<!--Job Detail-->



<div class="job-seeker mr-t-40">
    <label >Job Seekers</label>
    <div class="jobseeker-border  mr-t-15 mr-b-25"></div>
    @if(count($seekerList)==0)
    <div class="text-center">
        <img src="{{ asset('web/images/denta_create_profile.png')}}" alt="create profile">
        <div class="mr-b-10">
            <label class="mr-t-15">No jobseekers yet</label>
        </div>
    </div>
    @else
    @foreach($seekerList as $key=>$seekerGroup)
    <div class="jobseeker-statebox mr-t-25">
        <label class="fnt-16 textcolr-38">Jobseeker {{ \App\Models\JobLists::APPLIED_STATUS[$key] }} ({{ count($seekerGroup) }})</label>
        @foreach($seekerGroup as $seeker)
        <div class="media jobCatbox">
            <div class="media-left ">
                <div class="img-holder ">
                    <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
                    @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY)
                    <span id="fav_{{ $seeker['seeker_id'] }}" onclick="markFavourite({{ $seeker['seeker_id'] }});" class="star {{ ($seeker['is_favourite']==null)?'star-empty':'star-fill' }}"></span>
                    @endif
                </div>
            </div>
            <div class="media-body row">
                <div class="col-sm-7 pd-t-10 ">
                    <div >
                        <a href="{{ url('job/seekerdetails/'.$seeker['seeker_id'].'/'.$job['id']) }}" class="media-heading">{{ $seeker['first_name'].' '.$seeker['last_name'] }}</a> 
                        @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY)
                        <span class="mr-l-5 dropdown date_drop">
                            <span class=" dropdown-toggle label label-success" data-toggle="dropdown">{{ ($seeker['avg_rating']!='')?round($seeker['avg_rating'],1):'' }}</span>
                            <ul class="dropdown-menu rating-info">
                                <li><div class="rating_on"> Punctuality</div>
                                    <ul class="rate_me">
                                        <li><span></span></li>
                                        <li class="active"><span></span></li>
                                        <li><span></span></li>
                                        <li><span></span></li>
                                        <li><span></span></li>
                                    </ul>
                                </li>
                                <li><div class="rating_on"> Time management</div>
                                    <ul class="rate_me">
                                        <li><span></span></li>
                                        <li class="active"><span></span></li>
                                        <li><span></span></li>
                                        <li><span></span></li>
                                        <li><span></span></li>
                                    </ul></li>
                                    <li>
                                        <div class="rating_on">  Personal/Professional skill</div>
                                        <ul class="rate_me">
                                            <li><span></span></li>
                                            <li class="active"><span></span></li>
                                            <li><span></span></li>
                                            <li><span></span></li>
                                            <li><span></span></li>
                                        </ul>
                                    </li>
                                </ul>
                            </span>
                            @endif
                        </div>
                        <p class="nopadding">{{ $seeker['jobtitle_name'] }}</p>
                        @if($seeker['job_type']==\App\Models\RecruiterJobs::PARTTIME)
                        <p  class="nopadding">
                            <!--<span class="bg-ember statusBtn mr-r-5">Part time</span>-->
                            @php 
                            $seekerDayArr = [];
                            ($seeker['is_parttime_monday']==1)?array_push($seekerDayArr,'Monday'):'';
                            ($seeker['is_parttime_tuesday']==1)?array_push($seekerDayArr,'Tuesday'):'';
                            ($seeker['is_parttime_wednesday']==1)?array_push($seekerDayArr,'Wednesday'):'';
                            ($seeker['is_parttime_thursday']==1)?array_push($seekerDayArr,'Thursday'):'';
                            ($seeker['is_parttime_friday']==1)?array_push($seekerDayArr,'Friday'):'';
                            ($seeker['is_parttime_saturday']==1)?array_push($seekerDayArr,'Saturday'):'';
                            ($seeker['is_parttime_sunday']==1)?array_push($seekerDayArr,'Sunday'):'';
                            @endphp
                            {{ implode(', ',$seekerDayArr) }}
                        </p>
                        @endif
                        @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY && $seeker['temp_job_dates']!=null)
                        <p  class="nopadding">
                            <!--<span class="bg-ember statusBtn mr-r-5">Temporary</span>-->
                            <span class="dropdown date-drop">
                                @php 
                                $seekerDates = explode(',',$seeker['temp_job_dates']);
                                @endphp
                                <span class="dropdown-toggle"  data-toggle="dropdown">
                                    <span class="day-drop">{{ date('l, d M Y',strtotime($seekerDates[0])) }}</span>
                                    <span class="caret"></span></span>
                                    <ul class="dropdown-menu">
                                        @foreach ($seekerDates as $date)
                                        <li>{{ date('l, d M Y',strtotime($date)) }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            </p>
                            @endif
                        </div>
                        <div class="col-sm-5 pd-t-5 text-right">
                            <p>{{ round($seeker['distance'],2) }} miles away</p>
                            <form action="{{ url('job/updateStatus') }}" method="post">
                                {!! csrf_field() !!}
                                <input type="hidden" name="jobId" value="{{ $job['id'] }}">
                                <input type="hidden" name="seekerId" value="{{ $seeker['seeker_id'] }}">
                                @if($key==\App\Models\JobLists::HIRED)
                                <button type="button" class="modalClick btn btn-primary pd-l-30 pd-r-30 mr-r-5" data-toggle="modal" 
                                data-target="#ShortListMessageBox" data-seekerId="{{ $seeker['seeker_id'] }}">Message</button>
                                @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY && $seeker['avg_rating']==null)
                                <button type="button" class="btn  btn-primary-outline active pd-l-30 pd-r-30 mr-l-5" >Rate seeker</button>
                                @endif
                                @elseif($key==\App\Models\JobLists::SHORTLISTED)
                                <button type="button" class="modalClick btn btn-primary pd-l-30 pd-r-30" data-toggle="modal" 
                                data-target="#ShortListMessageBox" data-seekerId="{{ $seeker['seeker_id'] }}">Message</button>
                                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::HIRED }}" class="btn btn-primary pd-l-30 pd-r-30 ">Hire</button>
                                @elseif($key==\App\Models\JobLists::APPLIED)
                                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::REJECTED }}" class="btn btn-link  mr-r-5">Reject</button>
                                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::SHORTLISTED }}" class="btn btn-primary pd-l-30 pd-r-30 ">Shortlist</button>
                                @elseif($key==\App\Models\JobLists::INVITED)
                                <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invited</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
            @endif
            <div class="mr-t-15 text-center">
                <!--<button type="button" class="view_loadmore btn-block">View More</button>-->
            </div>

        </div>
    </div>



    <div id="ShortListMessageBox" class="modal fade" role="dialog">
        <div class="modal-dialog custom-modal popup-wd522">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invite Jobseeker</h4>
                </div>
                <div class="modal-body ">
                    <form>
                        <div class="form-group custom-select">
                            <textarea id="chatMsg" class="form-control messageBoxTextArea" placeholder="Type your message here"></textarea>
                        </div>
                        <div class="text-right mr-t-20 mr-b-30">
                            <input type="hidden" id="seekerId" value="">
                            <button id="sendChat" type="submit" class="btn btn-primary pd-l-30 pd-r-30">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @endsection

    @section('js')
    <script type="text/javascript">

        var urlFav = "{{ url('recruiter/markFavourite') }}";
        var socketUrl = "{{ url('') }}:3000";
        var userId = "{{ Auth::id() }}";
        var officeName = "{{ $job['office_name'] }}";
    </script>
    <script src="{{ url('') }}:3000/socket.io/socket.io.js"></script>
    <script src ="{{asset('web/scripts/jobdetail.js')}}"></script>
    @endsection
