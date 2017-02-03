@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/optionDropDown.css')}}">
@endsection
@section('content')
    <div class="container padding-container-template">
            <ul class="breadcrumb">
                <li>Template</li>
                <li class="active">{{ $templateData->templateName }}</li>
            </ul>
            <div class="row sec-mob mr-b-10">
                <div class="col-sm-6 col-xs-6">
                    <div class="section-title mr-b-10">{{ $templateData->templateName }}</div>
                </div>
                <div class="col-sm-6 text-right col-xs-6">
                    <a href="{{ url('createJob/'.$templateId)}}" class="btn btn-primary pd-l-25 pd-r-25">Create Job Opening</a>
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
<!--                    <div class="title">
                        <p>Job Title</p>
                        <p class="title-description">Dental Assistant</p>
                    </div>-->
                </div>  
                <div class="profile-div">
<!--                    <div class="title">
                        <p>Job Description</p>
                        <p class="title-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quorum sine causa fieri nihil putandum est. Potius inflammat, ut coercendi magis quam dedocendi esse videantur. Duo Reges: constructio interrete. Mihi enim satis est, ipsis non satis. Cuius ad naturam apta ratio vera illa et summa lex a philosophis dicitur. Id est enim, de quo quaerimus. Id quaeris, inquam, in quo, utrum respondero, verses te huc atque illuc necesse est.</p>
                        <p class="title-description">Sed ad bona praeterita redeamus. Roges enim Aristonem, bonane ei videantur haec: vacuitas doloris, divitiae, valitudo; Nam prius a se poterit quisque discedere quam appetitum earum rerum, quae sibi conducant, amittere.</p>
                    </div>-->
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
