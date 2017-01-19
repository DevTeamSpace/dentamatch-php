@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/multiple-select.css')}}">
<link rel="stylesheet" href="{{asset('web/css/bootstrap-select.css')}}">
<link rel="stylesheet" href="{{asset('web/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" href="{{asset('web/css/dashboard.css')}}">

@endsection
@section('content')
<form data-parsley-validate method="post" action="{{ url('createJob/saveOrUpdate') }}">
    <div class="container padding-container-template">
            <ul class="breadcrumb breadcrumb-custom">
                <li>Template</li>
                <li>Dental Assistant Template</li>
                <li class="active">Create Job Opening</li>
            </ul>
            <div class="template">
                <div class="template-header">
                    <h1>Create Job Opening</h1>
                </div>
                <div class="template-header-right-publish">
                    {!! csrf_field() !!}
                    <input type="hidden" name="templateId" value="{{ $templateId }}">
                    <span class="cancel">Cancel</span>
                    <button type="submit" class="btn btn-primary">Publish</button>
                </div>  
            </div>    
            <div class="viewdentaltemplate padding-dentaltemplate">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="profile-div">
                    <div class="title">
                        <p class="title-description">Dental Office Address</p>
                        <select data-parsley-required data-parsley-required-message= "Select dental office address" name="dentalOfficeId" id="dentalOfficeId" class="selectpicker">
                            <option value="">Select dental office address</option>
                            @foreach ($offices as $office)
                            <option value="{{ $office['id'] }}">{{ $office['address'] }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" value="{{ json_encode($offices) }}" id="officeJson">
                        <input type="hidden" value="add" name="action">
                        <p class="error-div hide">Job cannot be currently created for this location. We will soon be available in your area.</p>
                    </div>
                </div>  
                <div class="job-type">
                    <div class="title job-type">
                        <p class="title-description">Job Type</p>
                        <div class="col-md-3 nopadding">
                            <div class="full-time-box">
                                <input data-parsley-required data-parsley-required-message= "job type required" class="magic-radio" type="radio" name="jobType" id="fulltime" value="{{ \App\Models\RecruiterJobs::FULLTIME }}">
                                <label for="Full Time">
                                    Full Time
                                </label>
                            </div>  
                        </div>
                        <div class="col-md-3 nopadding left-margin-ist">
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
                        <div class="col-md-3 nopadding left-margin-2nd">
                            <div class="full-time-box">
                                <input class="magic-radio" type="radio" name="jobType" id="temporary" value="{{ \App\Models\RecruiterJobs::TEMPORARY }}">

                                <label for="Temporary">
                                    Temporary
                                </label>
                                <input name="tempDates" type="text" id="CoverStartDateOtherPicker" class="date-instance" />
                            </div>  
                        </div>

                    </div>
                </div> 
                <div class="profile-div job-opening hide">
                    <div class="title">
                        <p class="title-description">Total Job Opening</p>
                        <input name="noOfJobs" type="text" id="jobopening" class="form-control" />
                        
<!--                        <select name="noOfJobs" id="jobopening"  class="selectpicker">
                            <option value="2">2</option>
                        </select>-->

                    </div>
                </div>      
            </div>

        </div> 
</form>
@endsection

@section('js')
<script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>
<script src="{{asset('web/scripts/multiple-select.js')}}"></script>
<script src="{{asset('web/scripts/moment.min.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>
        <script>
            //$("form").parsley({ excluded: ":hidden" });
            $('#officeAddress, #jobopening').selectpicker({
                style: 'btn-info'
            });
            $('#CoverStartDateOtherPicker').datepicker({
                multidate: true,
                orientation: "top auto",
                autoclose: false,
            }).on("show", function () {
                $('.datepicker').addClass('custom-active');
                $('.custom-active .datepicker-days').find('th.prev').text('<');
                $('.custom-active .datepicker-days').find('th.next').text('>');
                $('.datepicker .datepicker-days .table-condensed thead').find('.choose-dates').parent().remove();
                $('.datepicker .datepicker-days .table-condensed thead').prepend('<tr><th class="choose-dates" colspan="14">Choose Dates</th></tr>');
            });


            $('.full-time-box label').click(function () {
                //$(this).parent().parent().parent().find('input').attr("checked",false);
                $(this).parent().find('input').prop("checked", true);
                getId = $(this).parent().find('input').attr('id');

                if (getId === 'parttime') {
                    $('#monthSelect').prop('data-parsley-required',true);
                    //$('#jobopening').prop('data-parsley-required-message',"No of jobs required")
                    $('.job-opening').addClass('hide');
                    $('div.select-days-custom').css('display', 'block');
                    $('#monthSelect').multipleSelect({
                        filter: false,
                        isOpen: true,
                        keepOpen: true,
                        selectAll: false,
                        minWidth: 100
                    }).width(300);
                    $(this).parent().parent().find('button span').addClass('placeholder').text('Select Days');
                } else if (getId === 'temporary') {
                    $('div.select-days-custom').css('display', 'none');
                    $('.job-opening').removeClass('hide');
                    $('#jobopening').prop('data-parsley-required',true);
                    $("#CoverStartDateOtherPicker").datepicker("show");
                } else {
                    $('div.select-days-custom').css('display', 'none');
                    $('.job-opening').addClass('hide');
                }
                $("form").parsley().destroy();

                $("form").parsley();
            });
            $(document).on('click', '.select-days-custom div.ms-drop', function (e) {
                $(this).parent().parent().find('button span').addClass('placeholder').text('Select Days');
            });
            $(document).on('click', '.select-days-custom button', function (event) {
                $(this).find('div').addClass('open');
                $(this).parent().find('.ms-drop ').css('display', 'block');
            });
            $('#dentalOfficeId').change(function(){
                $('.error-div').addClass('hide')
                var officeJson = $.parseJSON($('#officeJson').val());
                var dentalOfficeId = $('#dentalOfficeId').val()
                $.each(officeJson,function(index,value){
                    console.log(value);
                    if(dentalOfficeId==value.id && value.zipcode==null){
                        $('.error-div').removeClass('hide');
                        $('#dentalOfficeId').val('');
                    }
                });
            });
        </script> 
@endsection
