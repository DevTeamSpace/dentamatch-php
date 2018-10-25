@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/style.css')}}">
<link rel="stylesheet" href="{{asset('web/plugins/range-slider/css/bootstrap-slider.css')}}">
<link rel="stylesheet" href="{{asset('web/css/bootstrap-datepicker.css')}}">
@endsection
@section('content')

<div class="container padding-container-template ">
    <!--breadcrumb-->
    <ul class="breadcrumb ">
        <li><a href="{{ url('job/lists') }}">Jobs Listing</a></li>
        <li class=""><a href="{{ url('job/details/'.$jobId) }}">Jobs Detail</a></li>
        <li class="active">Search Results</li>
    </ul>
    <!--/breadcrumb-->
    
    <div class="col-sm-12 mr-b-55">
        <div class="row section-title mr-b-10">
            Search Results
            <a href="{{ url('job/details/'.$jobId) }}" class="btn btn-primary pd-l-30 pd-r-30 pull-right">Back</a>
        </div>
        
        <div class="jobCatbox row searchpreference">
            <div class="col-sm-7 mr-b-5">
                <label class="fnt-16 nopadding">Job Title</label>
                <h4 class="textcolr-38 nopadding"><strong>{{$jobDetails['jobtitle_name']}}</strong></h4>
            </div>
            <div class="col-sm-3 mr-b-5">
              <label class="fnt-16 nopadding">Job Type</label>
              <h4 class="textcolr-38 nopadding">
                @if($jobDetails['job_type']==\App\Models\RecruiterJobs::FULLTIME)
                <strong>Full Time</strong>
                @elseif($jobDetails['job_type']==\App\Models\RecruiterJobs::PARTTIME)
                <strong>Part Time</strong>
                @else
                <strong>Temporary</strong>
                @endif
              </h4>
            </div>
            <div class="col-sm-5">
<!--                <div class="form-group">   custom-select 
                <label class="fnt-16 nopadding">Preferred Job Locations</label>
                <div class="clearfix"></div>
                <select name="preferredLocationId" id="preferredLocationIdSearch" class="customPreferDrop selectpicker">
                    <option value="">Select preferred job locations</option>
                    @foreach ($preferredLocations as $key=>$prefLocation)
                    @if($key==0)
                    <option data-divider="true"></option>
                    @endif
                    <option value="{{ $prefLocation['id'] }}" @if($prefLocation['id'] == $preferredLocationId) selected='true' @endif; >{{ $prefLocation['preferred_location_name'] }}</option>
                    <option data-divider="true"></option>
                    @endforeach

                </select>
                </div>-->
                <div >
                <input type="hidden" id="avail_all" name="avail_all" value="{{ $searchData['avail_all'] }}">
                </div>
            </div>
        </div>
    </div>

    <div class="row sec-mob">
    <div class="col-sm-6 mr-b-10 col-xs-6">
        <div class="section-title" id="resultFound">{{$seekersList['paginate']->total()}} Result{{ (($seekersList['paginate']->total()>1)?'s':'') }} Found</div>
    </div>
    <div class="col-sm-6 text-right mr-b-10 col-xs-6">
        <button type="button" class="btn {{ !empty($searchData['avail_all']) ? "btn-primary" : "btn-primary-outline" }}" id="availAllBtn">Available all days </button>
    </div>
    </div>
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
        {{ Session::get('message') }} 
        <span class="close" data-dismiss="alert">&times;</span>
    </p>
    @endif
    @if(count($seekersList['paginate'])>0)
        <div class="jobseeker-statebox"  id="ajaxData">
            @include('web.recuriterJob.seekers-data')
        </div>
    @else
    <div class="jobCatbox mr-b-20">
        <div class="template-job-information ">
            <div class="template-job-information-left">
                <h4>Nothing to show</h4>
            </div>
        </div>  
    </div>
    @endif    
<!--loader part-->
<div class="loader-box" style="display: none;">
    <div id="loader"></div>
</div>
<!--/loader part-->

</div>

@endsection

@section('js')
<script src="{{asset('web/scripts/moment.min.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript">
    var urlFav = "{{ url('recruiter/markFavourite') }}";
</script>
<script src ="{{asset('web/scripts/search.js')}}"></script>
<script src ="{{asset('web/scripts/jobdetail.js')}}"></script>
@endsection
