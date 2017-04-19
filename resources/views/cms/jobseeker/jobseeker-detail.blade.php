@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Jobseeker Details</div>
                <div class="panel-body">
                    <div class="form-group">
                        
                        <table class="table table-user-information">
                            <tbody>
                                
                                <tr>
                                    <td>First Name</td>
                                    <td>{{ $seekerDetails['first_name'] }}</td>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <td>{{ $seekerDetails['last_name'] }}</td>
                                </tr>
                                
                                @if(!empty($seekerDetails['jobtitle_name']))
                                <tr>
                                    <td>Job Title</td>
                                    <td>{{ $seekerDetails['jobtitle_name'] }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td> Job Availability</td>
                                    <td>
                                        

                                        @if($seekerDetails['is_fulltime'])
                                        <span >Full Time</span><br />
                                        @endif
                                        @if($seekerDetails['is_parttime_monday'] || $seekerDetails['is_parttime_tuesday'] || $seekerDetails['is_parttime_wednesday'] || $seekerDetails['is_parttime_thursday'] || $seekerDetails['is_parttime_friday'] || $seekerDetails['is_parttime_saturday'] || $seekerDetails['is_parttime_sunday'])
                                        <span >Part Time</span>
                                        <span> | 
                                            @php 
                                            $dayArr = [];
                                            ($seekerDetails['is_parttime_monday']==1)?array_push($dayArr,'Monday'):'';
                                            ($seekerDetails['is_parttime_tuesday']==1)?array_push($dayArr,'Tuesday'):'';
                                            ($seekerDetails['is_parttime_wednesday']==1)?array_push($dayArr,'Wednesday'):'';
                                            ($seekerDetails['is_parttime_thursday']==1)?array_push($dayArr,'Thursday'):'';
                                            ($seekerDetails['is_parttime_friday']==1)?array_push($dayArr,'Friday'):'';
                                            ($seekerDetails['is_parttime_saturday']==1)?array_push($dayArr,'Saturday'):'';
                                            ($seekerDetails['is_parttime_sunday']==1)?array_push($dayArr,'Sunday'):'';
                                            @endphp
                                            {{ implode(', ',$dayArr) }}
                                        </span><br />
                                        @endif
                                        @if($seekerDetails['temp_job_dates'])
                                        
                                        <span >Temporary |</span>
                                        <span class="dropdown date-drop">
                                            @php 
                                            $dates = explode(' | ',$seekerDetails['temp_job_dates']);
                                            @endphp
                                            <a href="#" class=" dropdown-toggle"  data-toggle="dropdown">
                                                <span class="day-drop">{{ date('l, d M Y',strtotime($dates[0])) }}</span>
                                                <span class="caret"></a>
                                                </span>
                                                <ul class="dropdown-menu">
                                                  @foreach ($dates as $date)
                                                  <li>{{ date('l, d M Y',strtotime($date)) }}</li>
                                                  @endforeach
                                              </ul>
                                          </span>
                                          
                                          @endif    
                                      </td>
                                  </tr>
                                  @if(!empty($seekerDetails['skills']))
                                  @foreach($seekerDetails['skills'] as $skills)
                                  <tr>
                                    @if($skills['skill_name'] == 'Other' || $skills['skill_name'] == 'other')
                                    <td>{{$skills['skill_name']}}</td>
                                    <td>{{$skills['other_skill']}}</td>
                                    @else
                                    <td>{{$skills['skill_title']}}</td>
                                    <td>{{$skills['skill_name']}}</td>
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <a href="<?php echo e(url('cms/jobseeker/index')); ?>"  class="btn btn-primary">
                                <i class="fa fa-backward"></i> Return to list
                            </a>
                        </div>
                    </div>
                    
                    
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
