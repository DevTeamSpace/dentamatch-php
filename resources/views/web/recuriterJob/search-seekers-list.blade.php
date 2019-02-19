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
        <p class="nopadding">{{$seeker['jobtitle_name']}}</p>

        @include('web.recuriterJob.partial.seeker-timetable', ['seekerDetails' => $seeker, 'jobType' => $jobDetails['job_type']])

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