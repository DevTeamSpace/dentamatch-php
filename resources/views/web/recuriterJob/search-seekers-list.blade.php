<!--Seeker listing-->
@php
  $datesTemp = explode(',',$jobDetails['temp_job_dates']);
  $seekerDatesCount = count($datesTemp);
@endphp
@foreach ($seekersList['paginate'] as $seeker)
  <!--search preference list-->
  <form action="{{ url('job/updateStatus') }}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="jobId" value="{{ $jobDetails['id'] }}">
    <input type="hidden" name="seekerId" value="{{ $seeker['user_id'] }}">
    <input type="hidden" name="jobType" value="{{ $jobDetails['job_type'] }}">
    <div class="media jobCatbox">
      <div class="media-left ">
        <div class="img-holder ">
          <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
          @if($jobDetails['job_type']==\App\Enums\JobType::TEMPORARY)
            <span id="fav_{{ $seeker['user_id'] }}" onclick="markFavourite({{ $seeker['user_id'] }});"
                  class="star {{ ($seeker['is_favourite']==null)?'star-empty':'star-fill' }}"></span>
          @endif
        </div>
      </div>

      <div class="media-body ">
        <div class="template-job-information mr-t-15">
          <div class="template-job-information-left">
            <h4 class="pull-left"><a
                      href="{{ url('job/seekerdetails/'.$seeker['user_id'].'/'.$jobDetails['id']) }}">{{$seeker['first_name'].' '.$seeker['last_name']}}</a>
            </h4>
            @if($jobDetails['job_type']==\App\Enums\JobType::TEMPORARY)
              <span class="mr-l-5 dropdown date_drop">
                @include('web.recuriterJob.partial.seeker-ratings', [
                    'avgRating' => $seeker['avg_rating'],
                    'punctuality' => $seeker['punctuality'],
                    'timeManagement' => $seeker['time_management'],
                    'skills' => $seeker['skills']
                  ])
            </span>
            @endif
          </div>
        </div>
        <div class="job-type-detail">
          <p class="nopadding">{{$seeker['jobtitle_name']}}</p>
          @if($seeker['is_fulltime'] && $jobDetails['job_type']==\App\Enums\JobType::FULLTIME)
            <span class="drk-green statusBtn mr-r-5">Full Time</span>
          @elseif(($seeker['is_parttime_monday'] || $seeker['is_parttime_tuesday'] || $seeker['is_parttime_wednesday'] || $seeker['is_parttime_thursday'] || $seeker['is_parttime_friday'] || $seeker['is_parttime_saturday'] || $seeker['is_parttime_sunday']) && $jobDetails['job_type']==\App\Enums\JobType::PARTTIME)
            <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
            <span> |
              @php
                $dayArr = [];
                ($seeker['is_parttime_monday']==1)?array_push($dayArr,'Monday'):'';
                ($seeker['is_parttime_tuesday']==1)?array_push($dayArr,'Tuesday'):'';
                ($seeker['is_parttime_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
                ($seeker['is_parttime_thursday']==1)?array_push($dayArr,'Thursday'):'';
                ($seeker['is_parttime_friday']==1)?array_push($dayArr,'Friday'):'';
                ($seeker['is_parttime_saturday']==1)?array_push($dayArr,'Saturday'):'';
                ($seeker['is_parttime_sunday']==1)?array_push($dayArr,'Sunday'):'';
              @endphp
              {{ implode(', ',$dayArr) }}
        </span>
          @elseif($jobDetails['job_type']==\App\Enums\JobType::TEMPORARY)
            <span class="bg-ember statusBtn mr-r-5">Temporary</span>
            @if($seeker['temp_job_dates'])
              <span class="dropdown date-drop">
            @php
              $dates = explode(',',$seeker['temp_job_dates']);
            @endphp
                <input type="hidden" class="tempDates" value="{{ $seeker['temp_job_dates'] }}">
            <a href="javascript:void(0);" class=" dropdown-toggle showCalendarProfile">
                <span class="day-drop">{{ date('l, M d, Y',strtotime($dates[0])) }}</span>
                <span class="fa fa-calendar"></span> View All Dates</a>
              <!--                <ul class="dropdown-menu">
                    @foreach ($dates as $date)
                <li>{{ date('l, M d, Y',strtotime($date)) }}</li>
                    @endforeach
                      </ul>-->
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
          </div>
          <div class="col-sm-6 col-xs-6 ">
            @if(isset($seeker['job_status']) && $seeker['job_status'] == 1)
              <button type="button" class="btn btn-primary-outline pull-right pd-l-30 pd-r-30">Invited</button>
            @else
              <button type="submit" name="appliedStatus" value="{{ \App\Enums\JobAppliedStatus::INVITED }}"
                      class="btn btn-primary pull-right pd-l-30 pd-r-30 ">Invite
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--/search preference list-->
@endforeach
<input type="hidden" name="resultCount" id="resultCount" value="{{$seekersList['paginate']->total()}}">

{{ $seekersList['paginate']->appends(['avail_all' => $searchData['avail_all']])->links() }}
<script type="text/javascript">
  $(document).ready(function () {
    $('.showCalendarProfile').datepicker({
      format: 'yyyy/mm/dd',
      autoclose: true,
      daysOfWeekDisabled: [0, 1, 2, 3, 4, 5, 6],
    });
    $('.showCalendarProfile').click(function () {
      console.log('ssss');
      var tempDates = $(this).closest('.dropdown').find('.tempDates').val().split(',');
      console.log(tempDates);

      $(this).datepicker('setDates', tempDates);
      //['06-05-2018','06-06-2018','06-07-2018']
    });
  })
</script>