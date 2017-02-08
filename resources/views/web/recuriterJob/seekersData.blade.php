<!--Seeker listing-->
@foreach ($seekersList as $seeker)
<!--search preference list-->
<div class="media jobCatbox">
    <div class="media-left ">
        <div class="img-holder ">
          <img class="media-object img-circle" src="{{ url('image/66/66/?src=' .$seeker['profile_pic']) }}" alt="...">
          @if($seeker['is_favourite'] != null)
            <span class="star star-fill"></span>
          @endif  
        </div>
    </div>
    <div class="media-body ">
        <div class="template-job-information mr-t-15">
          <div class="template-job-information-left">
            <h4 class="pull-left"><a href="{{ url('job/seekerdetails/'.$seeker['id'].'/'.$jobDetails->id) }}">{{$seeker['first_name'].' '.$seeker['last_name']}}</a></h4><span class="mr-l-5 label label-warning">{{round($seeker['avg_rating'],1)}}</span>
          </div>
          <div class="template-job-information-right">
            <span >{{round($seeker['distance'],0)}} miles away</span>
          </div> 
        </div> 
        <div class="job-type-detail">
            <p class="nopadding">{{$seeker['jobtitle_name']}}</p>
            @if($seeker['is_fulltime'])
            <span class="bg-ember statusBtn mr-r-5">Full Time</span>
            @elseif($seeker['is_parttime_monday'] || $seeker['is_parttime_tuesday'] || $seeker['is_parttime_wednesday'] || $seeker['is_parttime_thursday'] || $seeker['is_parttime_friday'] || $seeker['is_parttime_saturday'] || $seeker['is_parttime_sunday'])
            <span class="bg-ember statusBtn mr-r-5">Part Time</span>
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
            @else
            <span class="bg-ember statusBtn mr-r-5">Temporary</span>
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
        </div>
        <dl class="dl-horizontal text-left mr-t-30">
            <dt>Software Training:</dt>
            <dd>Softdent front office/Softdent charting</dd>

            <dt>CAD CAM:</dt>
            <dd>Planscan, Cerec</dd>

            <dt>DIG IMP:</dt>

            <dd>3 m true definition, 3 shape trios, Itero</dd>
            <dt>Digital Imaging:</dt>
            <dd>Dexis, Schick, Trophy, Suni</dd>
            <dt>Professional Training:</dt>
            <dd>Back office management, Supply inventory management</dd>
            <dt>Language:</dt>
            <dd>English, Spanish, Farsi</dd>
            <dt>General Skills:</dt>
            <dd>Intra oral camera, Alginate impression, Night guards</dd>
        </dl>
        <div class="row">
            <div class="col-sm-6 col-xs-6">
                <a href="#">See more.. </a>
            </div>
            <div class="col-sm-6 col-xs-6 ">
                <button type="submit" class="btn btn-primary pull-right pd-l-30 pd-r-30 ">Invite</button>
            </div>
        </div>
    </div>
</div>
<!--/search preference list-->
@endforeach 

{{ $seekersList->links() }}
