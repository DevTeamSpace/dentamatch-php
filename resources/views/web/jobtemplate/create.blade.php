@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/optionDropDown.css')}}">
@endsection
@section('content')
<form data-parsley-validate method="post" action="{{ url('jobtemplates/saveOrUpdate') }}">
    <div class="customContainer center-block containerBottom">
        <div class="profieBox">
            <h3>Create Job Template</h3>
            <div class="commonBox cboxbottom">

                <div class="form-group">
                    <label >Template Name</label>
                    <input value="{{ (isset($templateData->templateName)?$templateData->templateName:'') }}" name="templateName" type="text" class="form-control"  data-parsley-required data-parsley-required-message="templete name required">
                </div>
                <div class="form-group">
                    <label >Job Title</label>

                    <div class="slt">
                        <select name="jobTitleId" class="ddlCars" data-parsley-required data-parsley-required-message="job title required">
                            @foreach ($jobTitleData as $jobTitle)
                            <option {{ (isset($templateData->jobTitleId) && $templateData->jobTitleId==$jobTitle['id'])?'selected':'' }} value="{{ $jobTitle['id'] }}">{{ $jobTitle['jobtitle_name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label>Job Description</label>
                    <textarea name="templateDesc" class="form-control txtHeight"  data-parsley-required data-parsley-required-message= "job description required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" >{{ (isset($templateData->templateDesc)?$templateData->templateDesc:'') }}</textarea>
                </div>

            </div>		

            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>KEY SKILLS</h5>
                    </div>
                </div>
                @foreach ($skillsData as $key => $skills)
                <div class="form-group">
                    <label ><?=$key?></label>
                    <select class="my-select" name="skills[]" multiple="multiple">
                        @foreach ($skills as $skillData)
                        <option {{ (isset($skillData['sel_skill_id']) && $skillData['sel_skill_id']==$skillData['id'])?'selected':'' }} value="{{ $skillData['id'] }}">{{ $skillData['skill_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>	
        </div>
        <div class="pull-right text-right">
            {!! csrf_field() !!}
            <input type="hidden" name="action" value="{{ (isset($templateData->id)?'edit':'add') }}">
            <input type="hidden" name="id" value="{{ (isset($templateData->id)?$templateData->id:'') }}">
            <button type="button" class="btn btn-link mr-r-10" style="font-weight:500">Cancel</button>
            <button type="submit" class="btn btn-primary pd-l-40 pd-r-40">Save</button>

        </div>
    </div>
</form>
@endsection

@section('js')
<script src="{{asset('web/scripts/optionDropDown.js')}}"></script>
<script src="{{asset('web/scripts/custom.js')}}"></script>
<script>
$('.ddlCars').multiselect({
    numberDisplayed: 3,
});

$('.my-select').searchableOptionList({
    maxHeight: 200,
    showSelectAll: true
});

</script>
@endsection
