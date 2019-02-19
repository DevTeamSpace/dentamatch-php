@extends('web.layouts.dashboard')

@section('content')
  @php
    $datesTemp = explode(',',$jobDetails['temp_job_dates']);
    $seekerDatesCount = count($datesTemp);
  @endphp

  <div class="container padding-container-template">
    <!--breadcrumb-->
    <ul class="breadcrumb">
      <li><a href="{{ url('job/lists') }}">Jobs Listing</a></li>
      <li><a href="{{ url('job/details/'.$jobId) }}">Jobs Detail</a></li>
      <li><a href="{{ url('job/search/'.$jobId) }}">Search Results</a></li>
      <li class="active">{{$seekerDetails['first_name'].' '.$seekerDetails['last_name']." Profile"}}</li>
    </ul>
    <!--/breadcrumb-->


    <div class="commonBox">
      <div class="row resultHeading">
        <div class="col-md-2 col-sm-2 resultImage">
          <img src="{{ url('image/120/120/?src=' .$seekerDetails['profile_pic']) }}" class="img-circle">
        </div>
        <div class="col-md-7 col-sm-6">
          <div>{{ number_format($matchedSkills['percentSkills'],2) }}% Match</div>
          <span class=" dropdown date_drop">
            <h4 class="next-rate">{{$seekerDetails['first_name'].' '.$seekerDetails['last_name']}}</h4>

            @include('web.recuriterJob.partial.seeker-ratings', [
                    'avgRating' => $seekerDetails['sum'],
                    'punctuality' => $seekerDetails['punctuality'],
                    'timeManagement' => $seekerDetails['time_management'],
                    'skills' => $seekerDetails['avgskills']
            ])

          </span>
          <h6>{{$seekerDetails['jobtitle_name']}}</h6>

          @include('web.recuriterJob.partial.seeker-timetable')

        </div>
        <form action="{{ url('job/updateStatus') }}" method="post">
          <div class="col-md-3 text-right">
            {!! csrf_field() !!}
            <input type="hidden" name="jobId" value="{{ $jobId }}">
            <input type="hidden" name="seekerId" value="{{ $seekerDetails['user_id'] }}">
            <input type="hidden" name="jobType" value="{{ $jobDetails['job_type'] }}">
            @if($seekerDetails['applied_status'] == \App\Enums\JobAppliedStatus::INVITED)
              <h6>INVITED</h6>
            @elseif($seekerDetails['applied_status'] == \App\Enums\JobAppliedStatus::APPLIED)
              <h6></h6>
              <button type="submit" name="appliedStatus" value="{{ \App\Enums\JobAppliedStatus::REJECTED }}"
                      class="btn btn-primary pd-l-20 pd-r-20">Reject
              </button>
              <button type="submit" name="appliedStatus" value="{{ \App\Enums\JobAppliedStatus::SHORTLISTED }}"
                      class="btn btn-primary pd-l-20 pd-r-20">Accept
              </button>
            @elseif($seekerDetails['applied_status'] == \App\Enums\JobAppliedStatus::SHORTLISTED)
              <h6>INTERVIEWING</h6>
              <button type="button" class="modalClick btn btn-primary pd-l-30 pd-r-30 " data-toggle="modal"
                      data-target="#ShortListMessageBox" data-seekerId="{{ $seekerDetails['user_id'] }}">Message
              </button>
              <button type="submit" name="appliedStatus" value="{{ \App\Enums\JobAppliedStatus::HIRED }}"
                      class="btn btn-primary pd-l-20 pd-r-20">Hire
              </button>
            @elseif($seekerDetails['applied_status'] == \App\Enums\JobAppliedStatus::HIRED)
              <h6>HIRED</h6>
              <button type="button" class="modalClick btn btn-primary pd-l-30 pd-r-30 mr-t-30 " data-toggle="modal"
                      data-target="#ShortListMessageBox" data-seekerId="{{ $seekerDetails['user_id'] }}">Message
              </button>
            @elseif (!empty($datesTemp) && date("Y-m-d")>$datesTemp[$seekerDatesCount-1] && $jobDetails['job_type']==\App\Enums\JobType::TEMPORARY)
              <h6>TEMP JOB EXPIRED</h6>
            @else
              <button type="submit" name="appliedStatus" value="{{ \App\Enums\JobAppliedStatus::INVITED }}"
                      class="btn btn-primary pd-l-30 pd-r-30">Invite
              </button>
            @endif
          </div>
        </form>
      </div>
    </div>

    <div class="commonBox mr-t-20 mr-b-20">
      <div class="skillsBlock">
        <h5>MATCHING SKILLS ({{ number_format($matchedSkills['percentSkills'],2) }}%)</h5>
        <h6>Matched skills are in bold</h6>
        @if(!empty($matchedSkills['data']))
          @foreach($matchedSkills['data'] as $keySki => $skillArray)
            <ul class="skill_List">
              <li>{{ $keySki }}</li>
              @if(!empty($skillArray))
                <li>
                  @foreach($skillArray as $skillArrayList)
                    @if($skillArrayList['matchedFlag'] == 1)
                      <strong>
                        {{ $skillArrayList['skill_name'] }},
                      </strong>
                    @else
                      {{ $skillArrayList['skill_name'] }},
                    @endif
                  @endforeach
                </li>
              @endif
            </ul>
          @endforeach
        @endif
        @if($jobDetails['pay_rate']!='')
          <ul class="skill_List">
            <li class="payrate-red"><strong>Hourly Wage Offered: ${{ $jobDetails['pay_rate'] }}</strong></li>
          </ul>
        @endif
      </div>
    </div>

    <div class="commonBox">
      <div class="">
        <div class="leftCircle">
          <div class="searchResultHeading">
            <h5>ABOUT ME</h5>
            <p>{{$seekerDetails['about_me']}}</p>
          </div>

          <div class="searchResultHeading pd-t-20">
            <h5>LOCATION</h5>
            <p>{{$seekerDetails['preferred_location_name']}}</p>
          </div>

          <div class="searchResultHeading pd-t-20">
            <h5>PAST WORK EXPERIENCE</h5>
            @if(!empty($seekerDetails['experience']))
              @foreach($seekerDetails['experience'] as $experience)
                <div class="row">
                  <div class="col-sm-4 exprience">
                    <dl>
                      <dt>
                        <div class="expTitle">{{$experience['jobtitle_name']}}
                          <span>({{(round($experience['months_of_expereince']/12,0)!=0?round($experience['months_of_expereince']/12,0)." year":"")." ".(round($experience['months_of_expereince']%12,0)!=0?round($experience['months_of_expereince']%12,0)." month":"")}}
                            )</span>
                        </div>
                        {{$experience['office_name']}}
                      </dt>
                      <dd>{{$experience['office_address']}}</dd>
                      <dd>{{$experience['city']}}</dd>
                    </dl>
                  </div>
                  @if(!empty($experience['reference1_name']))
                    <div class="col-sm-4 exprience">
                      <dl>
                        <dt>
                          <div class="expTitle">Reference 1</div>
                          {{$experience['reference1_name']}}
                        </dt>
                        <dd>{{$experience['reference1_mobile']}}</dd>
                        <dd>{{$experience['reference1_email']}}</dd>
                      </dl>
                    </div>
                  @endif
                  @if(!empty($experience['reference2_name']))
                    <div class="col-sm-4 exprience">
                      <dl>
                        <dt>
                          <div class="expTitle">Reference 2</div>
                          {{$experience['reference2_name']}}
                        </dt>
                        <dd>{{$experience['reference2_mobile']}}</dd>
                        <dd>{{$experience['reference2_email']}}</dd>
                      </dl>
                    </div>
                  @endif
                </div>

              @endforeach
            @endif
          </div>


          <div class="searchResultHeading pd-t-20">
            <h5>EDUCATION / TRAINING </h5>
            @if(!empty($seekerDetails['schoolings']))
              @foreach($seekerDetails['schoolings'] as $schoolings)
                <div class="pd-t-10 keySkills">
                  <b>{{$schoolings['school_title']." (".$schoolings['year_of_graduation'].")"}}</b>
                  <p>{{$schoolings['school_name']}}</p>
                </div>
              @endforeach
            @endif
          </div>

          <div class="searchResultHeading pd-t-20">
            <h5>KEY SKILLS & TECHNOLOGY EXPERIENCE</h5>
          </div>

          @if(!empty($seekerDetails['skills']))
            @foreach($seekerDetails['skills'] as $skills)
              <div class="pd-t-10 keySkills">
                @if($skills['skill_name'] == 'Other' || $skills['skill_name'] == 'other')
                  <b>{{$skills['skill_name']}}</b>
                  <p>{{$skills['other_skill']}}</p>
                @else
                  <b>{{$skills['skill_title']}}</b>
                  <p>{{$skills['skill_name']}}</p>
                @endif
              </div>
            @endforeach
          @endif
            <?php
            $affiliations = explode(',', $seekerDetails['affiliations']);
            if(count($affiliations) > 0){
            ?>

          <div class="searchResultHeading pd-t-20">
            <h5>AFFILIATIONS</h5>
            <ul class="job-detail-listing bullet-list">
              @foreach($affiliations as $affiliation)
                <li>
                  @if(trim($affiliation) == "Other")
                    {{ $affiliation }} -- {{ $seekerDetails['other_affiliation'] }}
                  @else
                    {{ $affiliation }}
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
            <?php } ?>
          @if($seekerDetails['applied_status'] == \App\Enums\JobAppliedStatus::HIRED)
            @if(!empty($seekerDetails['certificate']))
              @foreach($seekerDetails['certificate'] as $certificate)
                <div class="searchResultHeading pd-t-20 smallSquare">
                  <h5>{{$certificate['certificate_name']}}</h5>
                  <p>
                    <a href="javascript:void(0)">
                      <img data-toggle="modal" data-target="#certificateModal" class="img-rounded thumb-certificate"
                           data-image="{{ $certificate['image_path'] }}"
                           src="{{ url('image/66/66/?src='.$certificate['image_path']) }}">
                    </a>
                    @if(!empty($certificate['validity_date']))
                      Valid Till: <span>{{date('M d, Y',strtotime($certificate['validity_date']))}}</span></p>
                  @else
                    Valid Till: N/A
                  @endif
                </div>
              @endforeach
            @endif
          @endif
        </div>
      </div>
    </div>

  </div>

  <!--  Modal content for the mixer image example -->
  <div id="certificateModal" class="modal fade" role="dialog">
    <div class="modal-dialog custom-modal popup-wd522">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Certificate</h4>
        </div>
        <div class="modal-body">
          <img id="certificateModalImg" class="img-rounded img-responsive" src="">

        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal mixer image -->

  @include('web.chat.chat_modal')

@endsection

@section('js')
  <script type="text/javascript">
    $('.thumb-certificate').click(function () {
      var imgUrl = "{{ url('image/550/500/?src=') }}";
      $('#certificateModal').modal({show: true});
      $('#certificateModalImg').attr('src', imgUrl + $(this).data('image'));
      return false;
    });

  </script>
  <script src="{{ asset('web/scripts/jobdetail.js') }}"></script>
@endsection
