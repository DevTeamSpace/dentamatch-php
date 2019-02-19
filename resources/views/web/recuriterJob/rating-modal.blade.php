<!-- Modal -->
<div id="ratesekeerPopup_{{ $seeker['seeker_id'] }}" class="modal fade " role="dialog">
  <div class="modal-dialog custom-modal popup-wd522">
    <!-- Modal content-->
    <div class="modal-content">
      <form action="{{ url('recruiter/rating') }}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="recruiter_job_id" value="{{ $job['id'] }}">
        <input type="hidden" name="seeker_id" value="{{ $seeker['seeker_id'] }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Rate Candidate</h4>
        </div>
        <div class="modal-body ">
          <div class="media nopadding">
            <div class="media-left ">
              <div class="img-holder pos-rel">
                <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}"
                     alt="...">
                <span class="star {{ ($seeker['is_favourite']==null)?'star-empty':'star-fill' }}"></span>
              </div>
            </div>
            <div class="media-body row">
              <div class="col-sm-8 pd-t-10 ">
                <div>
                  <a href="#" class="media-heading">{{ $seeker['first_name'].' '.$seeker['last_name'] }}</a>
                  @if(!empty($seeker['avg_rating']))
                    <span class=" dropdown-toggle label label-success"
                          data-toggle="dropdown">{{ number_format($seeker['avg_rating'], 1, '.', '') }}</span>
                  @else
                    <span class=" dropdown-toggle label label-success">Not Yet Rated</span>
                  @endif
                </div>
                <p class="nopadding">{{ $job['jobtitle_name'] }}</p>
                @php
                  $dates = explode(',',$job['temp_job_dates']);
                @endphp
                <p class="nopadding">
                  @foreach ($dates as $date)
                    {{ date('l, d M Y',strtotime($date)) }},
                  @endforeach
                </p>
              </div>
            </div>
          </div>
          <div class="mr-t-40 text-center">
            <p>Did <span>{{ $seeker['first_name'].' '.$seeker['last_name'] }}</span> show up for the job?</p>
            <div class="text-primary">
              <button type="button" id="rating-yes_{{ $seeker["seeker_id"] }}" class=" nopadding btn-link "
                      data-toggle="modal" data-target="#ratesekeer-rating_{{ $seeker['seeker_id'] }}">Yes
              </button>
              /
              <button type="submit" id="rating-no_{{ $seeker['seeker_id'] }}" class=" nopadding btn-link ">No</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="ratesekeer-rating_{{ $seeker['seeker_id'] }}" class="modal fade " role="dialog">
  <div class="modal-dialog custom-modal popup-wd522">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Rate Candidate</h4>
      </div>
      <div class="modal-body ">
        <form action="{{ url('recruiter/rating') }}" method="post">
          {!! csrf_field() !!}
          <input type="hidden" name="recruiter_job_id" value="{{ $job['id'] }}">
          <input type="hidden" name="seeker_id" value="{{ $seeker['seeker_id'] }}">
          <div class="media nopadding">
            <div class="media-left ">
              <div class="img-holder pos-rel">
                <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}"
                     alt="...">
                <span class="star {{ ($seeker['is_favourite']==null)?'star-empty':'star-fill' }}"></span>
              </div>
            </div>
            <div class="media-body row">
              <div class="col-sm-8 pd-t-10 ">
                <div>
                  <a href="#" class="media-heading">{{ $seeker['first_name'].' '.$seeker['last_name'] }}</a>
                  <span class="mr-l-5 label label-success">{{ ($seeker['avg_rating']!='')?round($seeker['avg_rating'],1): 'Not Yet Rated' }}</span>
                </div>
                <p class="nopadding">{{ $job['jobtitle_name'] }}</p>
                @php
                  $dates = explode(',',$job['temp_job_dates']);
                @endphp
                <p class="nopadding">
                  @foreach ($dates as $date)
                    {{ date('l, d M Y',strtotime($date)) }},
                  @endforeach
                </p>
              </div>
            </div>
          </div>
          <ul class="mr-t-40 rating-box">
            <li class="row">
              <div class="col-sm-6">
                <div class="rating_on"> Punctuality<span class="ex-text">(Did they show up & were they on time?)</span>
                </div>
              </div>
              <div class="col-sm-6 ">
                <input type="text" name="punctuality" id="punctuality_{{ $seeker["seeker_id"] }}">
                <label class="total-count ">/5</label>
              </div>
            </li>
            <li class="row">
              <div class="col-sm-6 ">
                <div class="rating_on"> Work performance <span class="ex-text">(Were they efficient? Were they a team player?)</span>
                </div>
              </div>
              <div class="col-sm-6 ">
                <input type="text" name="time_management" id="time-manage_{{ $seeker["seeker_id"] }}">
                <label class="total-count ">/5</label>
              </div>
            </li>
            <li class="row">
              <div class="col-sm-6">
                <div class="rating_on"> Skill & Aptitude <span class="ex-text">(Were the clinical skill on point? Was the candidate engaging with the patients and other members of the staff?)</span>
                </div>
              </div>
              <div class="col-sm-6 ">
                <input type="text" name="skills" id="personal-skill_{{ $seeker["seeker_id"] }}">
                <label class="total-count ">/5</label>
              </div>
            </li>
          </ul>
          <div class="mr-t-20 text-right">
            <button type="submit" class=" btn btn-primary pd-l-30 pd-r-30 ">Rate</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Fav Modal -->
