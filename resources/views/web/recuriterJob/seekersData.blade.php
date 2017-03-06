<!--Seeker listing-->
@foreach ($seekersList['paginate'] as $seeker)
<!--search preference list-->
<form action="{{ url('job/updateStatus') }}" method="post">
{!! csrf_field() !!}
<input type="hidden" name="jobId" value="{{ $jobDetails['id'] }}">
<input type="hidden" name="seekerId" value="{{ $seeker['user_id'] }}">
<div class="media jobCatbox">
    <div class="media-left ">
        <div class="img-holder ">
            <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
            @if($jobDetails['job_type']==App\Models\RecruiterJobs::TEMPORARY)
            <span id="fav_{{ $seeker['user_id'] }}" onclick="markFavourite({{ $seeker['user_id'] }});" class="star {{ ($seeker['is_favourite']==null)?'star-empty':'star-fill' }}"></span>
            @endif
        </div>
    </div>
    
    <div class="media-body ">
        <div class="template-job-information mr-t-15">
          <div class="template-job-information-left">
            <h4 class="pull-left"><a href="{{ url('job/seekerdetails/'.$seeker['user_id'].'/'.$jobDetails['id']) }}">{{$seeker['first_name'].' '.$seeker['last_name']}}</a></h4>
            @if($jobDetails['job_type']==App\Models\RecruiterJobs::TEMPORARY)
            <span class="mr-l-5 dropdown date_drop">
                @if(round($seeker['avg_rating'],0) > 3)
                @php $avgrateClass = 'bg-green' @endphp
                @elseif(round($seeker['avg_rating'],0) == 3)
                @php $avgrateClass = 'bg-ember' @endphp
                @elseif(round($seeker['avg_rating'],0) < 3)
                @php $avgrateClass = 'bg-red'  @endphp
                @endif
                
                @if(round($seeker['punctuality'],0) > 3)
                @php $puncClass = 'bg-green' @endphp
                @elseif(round($seeker['punctuality'],0) == 3)
                @php $puncClass = 'bg-ember' @endphp
                @elseif(round($seeker['punctuality'],0) < 3)
                @php $puncClass = 'bg-red'  @endphp
                @endif 
                
                @if(round($seeker['time_management'],0) > 3)
                @php $timeClass = 'bg-green' @endphp
                @elseif(round($seeker['time_management'],0) == 3)
                @php $timeClass = 'bg-ember' @endphp
                @elseif(round($seeker['time_management'],0) < 3)
                @php $timeClass = 'bg-red'  @endphp
                @endif 

                @if(round($seeker['skills'],0) > 3)
                @php $skillClass = 'bg-green' @endphp
                @elseif(round($seeker['skills'],0) == 3)
                @php $skillClass = 'bg-ember' @endphp
                @elseif(round($seeker['skills'],0) < 3)
                @php $skillClass = 'bg-red'  @endphp
                @endif   

                <span class=" dropdown-toggle label {{$avgrateClass}}" data-toggle="dropdown">{{number_format($seeker['avg_rating'], 1, '.', '')}}</span>
                
                <ul class="dropdown-menu rating-info">
                  <li><div class="rating_on"> Punctuality</div>
                    <ul class="rate_me">
                        @for($i=1; $i<=5; $i++)
                        @if($i <= round($seeker['punctuality'],0))
                        <li><span class="{{$puncClass}}"></span></li>
                        @else
                        <li><span></span></li>
                        @endif
                        @endfor
                    </ul>
                    <label class="total-count "><span class="counter">{{round($seeker['punctuality'],0)}}</span>/5</label>
                </li>
                <li><div class="rating_on"> Time management</div>
                    <ul class="rate_me">
                        @for($i=1; $i<=5; $i++)
                        @if($i <= round($seeker['time_management'],0))
                        <li><span class="{{$timeClass}}"></span></li>
                        @else
                        <li><span></span></li>
                        @endif
                        @endfor
                    </ul>
                    <label class="total-count "><span class="counter">{{round($seeker['time_management'],0)}}</span>/5</label>
                </li>
                <li>
                    <div class="rating_on">  Personal/Professional skill</div>
                    <ul class="rate_me">
                        @for($i=1; $i<=5; $i++)
                        @if($i <= round($seeker['skills'],0))
                        <li><span class="{{$skillClass}}"></span></li>
                        @else
                        <li><span></span></li>
                        @endif
                        @endfor
                    </ul>
                    <label class="total-count "><span class="counter">{{round($seeker['skills'],0)}}</span>/5</label>
                </li>
            </ul>
        </span>
        @endif  
    </div>
    <div class="template-job-information-right">
        <span >{{round($seeker['distance'],0)}} miles away</span>
    </div> 
