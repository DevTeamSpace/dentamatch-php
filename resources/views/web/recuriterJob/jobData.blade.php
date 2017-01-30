    <!--Job listing-->
    @foreach ($jobList as $job)
    
    <div class="jobCatbox mr-b-20">
        <div class="template-job-information ">
            <div class="template-job-information-left">
                <h4>{{ $job['jobtitle_name'] }}</h4>
            </div>
            <div class="template-job-information-right">
                <span>Posted : {{ $job['days'] }} days ago</span>

            </div> 
        </div>  
        
        <div class="job-type-detail">
            <div class="job-type-info">
                @if($job['job_type']==\App\Models\RecruiterJobs::FULLTIME)
                <span class="bg-ltgreen statusBtn mr-r-5">Full Time</span>
                @elseif($job['job_type']==\App\Models\RecruiterJobs::PARTTIME)
                <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
                <span> | 
                    @php 
                        $dayArr = [];
                        ($job['is_monday']==1)?array_push($dayArr,'Monday'):'';
                        ($job['is_tuesday']==1)?array_push($dayArr,'Tuseday'):'';
                        ($job['is_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
                        ($job['is_thursday']==1)?array_push($dayArr,'Thursday'):'';
                        ($job['is_friday']==1)?array_push($dayArr,'Friday'):'';
                        ($job['is_saturday']==1)?array_push($dayArr,'Saturday'):'';
                        ($job['is_sunday']==1)?array_push($dayArr,Sunday):'';
                    @endphp
                    {{ implode(', ',$dayArr) }}
                </span>
                @else
                <span class="bg-ember statusBtn mr-r-5">Temporary</span>
                <span class="dropdown date-drop">
                    @php 
                    $dates = explode(',',$job['temp_job_dates']);
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
        </div>

        <div class="template-job-information mr-t-30">
            <div class="template-job-information-left">
                <address>
                    <strong>{{ $job['office_name'] }}</strong><br>
                    {{ $job['office_desc'] }}<br>
                    {{ $job['address'].' '.$job['zipcode'] }}<br>
                    @if($job['job_type']==\App\Models\RecruiterJobs::TEMPORARY)
                    <span>Total Job Opening: {{ $job['no_of_jobs'] }}</span>
                    @endif
                </address> 

            </div>    

        </div> 
        <div class="template-job-information mr-t-15 width-job-info">

            <ul class="job-detail-listing">
                <li>
                    <span>Job Description</span>
                    <p >{{ $job['template_desc'] }}</p>
                </li>

            </ul>


        </div>
        <div class="row">
            <div class="col-sm-8">
                <ul class="listing listing-inline ">
                    @php
                    $valueArr = explode(',',$job['applied_status']);
                    
                    if(count($valueArr)>0){
                        $valueCountArr = array_count_values($valueArr);
                    }
                    @endphp
                    @if(isset($valueCountArr['2']))
                    <li><a href="{{ url('job/details/'.$job['id']) }}">{{ $valueCountArr['2'] }} Seekers Applied</a> </li>
                    @endif
                    @if(isset($valueCountArr['1']))
                    <li><a href="{{ url('job/details/'.$job['id']) }}">{{ $valueCountArr['1'] }} Seekers Invited </a></li>
                    @endif
                    <li><a href="{{ url('job/details/'.$job['id']) }}"> View Detail</a></li>
                </ul>
            </div>
            <div class="col-sm-4 search-seeker">
                <form method="post" action="{{ url('search/job') }}">
                    <input type="hidden" name="jobType" value="{{ $job['job_type'] }}">
                    <input type="hidden" name="jobTitle" value="{{ $job['job_title_id'] }}">
                    <button type="submit" class="btn btn-primary pd-l-30 pd-r-30 pull-right">Search Seekers</button>
                </form>
            </div>
        </div>

    </div>  
    @endforeach 
{{ $jobList->links() }}
