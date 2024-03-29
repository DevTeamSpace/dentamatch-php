    <!--Job listing-->
    @foreach ($jobList as $job)
    
    <div class="jobCatbox mr-b-20">
        <div class="template-job-information ">
            <div class="template-job-information-left">
                <h4>{{ $job['jobtitle_name'] }}</h4>
            </div>
            <div class="template-job-information-right">
                <span>Posted : 
                    @if($job['days']>0)
                    {{ $job['days'].' day'.(($job['days']>1)?'s':'') }} ago
                    @else
                    Today
                    @endif
                </span>

            </div> 
        </div>  
        
        <div class="job-type-detail">
            <div class="job-type-info">
                @if($job['job_type']==\App\Enums\JobType::FULLTIME)
                <span class="drk-green statusBtn mr-r-5">Full Time</span>
                @elseif($job['job_type']==\App\Enums\JobType::PARTTIME)
                <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
                <span> | 
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
                @else
                <span class="bg-ember statusBtn mr-r-5">Temporary</span>
                <span class="dropdown date-drop">
                    @php 
                    $dates = explode(',',$job['temp_job_dates']);
                    @endphp
                    <input type="hidden" class="tempDates" value="{{ $job['temp_job_dates'] }}">
                    <a href="javascript:void(0);" class=" dropdown-toggle showCalendarProfile">
                        <span class="day-drop">{{ date('l, M d, Y',strtotime($dates[0])) }}</span>
                        <span class="fa fa-calendar"></span> View All Dates</a>
<!--                        <ul class="dropdown-menu">
                            @foreach ($dates as $date)
                            <li>{{ date('l, M d, Y',strtotime($date)) }}</li>
                            @endforeach
                        </ul>-->
                    </span>
                    @endif
                </div>
            </div>

            <div class="template-job-information mr-t-30">
                <div class="template-job-information-left">
                    <address>
                        <strong>{{ $job['office_name'] }}</strong><br>
                        {{ $job['office_types_name'] }}<br>
                        {{ $job['address'].' '.$job['zipcode'] }}<br>
                        @if($job['job_type']==\App\Enums\JobType::TEMPORARY)
                        <span>Number of Candidates Needed: {{ $job['no_of_jobs'] }}</span>
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
                        $valueCountArr = array("1"=>0, "2"=>0);
                        if($job['applied_status']!=null){
                        $valueArr = explode(',',$job['applied_status']);

                        if($valueArr && count($valueArr)>0){
                        foreach($valueArr as $val){
                        $statusArr = explode('_',$val);
                        $valueCountArr[$statusArr[1]]++ ;
                    }
                }
            }
            @endphp
            @if($valueCountArr["2"]>0)
            <li><a href="{{ url('job/details/'.$job['id']) }}">{{ $valueCountArr['2'] }} Candidate{{ ($valueCountArr['2']>1)?'s':'' }} Applied</a> </li>
            @endif
            @if($valueCountArr["1"]>0)
            <li><a href="{{ url('job/details/'.$job['id']) }}">{{ $valueCountArr['1'] }} Candidate{{ ($valueCountArr['1']>1)?'s':'' }} Invited </a></li>
            @endif
            <li><a href="{{ url('job/details/'.$job['id']) }}"> View Details</a></li>
        </ul>
    </div>
    <div class="col-sm-4 ">
       
        <div class="search-seeker">
            <a href="{{ url('job/search',[$job['id']]) }}" class="btn btn-primary pull-right">Search Available Candidates</a>
        </div>
    </div>


</div>

</div>  
@endforeach 
{{ $jobList->links() }}
@section('js')
<script src="{{asset('web/scripts/moment.min.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.showCalendarProfile').datepicker({
            format: 'yyyy/mm/dd',
            autoclose: true,
            daysOfWeekDisabled:[0,1,2,3,4,5,6],            
        });
        $('.showCalendarProfile').click(function(){      
            console.log('ssss');
            var tempDates = $(this).closest('.dropdown').find('.tempDates').val().split(',');
            console.log(tempDates);
            
            $(this).datepicker('setDates', tempDates);
            //['06-05-2018','06-06-2018','06-07-2018']
        });
    })
</script>
@endsection
