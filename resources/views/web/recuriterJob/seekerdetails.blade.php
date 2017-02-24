@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/style.css')}}">
@endsection
@section('content')

<div class="container padding-container-template">
    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ url('job/lists') }}">Jobs Listing</a></li>
        <li><a href="{{ url('job/details/'.$jobId) }}">Jobs Detail</a></li>
        <li><a href="{{ url('job/search/'.$jobId) }}">Search Preference</a></li>
        <li class="active">{{$seekerDetails['first_name'].' '.$seekerDetails['last_name']." Profile"}}</li>
    </ul>
    <!--/breadcrumb-->

    <div class="commonBox">   
        <div class="row resultHeading">
            <div class="col-md-2 col-sm-2 resultImage">
                <img src="{{ url('image/120/120/?src=' .$seekerDetails['profile_pic']) }}" class="img-circle">
            </div> 
            <div class="col-md-7 col-sm-6">
                <h4>{{$seekerDetails['first_name'].' '.$seekerDetails['last_name']}}</h4>
                <h6>{{$seekerDetails['jobtitle_name']}}</h6> 
                <div class="job-type-detail">
                    
                    @if($seekerDetails['is_fulltime'])
                    <span class="statusBtn drk-green text-center statusBtnMargin">Full Time</span>
                    @endif
                    @if($seekerDetails['is_parttime_monday'] || $seekerDetails['is_parttime_tuesday'] || $seekerDetails['is_parttime_wednesday'] || $seekerDetails['is_parttime_thursday'] || $seekerDetails['is_parttime_friday'] || $seekerDetails['is_parttime_saturday'] || $seekerDetails['is_parttime_sunday'])
                    <span class="statusBtn bg-ltgreen text-center statusBtnMargin">Part Time</span>
                    <span> | 
                        @php 
                        $dayArr = [];
                        ($seekerDetails['is_parttime_monday']==1)?array_push($dayArr,'Monday'):'';
                        ($seekerDetails['is_parttime_tuesday']==1)?array_push($dayArr,'Tuseday'):'';
                        ($seekerDetails['is_parttime_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
                        ($seekerDetails['is_parttime_thursday']==1)?array_push($dayArr,'Thursday'):'';
                        ($seekerDetails['is_parttime_friday']==1)?array_push($dayArr,'Friday'):'';
                        ($seekerDetails['is_parttime_saturday']==1)?array_push($dayArr,'Saturday'):'';
                        ($seekerDetails['is_parttime_sunday']==1)?array_push($dayArr,'Sunday'):'';
                        @endphp
                        {{ implode(', ',$dayArr) }}
                    </span>
                    @endif
                    @if($seekerDetails['temp_job_dates'])
                    <span class="bg-ember statusBtn mr-r-5">Temporary</span>
                    <span class="dropdown date-drop">
                        @php 
                        $dates = explode(' | ',$seekerDetails['temp_job_dates']);
                        @endphp
                        <span class=" dropdown-toggle"  data-toggle="dropdown">
                            <span class="day-drop">{{ date('l, d M Y',strtotime($dates[0])) }}</span>
                            <span class="caret"></span>
                        </span>
                        <ul class="dropdown-menu">
                          @foreach ($dates as $date)
                          <li>{{ date('l, d M Y',strtotime($date)) }}</li>
                          @endforeach
                      </ul>
                  </span>
                  @endif
              </div>
          </div>
          <form action="{{ url('job/updateStatus') }}" method="post">
            <div class="col-md-3 text-right"><p>{{round($seekerDetails['distance'])}} miles away
                {!! csrf_field() !!}
                <input type="hidden" name="jobId" value="{{ $jobId }}">
                <input type="hidden" name="seekerId" value="{{ $seekerDetails['user_id'] }}">
                @if($seekerDetails['applied_status'] == \App\Models\JobLists::INVITED)
                <h6>INVITED</h6>
                @elseif($seekerDetails['applied_status'] == \App\Models\JobLists::APPLIED)
                <h6></h6>
                <button type="submit" class="btn btn-primary pd-l-20 pd-r-20">Reject</button>
                <button type="submit" class="btn btn-primary pd-l-20 pd-r-20">Accept</button>
                @elseif($seekerDetails['applied_status'] == \App\Models\JobLists::SHORTLISTED)
                <h6>SHORTLISTED</h6>
                <button type="submit" class="btn btn-primary pd-l-20 pd-r-20">Message</button>
                <button type="submit" class="btn btn-primary pd-l-20 pd-r-20">Hire</button>
                @elseif($seekerDetails['applied_status'] == \App\Models\JobLists::HIRED)
                <h6>HIRED</h6>
                <button type="submit" class="btn btn-primary pd-l-30 pd-r-30">Message</button>
                @else
                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::INVITED }}" class="btn btn-primary pd-l-30 pd-r-30">Invite</button>    
                @endif 
            </p>   
        </div>
    </form>
