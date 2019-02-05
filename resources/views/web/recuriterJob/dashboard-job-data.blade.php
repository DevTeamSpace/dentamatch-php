<!--Job listing-->
<ul class="dashboarFinalList recentlyPost "> 
    @foreach ($jobList as $job)
        <li>
            <div class="template-job-information ">
                <div class="template-job-information-left">
                    <h4>{{ $job['jobtitle_name'] }}</h4>
                </div>
            </div>
            @if($job['job_type']==\App\Enums\JobType::FULLTIME)
                <div class="job-type-detail">
                    <div class="job-type-info">
                        <span class="bg-ltgreen statusBtn mr-r-5">Full Time</span>
                    </div>
                </div>
            @elseif($job['job_type']==\App\Enums\JobType::PARTTIME)
                <div class="job-type-detail">
                    <div class="job-type-info">
                        <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
                        <span> 
                            |
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
                    </div>
                </div>
            @else
                <div class="job-type-detail">
                    <div class="job-type-info">
                        <span class="bg-ember statusBtn mr-r-5">Temporary</span>
                        <span class="dropdown date-drop">
                            @php 
                            $dates = explode(',',$job['temp_job_dates']);
                            @endphp
                            <span class=" dropdown-toggle" data-toggle="dropdown"><span class="day-drop">{{ date('l, M d, Y',strtotime($dates[0])) }}</span>
                                <span class="caret"></span>
                            </span>
                            <ul class="dropdown-menu">
                                @foreach ($dates as $date)
                                    <li>{{ date('l, M d, Y',strtotime($date)) }}</li>
                                @endforeach
                            </ul>
                        </span>
                    </div>
                </div>
            @endif
            <div class="postViewDetail text-right">
                <a href="{{ url('/job/details') }}/{{ $job['id'] }}">View details</a>
            </div>
        </li>
    @endforeach
</ul>
