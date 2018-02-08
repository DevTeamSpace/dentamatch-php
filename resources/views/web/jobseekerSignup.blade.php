@extends('web.layouts.jobseeker')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/optionDropDown.css')}}">
@endsection
@section('content')
@if (count($errors) > 0)
    <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
        {{ Session::get('message') }} 
        <span class="close" data-dismiss="alert">&times;</span>
    </p>
@endif

<form data-parsley-validate method="post" action="{{ url('jobseeker/storeSignup') }}">
    <div class="customContainer center-block containerBottom">
        <div class="profieBox">
            <h3>Jobseeker Signup</h3>
            <div class="commonBox cboxbottom">

                <div class="form-group">
                    <label>First Name</label>
                    <input placeholder="First Name" value="{{ app('request')->input('firstName') }}" name="firstName" type="text" class="form-control" data-parsley-required data-parsley-required-message="First name is required">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input placeholder="Last Name" value="{{ app('request')->input('lastName') }}" name="lastName" type="text" class="form-control" data-parsley-required data-parsley-required-message="Last name is required">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input placeholder="Email" value="{{ app('request')->input('email') }}" name="email" type="email" class="form-control" data-parsley-required data-parsley-required-message="Email is required">
                </div>
                <input type="hidden" name="jobtitles" id="jsonVal" value="{{ json_encode($jobTitleData) }}">
                        
                <div class="form-group">
                    <label>Job Title</label>
                    <div class="slt custom-select">
                        <select id="jobTitleId" name="jobTitleId" class="selectpicker mr-b-5" data-parsley-required data-parsley-required-message="Job title is required">
                            <option value="">Select job title</option>
                            @foreach ($jobTitleData as $key=>$jobTitle)
                            @if($key==0)
                                <option data-divider="true"></option>
                            @endif
                            @if($jobTitle['id'] == app('request')->input('jobTitleId'))
                                <option selected='true'  value="{{ $jobTitle['id'] }}">{{ $jobTitle['jobtitle_name'] }}</option>
                            @else
                                <option value="{{ $jobTitle['id'] }}" >{{ $jobTitle['jobtitle_name'] }}</option>
                            @endif
                                <option data-divider="true"></option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="liscenceGroup licenseId" >
                <div class="form-group">
                    <label>License</label>
                    <input placeholder="License No" value="{{ app('request')->input('license') }}" name="license" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label>License State</label>
                    <input placeholder="State" value="{{ app('request')->input('state') }}" name="state" type="text" class="form-control">
                </div>
                </div>
                <div class="form-group">
                    <label>Preferred Job Locations</label>
                    <div class="slt custom-select">
                        <select data-parsley-required data-parsley-required-message= "Select preferred location" name="preferredJobLocationId" id="preferredJobLocationId" class="selectpicker mr-b-5">
                            <option value="">Select preferred job locations</option>
                            @foreach ($preferredLocationId as $key=>$prefLocation)
                            @if($key==0)
                                <option data-divider="true"></option>
                            @endif
                            @if($prefLocation['id'] == app('request')->input('preferredJobLocationId'))
                                <option selected='true' value="{{ $prefLocation['id'] }}">{{ $prefLocation['preferred_location_name'] }}</option>
                            @else
                                <option value="{{ $prefLocation['id'] }}">{{ $prefLocation['preferred_location_name'] }}</option>
                            @endif
                                <option data-divider="true"></option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>About Me</label>
                    <textarea data-parsley-required data-parsley-required-message="About me is required" class="form-control" name="aboutMe">{{ app('request')->input('aboutMe') }}</textarea>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="privacy" id="checkbox" style="-webkit-appearance:checkbox; margin-right:5px;" value="" required=""><strong>I agree to the Terms and Conditions and Privacy policy</strong>
                </div>

            </div>
        </div>
        <div class="pull-right text-right">
            {!! csrf_field() !!}
            <button type="submit" id="Save" class="btn btn-primary pd-l-40 pd-r-40">Save</button>

        </div>
    </div>
</form>

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



    $('#jobTitleId > option').each(function(index) {
       $(this).attr("customIndex",index);
    });
    
    

    $('#jobTitleId').change(function(){
    

     
    var listID,jsonValue;
    


    listID= $(this).val();
 
    
    jsonValue=JSON.parse($(jsonVal).val());
  


    for(var i=0;i<jsonValue.length; i++){


if(jsonValue[i].id==listID)
{

if(jsonValue[i].is_license_required==1){
    $('.liscenceGroup').removeClass("licenseId");
        
        }

 else{
    $('.liscenceGroup').addClass("licenseId");
        }
    break;

}


    }
    

            
        });


    





    

</script>
@endsection
