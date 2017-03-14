@extends('web.layouts.dashboard')

@section('content')
<div class="container mr-b-60 padding-container-template">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
        {{ Session::get('message') }} 
    </p>
    @endif
    @if(count($favJobSeeker)>0)
    @foreach($favJobSeeker as $fav)
    <div class="media jobCatbox">
        <div class="media-left ">
            <div class="img-holder">

                <img class="media-object img-circle wd-66" src="{{ url("image/66/66/?src=" .$fav->profile_pic) }}" alt="...">
            </div>
        </div>
        <div class="media-body row">
            <div class="col-sm-8 pd-t-10 ">
                <div ><a href="#" class="media-heading">{{$fav->first_name}} {{$fav->last_name}}</a> <span class="mr-l-5 label label-success">{{number_format($fav->sum,1)}}</span></div>
                <p>{{ $fav->jobtitle_name}}</p>
            </div>
            <div class="col-sm-4 pd-t-15 text-right">
                <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 " onclick="putValue('{{$fav->seeker_id}}')" data-toggle="modal" data-target=".select_list">Invite</button>
<!--                @if($fav->applied_status =='1')
                <button type="submit" class="btn  btn-primary-outline active pd-l-30 pd-r-30 mr-b-5" >Invite</button>
                <p class="text-success "><span  class=" invite-success"><i class="fa fa-check "></i></span> Invitation sent</p>
                @else
                <button type="submit" class="btn btn-primary-outline pd-l-30 pd-r-30 " onclick="putValue('{{$fav->seeker_id}}')" data-toggle="modal" data-target=".select_list">Invite</button>
                @endif-->
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="jobCatbox mr-b-20">
        <div class="template-job-information ">
            <div class="template-job-information-left">
                <h4>No favorites added</h4>
            </div>
        </div>  
    </div>
    @endif
    <!-- Modal -->
    <div class="modal fade select_list " role="dialog">
        <div class="modal-dialog custom-modal popup-wd522">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invite Jobseeker</h4>
                </div>
                <div class="modal-body ">

                    <form data-parsley-validate action="invite-jobseeker" method="post">
                        {{ csrf_field() }}
                        <div class="form-group custom-select">
                            <label for="selectJobSeeker">Choose the job you want to invite for</label>
                            <input type="hidden" id="seekerId" name="seekerId" >
                            <select  id="selectJobSeeker" name="selectJobSeeker"  class="selectpicker" required="" data-parsley-required-message="Please select the job." >
                                <option value="" disabled selected>Select </option>
                                @foreach($jobDetail as $job)
                                <option value="{{$job->recruiterId}}" data-content="<h5>{{$job->jobtitle_name}}</h5><span class='label label-warning'>Temporary</span>">
                                    {{$job->jobtitle_name}}
                                </option>
                                <option data-divider="true"></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-right mr-t-20 mr-b-30">
                            <button type="button" class="modalClick btn btn-link mr-r-20" data-toggle="modal" data-target="#jobTemplate">Create Job</button>
                            <button type="submit" class="btn btn-primary pd-l-30 pd-r-30">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')

<script>
    $(document).ready(function () {
        $('#selectJobSeeker, #selectTemplate').selectpicker({
            style: 'btn btn-primary'
        });
    });
    function putValue(v){
        $('#seekerId').val(v);
    }
</script>
@endsection
@endsection
