@extends('web.layouts.dashboard')

@section('content')
<div class="container mr-b-60 mr-t-30 padding-container-template">
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
                <div ><a href="{{ url('jobseeker/'.$fav->seeker_id) }}" class="media-heading">{{$fav->first_name}} {{$fav->last_name}}</a> <span class="mr-l-5 label label-success">{{ !empty($fav->sum) ? number_format($fav->sum,1) : "Not Yet Rated"}}</span></div>
                <p>{{ $fav->jobtitle_name}}</p>
            </div>
            <div class="col-sm-4 pd-t-15 text-right">
                <button type="button" class="btn btn-primary-outline pd-l-30 pd-r-30 " onclick="putValue('{{$fav->seeker_id}}')" >Invite</button>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="jobCatbox mr-b-20">
        <div class="template-job-information ">
            <div class="template-job-information-left">
                <h4>No favourites to show</h4>
            </div>
        </div>  
    </div>
    @endif
    <!-- Modal -->
    <div class="modal fade select_list " role="dialog" id="favModal">
        <div class="modal-dialog custom-modal popup-wd522">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Invite Jobseeker</h4>
                </div>
                <div class="modal-body ">
                    
                    <form data-parsley-validate action="/invite-jobseeker" id="invite_seeker" method="post">
                        {{ csrf_field() }}
                        <div class="form-group custom-select">
                            <div id="showMessage" class="alert alert-danger"></div>
                            <label for="selectJobSeeker">Choose the job you want to invite for</label>
                            <input type="hidden" id="seekerId" name="seekerId" >
                            <select  id="selectJobSeeker" name="selectJobSeeker"  class="selectpicker" required="" data-parsley-required-message="Please select the job." >
<!--                                <option value="" disabled selected>Select </option>
                                @foreach($jobDetail as $job)
                                @if(!empty($job->temp_job_dates))
                                @php 
                                $temp_jobs = (!empty($job->temp_job_dates)?explode(',', $job->temp_job_dates):array());
                                $dates_are = '  ';
                                $dates_are .= (isset($temp_jobs[0])?date('M d, Y',  strtotime($temp_jobs[0])):"");
                                $dates_are .= (isset($temp_jobs[1])?", ".date('M d, Y',  strtotime($temp_jobs[1])):"");
                                $dates_are .= (isset($temp_jobs[2])?" , ..":"");
                                @endphp
                                <option value="{{$job->recruiterId}}" data-content="<h5>{{$job->jobtitle_name}}</h5><span class='label label-warning'>Temporary</span>{{$dates_are}}">
                                    {{$job->jobtitle_name}}
                                </option>

                                
                                <option value="" disabled selected>Select </option>
<option value="6" data-content="<h5>Registered Dental Assistant</h5><span class="label label-warning">Temporary</span>May 17, 2017, May 26, 2017 Registered Dental Assistant</option><option data-divider="true"></option>

                                @endif
                                <option data-divider="true"></option>
                                @endforeach-->
                            </select>
                        </div>
                        <div class="text-right mr-t-20 mr-b-30">
                            <button type="button" class="modalClick btn btn-link mr-r-20" data-toggle="modal" data-target="#jobTemplate">Create Job</button>
                            <button type="submit" id="btnSubmit" class="btn btn-primary pd-l-30 pd-r-30">Send</button>
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
            style: 'btn btn-default'
        });
        
    });
    function putValue(v){
        $('#seekerId').val(v);
        var userId = v;
        
        if(userId){
            $.ajax({
                type:'GET',
                url:'/get-favorite-job-lists',
                data:{'userId' : userId},
                success:function(data){
                    $('#showMessage').removeClass('alert alert-danger');
                    $('#showMessage').html('');
                    if(data != ""){
                        $('#selectJobSeeker').html(data);
                    }else{
                        $('#showMessage').addClass('alert alert-danger');
                        $('#showMessage').html('You do not have any job for sending invitaion for this user');
                        
                    }
                    $('#selectJobSeeker').selectpicker('refresh');
                    $('#favModal').modal('show');
                }
            }); 
        }else{
            //alert('Please select user first'); 
        }
    }
</script>
@endsection
@endsection
