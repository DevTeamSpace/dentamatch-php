@extends('web.layouts.dashboard')
@section('css')
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-datepicker.css')}}">
@endsection
@section('content')
  <div class="container padding-container-template">
    <!--breadcrumb-->
    <ul class="breadcrumb">
      <li><a href="{{ url('job/lists') }}">Jobs Listing</a></li>
      <li class="active">Jobs Detail</li>
    </ul>
    <!--/breadcrumb-->

    <!--Job Detail-->
    <section class="job-detail-box">
      <div class="template-job-information mr-t-15">
        <div class="template-job-information-left">
          <h4>{{ $job['jobtitle_name'] }}</h4>
        </div>
        <div class="template-job-information-right">
                    <span>Posted :
                      @if($job['days']>0)
                        {{ $job['days'].' day'.(($job['days']>1)?'s':'') }} ago
                      @else
                        Today
                      @endif
                   </span>
        </div>
      </div>
      <div class="job-type-detail">
        @if($job['job_type']==\App\Enums\JobType::FULLTIME)
          <span class="drk-green statusBtn mr-r-5">Full Time</span>
        @elseif($job['job_type']==\App\Enums\JobType::PARTTIME)
          <span class="bg-ltgreen statusBtn mr-r-5">Part Time</span>
          <span> |
            @php
              $dayArr = [];
              ($job['is_monday']==1)?array_push($dayArr,'Monday'):'';
              ($job['is_tuesday']==1)?array_push($dayArr,'Tuesday'):'';
              ($job['is_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
              ($job['is_thursday']==1)?array_push($dayArr,'Thursday'):'';
              ($job['is_friday']==1)?array_push($dayArr,'Friday'):'';
              ($job['is_saturday']==1)?array_push($dayArr,'Saturday'):'';
              ($job['is_sunday']==1)?array_push($dayArr,'Sunday'):'';
            @endphp
            {{ implode(', ',$dayArr) }}
            </span>
        @else
          <span class="bg-ember statusBtn mr-r-5">Temporary</span>
          <span class="dropdown date-drop">
                @php
                  $dates = explode(',',$job['temp_job_dates']);
                  $seekerDatesCount = count($dates);
                @endphp
            <input type="hidden" id="tempDates" value="{{ $job['temp_job_dates'] }}">
                <a href="#" class=" dropdown-toggle" id="showCalendarProfile"><span
                          class="day-drop">{{ date('l, d M Y',strtotime($dates[0])) }}</span>
                <span class="fa fa-calendar"></span> View All Dates</a>
          <!--                    <ul class="dropdown-menu">
                        @foreach ($dates as $date)
            <li>{{ date('l, d M Y',strtotime($date)) }}</li>
                        @endforeach
                  </ul>-->
                </span>
        @endif
      </div>
      <div class="template-job-information mr-t-30">
        <div class="template-job-information-left j-i-m-l">
          <address>
            <strong>{{ $job['office_name'] }}</strong><br>
            {{ $job['officetype_name'] }}<br>
            {{ $job['address'].' '.$job['zipcode'] }}<br>
            @if($job['job_type']==\App\Enums\JobType::TEMPORARY)
              <span>Number of Candidates Needed: {{ $job['no_of_jobs'] }}</span>
            @endif
            @if($job['pay_rate']!='')
              <span class="payrate-red row"><strong>Hourly Wage Offered: ${{ $job['pay_rate'] }}</strong></span>
            @endif
          </address>


        </div>
        <div class="template-job-information-right j-i-m-r">
          <div class="job-information-detail">
            <div class="search-seeker">
              <a href="{{ url('job/search',[$job['id']]) }}" class="btn btn-primary btn-block">Search Available
                Candidates</a>
              <button type="button" class="btn btn-primary pd-l-30 pd-r-20 btn-block deleteJobModal"
                      data-target="#actionModal" data-toggle="modal" data-job-id="{{ $job['id'] }}">Delete Job Listing
              </button>
                <?php
                $seekerListHiredArray = $seekerListHired->toArray();
                $seekerListInvitedArray = $seekerListInvited->toArray();
                $seekerListSortListedArray = $seekerListSortListed->toArray();
                $seekerListAppliedArray = $seekerListApplied->toArray();
                ?>
              @if($seekerListHiredArray['total'] == 0 && $seekerListInvitedArray['total'] == 0 && $seekerListSortListedArray['total'] == 0 && $seekerListAppliedArray['total'] == 0 )
                <a href="{{ url('job/edit',[$job['id']]) }}" class="btn btn-primary pd-l-30 pd-r-20 btn-block">Edit</a>
              @endif
            </div>


          </div>
        </div>
      </div>
      <div class="template-job-information mr-t-15 width-job-info">
        <div class="job-information-detail ">
          <ul class="job-detail">
            <li>
              <span>Job Description</span>
              <p>{{ $job['template_desc'] }}</p>
            </li>
            <li>
              <span>About Our Office</span>
              <p>{{ $job['office_desc'] }}</p>
            </li>
            @foreach($skills as $skill)
              <li>
                <span>{{ $skill['parent_skill_name'] }}</span>
                <p>{{ $skill['skill_name'] }}</p>
              </li>
            @endforeach
          </ul>

        </div>
      </div>
    </section>


    <!--Job Detail-->
    <div class="job-seeker mr-t-40">
      <label>Invited Candidates</label>
      <div class="jobseeker-border  mr-t-15 mr-b-25"></div>
      @if($seekerListHiredArray['total'] == 0 && $seekerListInvitedArray['total'] == 0 && $seekerListSortListedArray['total'] == 0 && $seekerListAppliedArray['total'] == 0 )
        <div class="text-center">
          <img src="{{ asset('web/images/denta_create_profile.png')}}" alt="create profile">
          <div class="mr-b-10">
            <label class="mr-t-15">You haven’t invited any candidates yet</label>
          </div>
        </div>
      @else
        @if($seekerListHiredArray['total'] > 0)
          <div class="jobseeker-statebox mr-t-25" id="4">
            @include('web.recuriterJob.job-seeker-details',['seekerList'=>$seekerListHired,'status'=>'Hired','totalCount'=>$seekerListHiredArray['total']])
          </div>
        @endif
        @if($seekerListSortListedArray['total'] > 0)
          <div class="jobseeker-statebox mr-t-25" id="3">
            @include('web.recuriterJob.job-seeker-details',['seekerList'=>$seekerListSortListed,'status'=>'Accepted','totalCount'=>$seekerListSortListedArray['total']])
          </div>
        @endif
        @if($seekerListAppliedArray['total'] > 0)
          <div class="jobseeker-statebox mr-t-25" id="2">
            @include('web.recuriterJob.job-seeker-details',['seekerList'=>$seekerListApplied,'status'=>'Applied','totalCount'=>$seekerListAppliedArray['total']])
          </div>
        @endif
        @if($seekerListInvitedArray['total'] > 0)
          <div class="jobseeker-statebox mr-t-25" id="1">
            @include('web.recuriterJob.job-seeker-details',['seekerList'=>$seekerListInvited,'status'=>'Invited to Interview','totalCount'=>$seekerListInvitedArray['total']])
          </div>
        @endif
      @endif
      <div class="mr-t-15 text-center"></div>


      @include('web.chat.chat_modal')


      <div id="actionModal" class="modal fade" role="dialog">
        <div class="modal-dialog custom-modal modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Delete Job</h4>
            </div>
            <div class="modal-body">
              <form action="{{ url('/delete-job') }}" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="jobId" id="jobId" value=""/>
                <input type="hidden" name="requestOrigin" value="web"/>
                <p class="text-center">Do you want to delete this job?</p>
                <div class="mr-t-20 mr-b-30 dev-pd-l-13p">
                  <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary pd-l-30 pd-r-30">Delete</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endsection

      @section('js')
        <script src="{{asset('web/scripts/moment.min.js')}}"></script>
        <script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>
        <script type="text/javascript">
          $(document).ready(function () {
            $('#showCalendarProfile').datepicker({
              format: 'yyyy/mm/dd',
              autoclose: true,
              daysOfWeekDisabled: [0, 1, 2, 3, 4, 5, 6],
            });
            $('#showCalendarProfile').click(function () {
              console.log('ssss');
              var tempDates = $('#tempDates').val().split(',');
              console.log(tempDates);

              $(this).datepicker('setDates', tempDates);
              //['06-05-2018','06-06-2018','06-07-2018']
            });
          })
          var urlFav = "{{ url('recruiter/markFavourite') }}";


          $(".deleteJobModal").click(function () {
            jobId = $(this).data('jobId');
            $("#jobId").val(jobId);
          });


          $(function () {
            $('body').on('click', '.pagination a', function (e) {
              e.preventDefault();

              $('#load a').css('color', '#dfecf6');
              $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

              var url = $(this).attr('href');
              var status = this.href.substr(this.href.lastIndexOf('/') + 1).split('?')[0];

              getArticles(url, status);
            });

            function getArticles(url, status) {
              $.ajax({
                url: url
              }).done(function (data) {
                $('#' + status).html(data);
              }).fail(function () {

              });
            }
          });

        </script>
        <script src="{{asset('web/scripts/jobdetail.js')}}"></script>
@endsection

