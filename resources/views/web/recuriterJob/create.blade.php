@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/multiple-select.css')}}">
<link rel="stylesheet" href="{{asset('web/css/bootstrap-select.css')}}">
<link rel="stylesheet" href="{{asset('web/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" href="{{asset('web/css/dashboard.css')}}">

@endsection
@section('content')
<div class="container padding-container-template">
    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ url('jobtemplates') }}">Template</a></li>
        <li><a href="{{ url('jobtemplates/view',[$jobTemplates->id]) }}">{{ $jobTemplates->templateName }}</a></li>
        <li class="active">Create Job Opening</li>
    </ul>
    <!--/breadcrumb-->

    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
        {{ Session::get('message') }} 
        <span class="close" data-dismiss="alert">&times;</span>
    </p>
    @endif
    <form data-parsley-validate method="post" action="{{ url('createJob/saveOrUpdate') }}" novalidate autocomplete="off">
        <div class="row sec-mob">
            <div class="col-sm-6 mr-b-10 col-xs-6">
                <div class="section-title">Create Job Opening</div>
            </div>
            <div class="col-sm-6 text-right mr-b-10 col-xs-6">
                {!! csrf_field() !!}
                <input type="hidden" name="templateId" value="{{ $templateId }}">
                <a href="{{ url('jobtemplates') }}" class="btn-link-noline mr-r-10">Cancel</a>

                <button type="submit" class="btn btn-primary pd-l-25 pd-r-25">Publish</button>
            </div>
        </div>

        <div class="commonBox cboxbottom padding-dentaltemplate">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="form-group custom-select">
                <label >Dental Office Address</label>
                <select data-parsley-required data-parsley-required-message= "Select dental office address" name="dentalOfficeId" id="dentalOfficeId" class="selectpicker mr-b-5">
                    <option value="">Select dental office address</option>
                    @foreach ($offices as $key=>$office)
                    @if($key==0)
                    <option data-divider="true"></option>
                    @endif
                    <option value="{{ $office['id'] }}">{{ $office['address'] }}</option>
                    <option data-divider="true"></option>
                    @endforeach

                </select>
                <input type="hidden" value="{{ json_encode($offices) }}" id="officeJson">
                <input type="hidden" value="add" name="action">
                <p class="error-div hide">Job cannot be currently created for this location. We will soon be available in your area.</p>

            </div>
            <div class="form-group custom-select">
                <label >Preferred Job Locations</label>
                <select data-parsley-required data-parsley-required-message= "Select preferred location" name="preferredJobLocationId" id="preferredJobLocationId" class="selectpicker mr-b-5">
                    <option value="">Select preferred job locations</option>
                    @foreach ($preferredLocationId as $prefLocation)
                    @if($key==0)
                    <option data-divider="true"></option>
                    @endif
                    <option value="{{ $prefLocation['id'] }}">{{ $prefLocation['preferred_location_name'] }}</option>
                    <option data-divider="true"></option>
                    @endforeach

                </select>
            </div>
            <div class="form-group">
                <label  >Job Type</label>
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="full-time-box">
                            <input data-parsley-required data-parsley-required-message= "job type required" class="magic-radio" type="radio" name="jobType" id="fulltime" value="{{ \App\Models\RecruiterJobs::FULLTIME }}">
                            <label for="Full Time">
                                Full Time
                            </label>
                        </div>  
                    </div>
                    <div class="col-md-4  col-lg-4 ">
                        <div class="full-time-box">
                            <input class="magic-radio" type="radio" name="jobType" id="parttime" value="{{ \App\Models\RecruiterJobs::PARTTIME }}">
                            <label for="Part Time">
                                Part Time
                            </label>
                            <select data-parsley-required="false" data-parsley-required-message="select days" name="partTimeDays[]" multiple="multiple" id="monthSelect" style="display: none;" class="select-days-custom">
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                                <option value="7">Sunday</option>

                            </select>
                        </div>  
                    </div>
                    <div class="col-md-4 col-lg-4 ">
                        <div class="full-time-box">
                            <input class="magic-radio" type="radio" name="jobType" id="temporary" value="{{ \App\Models\RecruiterJobs::TEMPORARY }}">

                            <label for="Temporary">
                                Temporary
                            </label>
                            <input name="tempDates" type="text" id="CoverStartDateOtherPicker" data-date="" class="date-instance" />
                        </div>  
                    </div>
                </div>

            </div>
            <div class="form-group  job-opening hide">
                <label >Total Job Opening</label>
                <input name="noOfJobs" type="text" id="jobopening" class="form-control" data-parsley-min="1" data-parsley-pattern="^[0-9]*$" data-parsley-pattern-message="numeric only "  data-parsley-required-message="required" data-parsley-min-message="zero should not be allowed"/>
            </div>
        </div>  
    </form>
</div>    

@endsection

@section('js')
<script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>
<script src="{{asset('web/scripts/multiple-select.js')}}"></script>
<script src="{{asset('web/scripts/moment.min.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>
@endsection