</div> 
<div class="job-type-detail">
    <p class="nopadding">{{$seeker['jobtitle_name']}}</p>
    @if($seeker['is_fulltime'] && $jobDetails['job_type']==App\Models\RecruiterJobs::FULLTIME)
    <span class="drk-green statusBtn mr-r-5">Full Time</span>
    @elseif(($seeker['is_parttime_monday'] || $seeker['is_parttime_tuesday'] || $seeker['is_parttime_wednesday'] || $seeker['is_parttime_thursday'] || $seeker['is_parttime_friday'] || $seeker['is_parttime_saturday'] || $seeker['is_parttime_sunday']) && $jobDetails['job_type']==App\Models\RecruiterJobs::PARTTIME)
    <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
    <span> | 
        @php 
        $dayArr = [];
        ($seeker['is_parttime_monday']==1)?array_push($dayArr,'Monday'):'';
        ($seeker['is_parttime_tuesday']==1)?array_push($dayArr,'Tuseday'):'';
        ($seeker['is_parttime_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
        ($seeker['is_parttime_thursday']==1)?array_push($dayArr,'Thursday'):'';
        ($seeker['is_parttime_friday']==1)?array_push($dayArr,'Friday'):'';
        ($seeker['is_parttime_saturday']==1)?array_push($dayArr,'Saturday'):'';
        ($seeker['is_parttime_sunday']==1)?array_push($dayArr,'Sunday'):'';
        @endphp
        {{ implode(', ',$dayArr) }}
    </span>
    @elseif($jobDetails['job_type']==App\Models\RecruiterJobs::TEMPORARY)
    <span class="bg-ember statusBtn mr-r-5">Temporary</span>
    @if($seeker['temp_job_dates'])
    <span class="dropdown date-drop">
        @php 
        $dates = explode(',',$seeker['temp_job_dates']);
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
    @endif
</div>
<dl class="dl-horizontal text-left mr-t-30">
    @if(isset($seekersList['allSkills'][$seeker['user_id']]))
    @foreach($seekersList['allSkills'][$seeker['user_id']] as $skills)
    <dt>{{$skills['title']}}:</dt>
    <dd>{{ implode(', ',$skills['skills']) }}</dd>
    @endforeach
    @endif
</dl>
<div class="row">
    <div class="col-sm-6 col-xs-6">
                <!--
                <a href="#">See more.. </a>
            -->
        </div>
        <div class="col-sm-6 col-xs-6 ">
            @if(isset($seeker['job_status']) && $seeker['job_status'] == 1)
            <button type="button" class="btn btn-primary-outline pull-right pd-l-30 pd-r-30">Invited</button>
            @else
            <button type="submit"  name="appliedStatus" value="{{ \App\Models\JobLists::INVITED }}" class="btn btn-primary pull-right pd-l-30 pd-r-30 ">Invite</button>
            @endif
        </div>
    </div>
</div>
</div>
</form>
<!--/search preference list-->
@endforeach 

{{ $seekersList['paginate']->appends(['distance' => $searchData['distance'],'avail_all' => $searchData['avail_all']])->links() }}
