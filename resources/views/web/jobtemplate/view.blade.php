@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/optionDropDown.css')}}">
@endsection
@section('content')
<div class="container padding-container-template">
    <ul class="breadcrumb">
        <li><a href="{{ url('jobtemplates') }}">Position Templates</a></li>
        <li class="active">{{ $templateData->templateName }}</li>
    </ul>
    <div class="row sec-mob mr-b-10">
        <div class="col-sm-6 col-xs-6">
            <div class="section-title mr-b-10">{{ $templateData->templateName }}</div>
        </div>
        <div class="col-sm-6 text-right col-xs-6">
            <a href="{{ url('createJob/'.$templateId)}}" class="btn btn-primary pd-l-25 pd-r-25">Post This Job</a>
        </div>  
    </div>    
    <div class="commonBox">
        <div class="viewtemp-div mr-b-30">
            <div class="dropdown icon-upload-ctn1">
                <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                <ul class="actions text-left dropdown-menu">
                    <li>
                        <a href="{{ url('jobtemplates/edit/'.$templateId) }}">
                            <span class="gbllist iconFirstEdit">
                                <i class="icon icon-edit"></i>  Edit
                            </span>

                        </a>
                    </li>
                </ul>
            </div>
            <div class="searchResultHeading">
                <h6><strong>Job Title</strong></h6>
                <p>{{ $templateData->jobtitle_name }}</p>
            </div>
        </div>  
        <div class="profile-div">
            <div class="viewtemp-div mr-b-30">
                <h6><strong>Job Description</strong></h6>
                <p>{{ $templateData->templateDesc }}</p>
            </div>
        </div> 
        <div class="viewtemp-div">
            <div class="viewtemp-div">
                <h6 class="mr-b-30"><strong>KEY SKILLS</strong></h6>
            </div>
            @foreach($templateSkillsData as $index=>$skillsData)
            <div class="{{ ($index=='0'?'':'pd-t-5') }}  keySkills">
                <h6><strong>{{ $skillsData['parent_skill_name']}}</strong></h6>
                <p>{{ $skillsData['skill_name']}}</p>
            </div>
            @endforeach
        </div> 
    </div>

</div>   

@endsection

@section('js')
<script src="{{asset('web/scripts/optionDropDown.js')}}"></script>
<script src="{{asset('web/scripts/custom.js')}}"></script>

@endsection
