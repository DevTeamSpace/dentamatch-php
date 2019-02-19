<div>
  @if( $seekerDetails['is_fulltime'] && (!isset($jobType) || $jobType == \App\Enums\JobType::FULLTIME) )
    <span class="drk-green statusBtn mr-b-5 statusBtnMargin">Full Time</span>
  @endif
  @if( \App\Helpers\TemplateHelper::isSeekerWorksPartTime($seekerDetails)
      && (!isset($jobType) || $jobType == \App\Enums\JobType::PARTTIME)
  )
    <span class="bg-ltgreen statusBtn mr-b-5 statusBtnMargin">Part Time</span>
    <span> | {{ \App\Helpers\TemplateHelper::getPartTimeString($seekerDetails) }} </span>
  @endif
  @if($seekerDetails['temp_job_dates'] && (!isset($jobType) || $jobType == \App\Enums\JobType::TEMPORARY))
    <label>
      <span class="bg-ember statusBtn mr-r-5">Temporary</span> |
      <span class="dropdown date-drop js-work-days-calendar">
        <input type="hidden" class="js-dates" value="{{ $seekerDetails['temp_job_dates'] }}">
        <a href="javascript:void(0);">
          <span class="day-drop">{{ \App\Helpers\TemplateHelper::getFirstDate($seekerDetails['temp_job_dates']) }}</span>
          <span class="fa fa-calendar"></span> View All Dates
        </a>
      </span>
    </label>
  @endif
</div>