<div id="favsekeerPopup_{{ $seeker['seeker_id'] }}" class="modal fade " role="dialog">
  <div class="modal-dialog custom-modal popup-wd522">
    <!-- Modal content-->
    <div class="modal-content">
      {!! csrf_field() !!}
      <input type="hidden" name="recruiter_job_id" value="{{ $job['id'] }}">
      <input type="hidden" name="seeker_id" value="{{ $seeker['seeker_id'] }}">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Favorite Candidate</h4>
      </div>
      <div class="modal-body ">
        <div class="media nopadding">
          <div class="media-left ">
            <div class="img-holder pos-rel">
              <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}"
                   alt="...">
              <span class="star {{ ($seeker['is_favourite']==null)?'star-empty':'star-fill' }}"></span>
            </div>
          </div>
          <div class="media-body row">
            <div class="col-sm-8 pd-t-10 ">
              <div>
                <a href="#" class="media-heading">{{ $seeker['first_name'].' '.$seeker['last_name'] }}</a>
                @if(!empty($seeker['avg_rating']))
                  <span class=" dropdown-toggle label label-success"
                        data-toggle="dropdown">{{ number_format($seeker['avg_rating'], 1, '.', '') }}</span>
                @else
                  <span class=" dropdown-toggle label label-success">Not Yet Rated</span>
                @endif
              </div>
              <p class="nopadding">{{ $job['jobtitle_name'] }}</p>
              @php
                $dates = explode(',',$job['temp_job_dates']);
              @endphp
              <p class="nopadding">
                @foreach ($dates as $date)
                  {{ date('l, d M Y',strtotime($date)) }},
                @endforeach
              </p>
            </div>
          </div>
        </div>
        <div class="mr-t-40 text-center">
          <hr>
          <p>Do you want to favorite <span>{{ $seeker['first_name'].' '.$seeker['last_name'] }}</span>?</p>
          <div class="text-primary">
            <button type="button" onclick="markFavourite({{ $seeker['seeker_id'] }});"
                    id="fav-yes_{{ $seeker["seeker_id"] }}" class=" nopadding btn-link ">Yes
            </button>
            /
            <button type="submit" id="fav-no_{{ $seeker['seeker_id'] }}" class=" nopadding btn-link ">No</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Fav Modal -->
<script type="text/javascript">
  {{--$(document).ready(function () {--}}
    {{--$('#favsekeerPopup_22').modal('show');--}}
    {{--var spanClassFav = $('#favsekeerPopup_{{ old("tab") }}').find('span.star').hasClass('star-empty');--}}
    {{--if (spanClassFav == true) {--}}
      {{--$('#favsekeerPopup_{{ old("tab") }}').modal('show');--}}
    {{--}--}}
  {{--});--}} // todo why?
  $('#punctuality_{{ $seeker["seeker_id"] }}').rating({
    icon: '',
    color: '#fff',
    colorHover: '#05c410',
    inline: true,
    showLabel: true,
    validationMessage: 'Oops! Please rate us!'
  }).change(function () {
    $('#punctuality_{{ $seeker["seeker_id"] }}').val($(this).val());
  });

  $('#time-manage_{{ $seeker["seeker_id"] }}').rating({
    icon: '',
    color: '#fff',
    colorHover: '#ffce57',
    inline: true,
    showLabel: true,
    validationMessage: 'Oops! Please rate us!'
  }).change(function () {
    $('#time-manage_{{ $seeker["seeker_id"] }}').val($(this).val());
  });

  $('#personal-skill_{{ $seeker["seeker_id"] }}').rating({
    icon: '',
    color: '#fff',
    colorHover: '#ff6565',
    inline: true,
    showLabel: true,
    validationMessage: 'Oops! Please rate us!'
  }).change(function () {
    $('#personal-skill_{{ $seeker["seeker_id"] }}').val($(this).val());
  });

  $('#fav-yes_{{ $seeker["seeker_id"] }}').click(function () {
    $('#favsekeerPopup_{{ $seeker["seeker_id"] }}').modal('hide');
    $('#favbutton_{{ $seeker["seeker_id"] }}').hide();
  });

  $('#fav-no_{{ $seeker["seeker_id"] }}').click(function () {
    $('#favsekeerPopup_{{ $seeker["seeker_id"] }}').modal('hide');
  });

  $('#rating-yes_{{ $seeker["seeker_id"] }}').click(function () {
    $('#ratesekeerPopup_{{ $seeker["seeker_id"] }}').modal('hide');
  });

  $('#rating-no_{{ $seeker["seeker_id"] }}').click(function () {
    seekerId = {{ $seeker['seeker_id'] }};
    jobId = {{ $job['id'] }};
    $('#ratesekeerPopup_{{ $seeker["seeker_id"] }}').modal('hide');
  });
</script>