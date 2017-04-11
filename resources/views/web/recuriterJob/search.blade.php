@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/style.css')}}">
<link rel="stylesheet" href="{{asset('web/plugins/range-slider/css/bootstrap-slider.css')}}">
@endsection
@section('content')

<div class="container padding-container-template ">
    <!--breadcrumb-->
    <ul class="breadcrumb ">
        <li><a href="{{ url('job/lists') }}">Jobs Listing</a></li>
        <li class=""><a href="{{ url('job/details/'.$jobId) }}">Jobs Detail</a></li>
        <li class="active">Search Preference</li>
    </ul>
    <!--/breadcrumb-->
<?php //dd($jobDetails); ?>
    
    <div class="col-sm-12 mr-b-55">
        <div class="row section-title mr-b-10">
            Search Preference
            <a href="{{ url('job/details/'.$jobId) }}" class="btn btn-primary pd-l-30 pd-r-30 pull-right">Back</a>
        </div>
        
        <div class="jobCatbox row searchpreference">
            <div class="col-sm-4 mr-b-5">
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
              <label class="fnt-16 nopadding">Radius</label>
              <div >
                <input type="hidden" id="slider_val" name="slider_val" value="{{ $searchData['distance'] }}">
                <input type="hidden" id="avail_all" name="avail_all" value="{{ $searchData['avail_all'] }}">
                <input id="range_slider" type="text"/>
                <span class="pull-left">1 mile</span>
                <span class="pull-right">{{ $maxDistance }} miles</span>
              </div>
            </div>
        </div>
    </div>

    <div class="row sec-mob">
    <div class="col-sm-6 mr-b-10 col-xs-6">
        <div class="section-title" id="resultFound">{{$seekersList['paginate']->total()}} Results Found</div>
    </div>
    <div class="col-sm-6 text-right mr-b-10 col-xs-6">
        <button type="button" class="btn {{ !empty($searchData['avail_all']) ? "btn-primary" : "btn-primary-outline" }}" id="availAllBtn">Available all days </button>
    </div>
    </div>

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
<script type="text/javascript">
    var urlFav = "{{ url('recruiter/markFavourite') }}";
    maxSliderRange = "<?php echo $maxDistance; ?>";
</script>
<script src ="{{asset('web/scripts/search.js')}}"></script>
<script src ="{{asset('web/scripts/jobdetail.js')}}"></script>
@endsection
