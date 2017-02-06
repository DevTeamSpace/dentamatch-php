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
                <span >Posted : {{ $job['days'].' day'.(($job['days']>1)?'s':'') }} ago</span>

            </div> 
        </div>    
        <div class="job-type-detail">
            @if($job['job_type']==\App\Models\RecruiterJobs::FULLTIME)
            <span class="bg-ltgreen statusBtn mr-r-5">Full Time</span>
            @elseif($job['job_type']==\App\Models\RecruiterJobs::PARTTIME)
            <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
            <span> | 
                @php 
                $dayArr = [];
                ($job['is_monday']==1)?array_push($dayArr,'Monday'):'';
                ($job['is_tuesday']==1)?array_push($dayArr,'Tuseday'):'';
                ($job['is_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
                ($job['is_thursday']==1)?array_push($dayArr,'Thursday'):'';
                ($job['is_friday']==1)?array_push($dayArr,'Friday'):'';
                ($job['is_saturday']==1)?array_push($dayArr,'Saturday'):'';
                ($job['is_sunday']==1)?array_push($dayArr,Sunday):'';
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
                        <a href="{{ url('search/job',[$job['job_type'],$job['job_title_id']]) }}" class="btn btn-primary pd-l-30 pd-r-30 btn-block">Search Seekers</a>
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
                <div class="jobseeker-statebox">
                    <label class="fnt-16 textcolr-38">Jobseeker {{ \App\Models\JobLists::APPLIED_STATUS[$key] }} ({{ count($seekerGroup) }})</label>
                    @foreach($seekerGroup as $seeker)
                    <div class="media jobCatbox">
                        <div class="media-left ">
                            <div class="img-holder ">
                                <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
                                <span class="star star-fill"></span>
                            </div>
                        </div>
                        <div class="media-body row">
                            <div class="col-sm-8 pd-t-10 ">
                                <div >
                                    <a href="#" class="media-heading">{{ $seeker['first_name'].' '.$seeker['last_name'] }}</a> 
                                    @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY)
                                    <span class="mr-l-5 label label-success">{{ $seeker['avg_rating'] }}</span>
                                    @endif
                                </div>
                                <p class="nopadding">{{ $seeker['jobtitle_name'] }}</p>
                                <p  class="nopadding">Wed, 08 Nov 2016</p>
                            </div>
                            <div class="col-sm-4 pd-t-5 text-right">
                                <p>{{ $seeker['distance'] }} miles away</p>
                                @if($key==\App\Models\JobLists::HIRED)
                                <button type="button" class="btn btn-primary pd-l-30 pd-r-30 mr-r-5">Message</button>
                                @elseif($key==\App\Models\JobLists::SHORTLISTED)
                                <button type="button" class="btn btn-primary pd-l-30 pd-r-30 mr-r-5">Message</button>
                                <button type="button" class="btn btn-primary pd-l-30 pd-r-30 ">Hire</button>
                                @elseif($key==\App\Models\JobLists::APPLIED)
                                <button type="button" class="btn btn-link  mr-r-5">Reject</button>
                                <button type="button" class="btn btn-primary pd-l-30 pd-r-30 ">Shortlist</button>
                                @elseif($key==\App\Models\JobLists::INVITED)
                                <button type="button" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
                                @endif
                                @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY)
                                <button type="submit" class="btn  btn-primary-outline active pd-l-30 pd-r-30 mr-b-5" >Rate seeker</button>
                                @endif
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
            @endforeach
        
        
            <div class="media jobCatbox ">
                <div class="media-left">
                    <div class="img-holder ">

                        <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
                        <span class="star star-empty"></span>
                    </div>
                </div>
                <div class="media-body row">
                    <div class="col-sm-8 pd-t-10 ">
                        <div >
                            <a href="#" class="media-heading">Paula Jackson</a> 


                            <span class="mr-l-5 dropdown date_drop">
                                <span class=" dropdown-toggle label label-success" data-toggle="dropdown">3.8</span>


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
                        </div>
                        <p class="nopadding">Dental Assistant</p>
                        <p  class="nopadding">Wed, 08 Nov  &  Fri, 10 Nov 2016</p>
                    </div>
                    <div class="col-sm-4 pd-t-5 text-right">
                        <p>1.5 miles away</p>
                        <button type="submit" class="btn  btn-primary-outline active pd-l-30 pd-r-30 mr-b-5" data-toggle="modal" data-target="#ratesekeerPopup">Rate seeker</button>
                    </div>
                </div>

            </div>
        
            
            
        <div class="jobseeker-statebox">
            <label class="mr-t-50 fnt-16 textcolr-38">Jobseeker Invited (20)</label>
            <div class="media jobCatbox">
                <div class="media-left ">
                    <div class="img-holder ">

                        <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
                        <span class="star star-empty"></span>
                    </div>
                </div>
                <div class="media-body row">
                    <div class="col-sm-8 pd-t-10 ">
                        <div ><a href="#" class="media-heading">James Fernandis</a> <span class="mr-l-5 label label-success">4.1</span></div>
                        <p class="nopadding">Dental Assistant</p>
                        <p class="nopadding">Wed, 08 Nov 2016</p>
                    </div>
                    <div class="col-sm-4 pd-t-15 text-right">
                        <p>1.2 miles away</p>
                        <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
                    </div>
                </div>
            </div>
            <div class="media jobCatbox">
                <div class="media-left ">
                    <div class="img-holder ">

                        <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
                        <span class="star star-empty"></span>
                    </div>
                </div>
                <div class="media-body row">
                    <div class="col-sm-8 pd-t-10 ">
                        <div ><a href="#" class="media-heading">Anthony Palmer</a> <span class="mr-l-5 label label-warning">3.0</span></div>
                        <p class="nopadding">Dental Assistant</p>
                        <p class="nopadding">Wed, 08 Nov 2016</p>
                    </div>
                    <div class="col-sm-4 pd-t-15 text-right">
                        <p>1.2 miles away</p>
                        <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
                    </div>
                </div>
            </div>
            <div class="media jobCatbox">
                <div class="media-left ">
                    <div class="img-holder ">

                        <img class="media-object img-circle" src="http://placehold.it/66x66" alt="...">
                        <span class="star star-empty"></span>
                    </div>
                </div>
                <div class="media-body row">
                    <div class="col-sm-8 pd-t-10 ">
                        <div ><a href="#" class="media-heading">Mark E. Shaffrey</a> <span class="mr-l-5 label lable-error">3.0</span></div>
                        <p class="nopadding">Dental Assistant</p>
                        <p class="nopadding">Wed, 08 Nov 2016</p>
                    </div>
                    <div class="col-sm-4 pd-t-15 text-right">
                        <p>2 miles away</p>
                        <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invite</button>
                    </div>
                </div>
            </div>

        </div>
        @endif



        <div class="mr-t-15 text-center">
            <button type="button" class="view_loadmore btn-block">View More</button>
        </div>

    </div>
</div>






@endsection

@section('js')
<script type="text/javascript">

    $(function () {
        $('body').on('click', '.pagination a', function (e) {
            e.preventDefault();

            $('#load a').css('color', '#dfecf6');
            $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');
            getArticles(url);
            window.history.pushState("", "", url);
        });

        function getArticles(url) {
            $.ajax({
                url: url
            }).done(function (data) {
                $('#ajaxData').html(data);
            }).fail(function () {

            });
        }
    });
</script>
@endsection
