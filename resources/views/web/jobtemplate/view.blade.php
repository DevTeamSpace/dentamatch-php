@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/optionDropDown.css')}}">
@endsection
@section('content')
    <div class="container padding-container-template">
            <ul class="breadcrumb breadcrumb-custom">
                <li>Template</li>
                <li class="active">{{ $templateData->templateName }}</li>
            </ul>
            <div class="template">
                <div class="template-header">
                    <h1>{{ $templateData->templateName }}</h1>
                </div>
                <div class="template-header-right">
                    <a href="{{ url('createJob/'.$templateId)}}" class="btn btn-primary">Create Job Opening</a>
                </div>  
            </div>    
            <div class="viewdentaltemplate">
                <div class="profile-div">
                    <div class="dropdown icon-upload-ctn1">
                        <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                        <ul class="actions text-left dropdown-menu">
                            <li class="text-center">
                                <a href="{{ url('jobtemplates/edit/'.$templateId) }}">
                                    <span class="delete-icon">
                                        <img src="{{asset('web/images/denatmatch-shape.png')}}" alt="delta delete icon" width="12">
                                    </span>
                                    <span class="cancel">Edit</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="searchResultHeading">
			<h5>Job Title</h5>
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
                    <div class="searchResultHeading">
			<h5>Job Description</h5>
			<p>{{ $templateData->templateDesc }}</p>
		</div>
                </div> 
                <div class="profile-div">
                   <div class="searchResultHeading">
			<h5>KEY SKILLS</h5>
			</div>
			@foreach($templateSkillsData as $index=>$skillsData)
                            <div class="{{ ($index=='0'?'':'pd-t-10') }}  keySkills">
                                <b>{{ $skillsData['parent_skill_name']}}</b>
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
