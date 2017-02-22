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
                    <input value="{{ (isset($templateData->templateName)?$templateData->templateName:'') }}" name="templateName" type="text" class="form-control" data-parsley-pattern="/^[a-zA-Z0-9\-\s]+$/" data-parsley-required data-parsley-required-message="Required">
                </div>
                <div class="form-group">
                    <label >Job Title</label>

                    <div class="slt custom-select">
                        <select id="jobTitleId" name="jobTitleId" class="selectpicker"  data-parsley-required data-parsley-required-message="Required">
                            @foreach ($jobTitleData as $jobTitle)
                            <option {{ (isset($templateData->jobTitleId) && $templateData->jobTitleId==$jobTitle['id'])?'selected':'' }} value="{{ $jobTitle['id'] }}">{{ $jobTitle['jobtitle_name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label>Job Description</label>
                    <textarea name="templateDesc" class="form-control chacterValidtion txtHeight" maxlength="1000"  data-parsley-required data-parsley-required-message= "Required"   >{{ (isset($templateData->templateDesc)?$templateData->templateDesc:'') }}</textarea>
                </div>

            </div>		

            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>KEY SKILLS</h5>
                    </div>
                @foreach ($skillsData as $key => $skills)
                <div class="form-group">
                    <label ><?=$key?></label>
                    <select class="my-select" name="skills[]" multiple="multiple" style="display:none">
                        @foreach ($skills as $skillData)
                        <option {{ (isset($skillData['sel_skill_id']) && $skillData['sel_skill_id']==$skillData['id'])?'selected':'' }} value="{{ $skillData['id'] }}">{{ $skillData['skill_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
					</div>
                
            </div>	
        </div>
        <div class="pull-right text-right">
            {!! csrf_field() !!}
            <input type="hidden" name="action" value="{{ (isset($templateData->id)?'edit':'add') }}">
            <input type="hidden" name="id" value="{{ (isset($templateData->id)?$templateData->id:'') }}">
            <button type="button" class="btn btn-link mr-r-10" data-toggle="modal" data-target="#discardTemplate">Cancel</button>
            <button type="submit" id="Save" class="btn btn-primary pd-l-40 pd-r-40">Save</button>

        </div>
    </div>
</form>

<div id="discardTemplate" class="modal fade" role="dialog" style="display: none;">
		<div class="modal-dialog custom-modal popup-wd522">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h4 class="modal-title">Discard Template</h4>
				</div>
				<div class="modal-body text-center">
					
					<p>Do you want to discard the changes?</p>

					<div class="mr-t-20 mr-b-30">
						<button id="cancelButton" type="button" class="btn btn-link mr-r-5">Yes</button>
                                                <button type="button" class="btn btn-primary pd-l-30 pd-r-30" data-dismiss="modal">No </button>
					</div>




				</div>


			</div>

		</div>
	</div>
@endsection

@section('js')
<script src="{{asset('web/scripts/optionDropDown.js')}}"></script>
<script src="{{asset('web/scripts/custom.js')}}"></script>
<script>
    $('form').submit(function(e){
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            $('#Save').attr('disabled',true);
        }
    });
    document.getElementById("cancelButton").onclick = function () {
        location.href = "{{ url('jobtemplates') }}";
    };
$('.ddlCars').multiselect({
    numberDisplayed: 3,
});
	$('#jobTitleId').selectpicker({
				style: 'btn btn-default'
			});

$('.my-select').searchableOptionList({
    maxHeight: 200,
    showSelectAll: true
});

</script>
@endsection
