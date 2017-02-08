@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/style.css')}}">
<link rel="stylesheet" href="{{asset('web/plugins/range-slider/css/bootstrap-slider.css')}}">
@endsection
@section('content')

<div class="container padding-container-template ">
    <!--breadcrumb-->
    <ul class="breadcrumb ">
        <li><a href="#">Jobs Listing</a></li>
        <li class=""><a href="#">Jobs Detail</a></li>
        <li class="active">Search Preference</li>
    </ul>
    <!--/breadcrumb-->
<?php //dd($jobDetails); ?>
    
    <div class="col-sm-12 mr-b-55">
        <div class="row section-title mr-b-10">Search Preference</div>
        <div class="jobCatbox row searchpreference">
            <div class="col-sm-4 mr-b-5">
                <label class="fnt-16 nopadding">Job Title</label>
                <h4 class="textcolr-38 nopadding"><strong>{{$jobDetails->jobtitle_name}}</strong></h4>
            </div>
            <div class="col-sm-3 mr-b-5">
              <label class="fnt-16 nopadding">Job Type</label>
              <h4 class="textcolr-38 nopadding">
                @if($jobDetails->job_type==\App\Models\RecruiterJobs::FULLTIME)
                <strong>Full Time</strong>
                @elseif($jobDetails->job_type==\App\Models\RecruiterJobs::PARTTIME)
                <strong>Part Time</strong>
                @else
                <strong>Temporary</strong>
                @endif
              </h4>
            </div>
            <div class="col-sm-5">
              <label class="fnt-16 nopadding">Radius</label>
              <div >
                <input type="hidden" name="page" value="{{$searchData['page']}}"/>
                <input type="hidden" name="slider_val" id="slider_val" value="{{$searchData['distance']}}"/>
                <input id="range_slider" type="text"/>
                <span class="pull-left">1 mile</span>
                <span class="pull-right">20 miles</span>
              </div>
            </div>
        </div>
    </div>

    <div class="row sec-mob">
    <div class="col-sm-6 mr-b-10 col-xs-6">
        <div class="section-title">{{$seekersList->total()}} Results Found</div>
    </div>
    <div class="col-sm-6 text-right mr-b-10 col-xs-6">
        <button type="button" class="btn btn-primary-outline ">Available all days </button>
    </div>
    </div>

    @if(count($seekersList)>0)
        <div class="jobseeker-statebox">
            @include('web.recuriterJob.seekersData')
        </div>
    @else
    <div class="jobCatbox mr-b-20">
        <div class="template-job-information ">
            <div class="template-job-information-left">
                <h4>Nothing to show</h4>
            </div>
        </div>  
    </div>
    @endif    
</div>


@endsection

@section('js')
<script src ="{{asset('web/plugins/range-slider/js/bootstrap-slider.js')}}"></script>
<script type="text/javascript">

    $(function () {
        $('body').on('click', '.pagination a', function (e) {
            e.preventDefault();

            $('#load a').css('color', '#dfecf6');
            $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');
            getArticles(url);
            window.history.pushState("", "", url);
        });

        /*-----------range slider--------*/
        $("#range_slider").slider({ 
            min: 1, 
            max: 20, 
            value: $('#slider_val').val(), 
            tooltip_position:'bottom',
            formatter: function(value) {
                return   value + ' miles ' ;
            },
            change: function(event, ui) {
                if (event.originalEvent) {
                    alert('manual: ' + ui.value);
                }
                else {
                    alert('programmatic: ' + ui.value);
                }
            }
        });
        /*-----------range slider--------*/


        function getArticles(url) {
            $.ajax({
                url: url
            }).done(function (data) {
                $('#ajaxData').html(data);
            }).fail(function () {

            });
        }
    });
</script>
@endsection
