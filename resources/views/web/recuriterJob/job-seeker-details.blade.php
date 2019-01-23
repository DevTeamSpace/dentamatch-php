<label class="fnt-16 textcolr-38">Candidates {{ $status }} ({{ $totalCount }})</label>
@foreach($seekerList as $seeker)
        @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY)
        @include('web.recuriterJob.rating-modal')
        @endif
        <div class="media jobCatbox">
            <div class="media-left ">
                <div class="img-holder ">
                    <img class="media-object img-circle" src="{{ url("image/66/66/?src=" .$seeker['profile_pic']) }}" alt="...">
                    @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY)
                    <span id="fav_{{ $seeker['seeker_id'] }}" onclick="markFavourite({{ $seeker['seeker_id'] }});" class="star {{ ($seeker['is_favourite']==null)?'star-empty':'star-fill' }}"></span>
                    @endif
                </div>
            </div>
            <div class="media-body row">
                <div class="col-sm-7 pd-t-10 ">
                    <div >
                        <a href="{{ url('job/seekerdetails/'.$seeker['seeker_id'].'/'.$job['id']) }}" class="algn-rel media-heading">{{ $seeker['first_name'].' '.$seeker['last_name'] }}</a>
                         <strong>({{ number_format($seeker['percentaSkillsMatch'],2) }}%)</strong>
                        @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY)
                        <span class="mr-l-15 dropdown date_drop">
                            @if(!empty($seeker['avg_rating']))
                            <span class=" dropdown-toggle label label-success" data-toggle="dropdown">{{ number_format($seeker['avg_rating'], 1, '.', '') }}</span>
                            @else
                            <span class=" dropdown-toggle label label-success">Not Yet Rated</span>
                            @endif
                            <ul class="dropdown-menu rating-info">
                                <li><div class="rating_on"> Punctuality <span class="ex-text">(Did they show up & were they on time?)</span></div>
                                    <ul class="rate_me">
                                        <?php $punctuality = round($seeker['avg_punctuality'],1);?>
                                        <li ><span {{ (!empty($punctuality)) ? (floor($punctuality)>=1 ? "class=bg-green" : "") : "" }}></span></li>
                                        <li><span {{ (!empty($punctuality)) ? (floor($punctuality)>=2 ? "class=bg-green" : "") : "" }}></span></li>
                                        <li><span {{ (!empty($punctuality) && floor($punctuality)>=3) ? "class=bg-green" : "" }}></span></li>
                                        <li ><span {{ (!empty($punctuality) && floor($punctuality)>=4) ? "class=bg-green" : "" }}></span></li>
                                        <li><span {{ (!empty($punctuality) && floor($punctuality)>=5) ? "class=bg-green" : "" }} ></span></li>
                                        <li class="last-child-css">{{ $punctuality }}</li>
                                    </ul>
                                </li>
                                <li><div class="rating_on"> Work performance <span class="ex-text">(Were they efficient? Were they a team player?)</span></div>
                                    <ul class="rate_me">
                                        <?php $timeManagement = round($seeker['avg_time_management'],1);?>
                                        <li ><span {{ (!empty($timeManagement)) ? (floor($timeManagement)>=1 ? "class=bg-ember" : "") : "" }}></span></li>
                                        <li><span {{ (!empty($timeManagement)) ? (floor($timeManagement)>=2 ? "class=bg-ember" : "") : "" }}></span></li>
                                        <li><span {{ (!empty($timeManagement) && floor($timeManagement)>=3) ? "class=bg-ember" : "" }}></span></li>
                                        <li ><span {{ (!empty($timeManagement) && floor($timeManagement)>=4) ? "class=bg-ember" : "" }}></span></li>
                                        <li><span {{ (!empty($timeManagement) && floor($timeManagement)>=5) ? "class=bg-ember" : "" }} ></span></li>
                                        <li class="last-child-css">{{ $timeManagement }}</li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="rating_on">  Skill & Aptitude <span class="ex-text">(Were the clinical skill on point? Was the candidate engaging with the patients and other members of the staff?)</span></div>
                                    <ul class="rate_me">
                                        <?php $skillsRating = round($seeker['avg_skills'],1);?>
                                        <li ><span {{ (!empty($skillsRating)) ? (floor($skillsRating)>=1 ? "class=bg-red" : "") : "" }}></span></li>
                                        <li><span {{ (!empty($skillsRating)) ? (floor($skillsRating)>=2 ? "class=bg-red" : "") : "" }}></span></li>
                                        <li><span {{ (!empty($skillsRating) && floor($skillsRating)>=3) ? "class=bg-red" : "" }}></span></li>
                                        <li><span {{ (!empty($skillsRating) && floor($skillsRating)>=4) ? "class=bg-red" : "" }}></span></li>
                                        <li><span {{ (!empty($skillsRating) && floor($skillsRating)>=5) ? "class=bg-red" : "" }}></span></li>
                                        <li class="last-child-css">{{ $skillsRating }}    </li>
                                    </ul>
                                </li>
                            </ul>
                        </span>
                        @endif
                    </div>
                    <p class="nopadding">{{ $seeker['jobtitle_name'] }}</p>
                    <p class="nopadding">
                        <?php
                        
                        $date = "";
                        if(isset($seeker->hired_job_dates) && $seeker->hired_job_dates != ""){
                            
                            $dateArray = explode(",",$seeker->hired_job_dates);
                            $newDateArray = [];
                            foreach($dateArray as $date){
                                $newDateArray[] = date('D, M d, Y',strtotime($date));
                            }
                            
                            $date = implode(" & ",$newDateArray);
                        }
                        echo $date;
                        ?>
                    </p>
                    @if($seeker['job_type']==\App\Models\RecruiterJobs::PARTTIME)
                    <p  class="nopadding">
                        <!--<span class="bg-ember statusBtn mr-r-5">Part time</span>-->
                        @php 
                        $seekerDayArr = [];
                        ($seeker['is_parttime_monday']==1)?array_push($seekerDayArr,'Monday'):'';
                        ($seeker['is_parttime_tuesday']==1)?array_push($seekerDayArr,'Tuesday'):'';
                        ($seeker['is_parttime_wednesday']==1)?array_push($seekerDayArr,'Wednesday'):'';
                        ($seeker['is_parttime_thursday']==1)?array_push($seekerDayArr,'Thursday'):'';
                        ($seeker['is_parttime_friday']==1)?array_push($seekerDayArr,'Friday'):'';
                        ($seeker['is_parttime_saturday']==1)?array_push($seekerDayArr,'Saturday'):'';
                        ($seeker['is_parttime_sunday']==1)?array_push($seekerDayArr,'Sunday'):'';
                        @endphp
                        {{ implode(', ',$seekerDayArr) }}
                    </p>
                    @endif
                    @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY && $seeker['temp_job_dates']!=null)
                    <p  class="nopadding">
                        <!--<span class="bg-ember statusBtn mr-r-5">Temporary</span>-->
                        <span class="dropdown date-drop">
                            @php 
                            $seekerDates = explode(',',$seeker['temp_job_dates']);
                            @endphp
                            <span class="dropdown-toggle"  data-toggle="dropdown">
                                <span class="day-drop">{{ date('l, d M Y',strtotime($seekerDates[0])) }}</span>
                                <span class="caret"></span></span>
                                <ul class="dropdown-menu">
                                    @foreach ($seekerDates as $sdate)
                                    <li>{{ date('l, d M Y',strtotime($sdate)) }}</li>
                                    @endforeach
                                </ul>
                            </span>
                        </p>
                        @endif
                    </div>
                    <div class="col-sm-5 pd-t-5 text-right">
                        <?php 
                            $key = $seeker['applied_status'];
                        ?>
                        <form action="{{ url('job/updateStatus') }}" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="jobId" value="{{ $job['id'] }}">
                            <input type="hidden" name="seekerId" value="{{ $seeker['seeker_id'] }}">
                            <input type="hidden" name="jobType" value="{{ $seeker['job_type'] }}">
                            @if($key==\App\Models\JobLists::HIRED)
                            @if($seeker['seeker_block']==0 && $seeker['recruiter_block']==0)
                            <button type="button" class="modalClick btn btn-primary pd-l-30 pd-r-30 mr-r-5" data-toggle="modal" 
                            data-target="#ShortListMessageBox" data-seekerId="{{ $seeker['seeker_id'] }}">Message</button>
                            @endif
                            @if($seeker['job_type']==App\Models\RecruiterJobs::TEMPORARY && ($seeker['ratingId']!=$seeker['seeker_id']))
                            @if(!empty($dates) && date("Y-m-d")>$dates[$seekerDatesCount-1])
                            <button type="button" class="btn  btn-primary-outline active pd-l-30 pd-r-30 " data-toggle="modal" data-target="#ratesekeerPopup_{{ $seeker['seeker_id'] }}">Leave a Rating</button>
                            @else
                            <button type="button" class="btn btn-primary-outline pd-l-30 pd-r-30">Leave a Rating</button>
                            @endif
                            @endif
                            @elseif($key==\App\Models\JobLists::SHORTLISTED)
                            @if($seeker['seeker_block']==0 && $seeker['recruiter_block']==0)
                            <button type="button" class="modalClick btn btn-primary pd-l-30 pd-r-30" data-toggle="modal" 
                            data-target="#ShortListMessageBox" data-seekerId="{{ $seeker['seeker_id'] }}">Message</button>
                            @endif
                            <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::HIRED }}" class="btn btn-primary pd-l-30 pd-r-30 ">Hire</button>
                            @elseif($key==\App\Models\JobLists::APPLIED)
                            <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::REJECTED }}" class="btn btn-link  mr-r-5">Reject</button>
                            <button type="submit" name="appliedStatus" value="{{ \App\Models\JobLists::SHORTLISTED }}" class="btn btn-primary pd-l-30 pd-r-30 ">Interviewing</button>
                            @elseif($key==\App\Models\JobLists::INVITED)
                            <button type="button" class="btn btn-primary-outline pd-l-30 pd-r-30 ">Invited</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        {{ $seekerList->links() }}
            