<ul class="weekList">
    @foreach ($currentWeekCalendar as $calendar)
        <li>{{ $calendar['temp_job_format'] }}
            <div class="dental">
                <p>{{ $calendar['jobtitle_name'] }}</p>
                @if(!empty($calendar['seekers']['4']))
                    <div class="dentalImg">
                        <img src="{{ $calendar['seekers']['4']['0']['profile_pic'] }}" width="22" class="img-circle">
                        <div class="dentalNumber img-circle">{{ count($calendar['seekers']['4']) }}+</div>
                    </div>
                @endif
            </div>
            <a href="#" class="moreJobs pull-right">{{ $calendar['no_of_jobs'] }}</a>
        </li>
    @endforeach
</ul>