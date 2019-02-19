<div>
  @if( $job['job_type'] == \App\Enums\JobType::FULLTIME )
    <span class="drk-green statusBtn mr-r-5">Full Time</span>
  @elseif( $job['job_type']==\App\Enums\JobType::PARTTIME )
    <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
    <span> | {{ \App\Helpers\TemplateHelper::getPartTimeString($job) }} </span>
  @else
    <span class="bg-ember statusBtn mr-r-5">Temporary</span> |
    <span class="dropdown date-drop js-work-days-calendar">
      <input type="hidden" class="js-dates" value="{{ $job['temp_job_dates'] }}">
      <a href="javascript:void(0);">
        <span class="day-drop">{{ \App\Helpers\TemplateHelper::getFirstDate($job['temp_job_dates']) }}</span>
        <span class="fa fa-calendar"></span> View All Dates
      </a>
    </span>
  @endif
</div>