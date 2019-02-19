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

    @include('web.recuriterJob.partial.job-type')

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
          <p>{{ $job['template_desc'] }}</p>
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
            <li><a href="{{ url('job/details/'.$job['id']) }}">{{ $valueCountArr['2'] }}
                Candidate{{ ($valueCountArr['2']>1)?'s':'' }} Applied</a></li>
          @endif
          @if($valueCountArr["1"]>0)
            <li><a href="{{ url('job/details/'.$job['id']) }}">{{ $valueCountArr['1'] }}
                Candidate{{ ($valueCountArr['1']>1)?'s':'' }} Invited </a></li>
          @endif
          <li><a href="{{ url('job/details/'.$job['id']) }}"> View Details</a></li>
        </ul>
      </div>
      <div class="col-sm-4 ">

        <div class="search-seeker">
          <a href="{{ url('job/search',[$job['id']]) }}" class="btn btn-primary pull-right">Search Available
            Candidates</a>
        </div>
      </div>


    </div>

  </div>
@endforeach
{{ $jobList->links() }}