</div>

<div class="pd-t-60">
    <div class="leftCircle">
        <div class="searchResultHeading">
            <h5>ABOUT ME</h5>
            <p>{{$seekerDetails['about_me']}}</p>
        </div>

        <div class="searchResultHeading pd-t-20">
            <h5>LOCATION</h5>
            <p>{{$seekerDetails['preferred_job_location']}}</p>
        </div>

        <div class="searchResultHeading pd-t-20">
            <h5>EXPERIENCE</h5>
            @if(!empty($seekerDetails['experience']))
            @foreach($seekerDetails['experience'] as $experience)
            <div class="row">   
                <div class="col-sm-6 exprience">
                    <dl>
                        <dt>
                            <div class="expTitle">{{$experience['jobtitle_name']}} 
                                <span>({{(round($experience['months_of_expereince']/12,0)!=0?round($experience['months_of_expereince']/12,0)." year":"")." ".(round($experience['months_of_expereince']%12,0)!=0?round($experience['months_of_expereince']%12,0)." month":"")}})</span>
                            </div>
                            {{$experience['reference1_name']}}
                        </dt>
                        <dd>{{$experience['reference1_mobile']}}</dd>
                        <dd>{{$experience['reference1_email']}}</dd> 
                    </dl>
                </div>
                <div class="col-sm-6 exprience">
                    <dl>
                        <dt>
                            <div class="expTitle">Reference</div>
                            {{$experience['reference2_name']}}
                        </dt>
                        <dd>{{$experience['reference2_mobile']}}</dd>
                        <dd>{{$experience['reference2_email']}}</dd>                 
                    </dl>
                </div>
            </div>
            @endforeach
            @endif
        </div>    


        <div class="searchResultHeading pd-t-20">
            <h5>DENTAL SCHOOL / TRAINING / GRADUATIONS</h5>
            @if(!empty($seekerDetails['schoolings']))
            @foreach($seekerDetails['schoolings'] as $schoolings)
            <div class="pd-t-10 keySkills">
                <b>{{$schoolings['school_title']." (".$schoolings['year_of_graduation'].")"}}</b>
                <p>{{$schoolings['school_name']}}</p>
            </div>
            @endforeach
            @endif
        </div>

        <div class="searchResultHeading pd-t-20">
            <h5>KEY SKILLS</h5>
        </div>

        @if(!empty($seekerDetails['skills']))
        @foreach($seekerDetails['skills'] as $skills)
        <div class="pd-t-10 keySkills">
            <b>{{$skills['skill_title']}}</b>
            <p>{{$skills['skill_name']}}</p>
        </div>
        @endforeach
        @endif
        
        <div class="searchResultHeading pd-t-20">
            <h5>AFFILIATIONS</h5>
            <P>{{$seekerDetails['affiliations']}}</P>
        </div>

        @if(!empty($seekerDetails['certificate']))
        @foreach($seekerDetails['certificate'] as $certificate)
        <div class="searchResultHeading pd-t-20">
            <h5>{{$certificate['certificate_name']}}</h5>
            <P>
                <img src="{{ url('image/66/66/?src=' .$certificate['image_path']) }}">
                Valid Till: {{date('d M Y',strtotime($certificate['validity_date']))}}</P>
            </div>
            @endforeach
            @endif
        </div>  
    </div>
</div>  
</div>
@endsection

@section('js')
@endsection
