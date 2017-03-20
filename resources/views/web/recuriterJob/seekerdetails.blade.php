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
            <div class="col-md-6 col-sm-6">
                <h4>{{$seekerDetails['first_name'].' '.$seekerDetails['last_name']}}</h4>
                <h6>{{$seekerDetails['jobtitle_name']}}</h6> 
                <div class="job-type-detail seeker-detail-temp">

                    @if($seekerDetails['is_fulltime'])
                    <span class="statusBtn drk-green text-center statusBtnMargin mr-b-5">Full Time</span>
                    @endif
                    @if($seekerDetails['is_parttime_monday'] || $seekerDetails['is_parttime_tuesday'] || $seekerDetails['is_parttime_wednesday'] || $seekerDetails['is_parttime_thursday'] || $seekerDetails['is_parttime_friday'] || $seekerDetails['is_parttime_saturday'] || $seekerDetails['is_parttime_sunday'])
                    <span class="statusBtn bg-ltgreen text-center statusBtnMargin mr-b-5">Part Time</span>
                    <span> | 
                        @php 
                        $dayArr = [];
                        ($seekerDetails['is_parttime_monday']==1)?array_push($dayArr,'Monday'):'';
                        ($seekerDetails['is_parttime_tuesday']==1)?array_push($dayArr,'Tuesday'):'';
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
            <div class="col-md-3 text-right"><p>{{round($seekerDetails['distance'],0)}} miles away</p>
                {!! csrf_field() !!}
                <input type="hidden" name="jobId" value="{{ $jobId }}">
                <input type="hidden" name="seekerId" value="{{ $seekerDetails['user_id'] }}">
                @if($seekerDetails['applied_status'] == \App\Models\JobLists::INVITED)
                <h6>INVITED</h6>
                @elseif($seekerDetails['applied_status'] == \App\Models\JobLists::APPLIED)
                <h6></h6>
                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::REJECTED }}" class="btn btn-primary pd-l-20 pd-r-20">Reject</button>
                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::SHORTLISTED }}" class="btn btn-primary pd-l-20 pd-r-20">Accept</button>
                @elseif($seekerDetails['applied_status'] == \App\Models\JobLists::SHORTLISTED)
                <h6>SHORTLISTED</h6>
                <a href="{{ url('chat') }}" class="btn btn-primary pd-l-30 pd-r-30">Message</a>
                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::HIRED }}" class="btn btn-primary pd-l-20 pd-r-20">Hire</button>
                @elseif($seekerDetails['applied_status'] == \App\Models\JobLists::HIRED)
                <h6>HIRED</h6>
                <a href="{{ url('chat') }}" class="btn btn-primary pd-l-30 pd-r-30">Message</a>
                @else
                <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::INVITED }}" class="btn btn-primary pd-l-30 pd-r-30">Invite</button>    
                @endif    
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
                    <div class="col-sm-4 exprience">
                        <dl>
                            <dt>
                                <div class="expTitle">{{$experience['jobtitle_name']}} 
                                    <span>({{(round($experience['months_of_expereince']/12,0)!=0?round($experience['months_of_expereince']/12,0)." year":"")." ".(round($experience['months_of_expereince']%12,0)!=0?round($experience['months_of_expereince']%12,0)." month":"")}})</span>
                                </div>
                                {{$experience['office_name']}}
                            </dt>
                            <dd>{{$experience['office_address']}}</dd>
                            <dd>{{$experience['city']}}</dd> 
                        </dl>
                    </div>
                    @if(!empty($experience['reference1_name']))
                    <div class="col-sm-4 exprience">
                        <dl>
                            <dt>
                                <div class="expTitle">Reference 1</div>
                                {{$experience['reference1_name']}}
                            </dt>
                            <dd>{{$experience['reference1_mobile']}}</dd>
                            <dd>{{$experience['reference1_email']}}</dd>                 
                        </dl>
                    </div>
                    @endif
                    @if(!empty($experience['reference2_name']))
                    <div class="col-sm-4 exprience">
                        <dl>
                            <dt>
                                <div class="expTitle">Reference 2</div>
                                {{$experience['reference2_name']}}
                            </dt>
                            <dd>{{$experience['reference2_mobile']}}</dd>
                            <dd>{{$experience['reference2_email']}}</dd>                 
                        </dl>
                    </div>
                    @endif
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
            @if($seekerDetails['applied_status'] == \App\Models\JobLists::HIRED)
            @if(!empty($seekerDetails['certificate']))
            @foreach($seekerDetails['certificate'] as $certificate)
            <div class="searchResultHeading pd-t-20 smallSquare">
                <h5>{{$certificate['certificate_name']}}</h5>
                <p>
                    <a href="javascript:void(0)" >
                        <img data-toggle="modal" data-target="#certificateModal" class="img-rounded thumb-certificate" data-image="{{ $certificate['image_path'] }}" src="{{ url('image/66/66/?src='.$certificate['image_path']) }}">
                    </a>
                    @if(!empty($certificate['validity_date']))
                    Valid Till: <span>{{date('d M Y',strtotime($certificate['validity_date']))}}</span></p>
                    @else
                    Valid Till: N/A
                    @endif
                </div>
                @endforeach
                @endif
             @endif
            </div>  
        </div>
    </div>  
</div>

<!--  Modal content for the mixer image example -->
<div id="certificateModal" class="modal fade" role="dialog">
    <div class="modal-dialog custom-modal popup-wd522">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Certificate</h4>
      </div>
      <div class="modal-body">
          <img id="certificateModalImg" class="img-rounded img-responsive" src="">

      </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal mixer image -->

<script>
    $('.thumb-certificate').click(function(){
        var imgUrl = "{{ url('image/550/500/?src=') }}";
        $('#certificateModal').modal({show:true});
        $('#certificateModalImg').attr('src', imgUrl+$(this).data('image'));
        return false;
    });
    
</script>
@endsection

@section('js')
@endsection
