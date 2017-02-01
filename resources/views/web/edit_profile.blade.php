@extends('web.layouts.dashboard')

@section('content')
<div class="container globalpadder">
    <!-- Tab-->
    <form data-parsley-validate="" novalidate=""  class="formdataPart">	
        <div class="row">
            @include('web.layouts.sidebar')
            <div class="col-sm-8 ">
                <div class="addReplica">
                    @if(isset($user->office_name) && !empty($user->office_name && !empty($user->office_desc)))
                    <div class="resp-tabs-container commonBox profilePadding cboxbottom ">
                        <div class="descriptionBox">
                            <div class="dropdown icon-upload-ctn1">
                                <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                                <ul class="actions text-left dropdown-menu">
                                    <li ><span class="gbllist iconFirstEdit"><i class="icon icon-edit"></i> Edit</span></li>
                                </ul>
                            </div>	
                            <div class="viewProfileRightCard">
                                <div class="detailTitleBlock">
                                    <input type="hidden" value="{{ @$user->office_name }}" id="hiddenofficename">
                                    <h5>{{@$user->office_name}}</h5>
                                </div>
                                <h6>Dental Office Description</h6>	
                                <input type="hidden" value="{{ @$user->office_desc }}" id="hiddenofficedesc">
                                <p>{{@$user->office_desc}}</p>	
                            </div>
                        </div>
                    </div>
                    @endif

                    @foreach($offices as $office)
                    <div class="resp-tabs-container commonBox replicaBox profilePadding cboxbottom masterBox">
                        <div class="descriptionBox">
                            <div class="dropdown icon-upload-ctn1">
                                <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                                <ul class="actions text-left dropdown-menu">
                                    <li ><span class="gbllist iconEdit"><i class="icon icon-edit "></i> Edit</span></li>
                                    <li><span class="gbllist iconDel"><i class="icon icon-deleteicon"></i> Delete</span></li>
                                </ul>
                            </div>
                            <div class="descriptionBoxInner">
                                <div class="viewProfileRightCard pd-b-25">
                                    <div class="detailTitleBlock">
                                        <h5>OFFICE DETAILS</h5>
                                    </div>
                                    <h6>Dental Office Type</h6>	
                                    <p>Dental Office Type</p>	
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Dental Office Address</h6>	
                                    <p>{{$office->address}}</p>	
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Phone Number</h6>	
                                    <p>{{$office->phone_no}}</p>	
                                </div>	
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Working Hours</h6>
                                    @if($office->work_everyday_start!='00:00:00')
                                    <p>Eveyday : 9am to 6pm</p>	
                                    @endif
                                    @if($office->monday_start!='00:00:00')
                                    <p>Monday : 9am to 6pm</p>	
                                    @endif
                                    @if($office->tuesday_start!='00:00:00')
                                    <p>Tuesday : 9am to 6pm</p>	
                                    @endif
                                    @if($office->wednesday_start!='00:00:00')
                                    <p>Wednesday : 9am to 6pm</p>	
                                    @endif
                                    @if($office->thursday_start!='00:00:00')
                                    <p>Thursday : {{strtotime($office->thursday_start)}} to {{$office->thursday_start}}</p>	
                                    @endif
                                    @if($office->friday_start!='00:00:00')
                                    <p>Friday : 9am to 6pm</p>	
                                    @endif
                                    @if($office->saturday_start!='00:00:00')
                                    <p>Saturday : 9am to 6pm</p>	
                                    @endif
                                    @if($office->sunday_start!='00:00:00')
                                    <p>Sunday : 9am to 6pm</p>	
                                    @endif
                                </div>					
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Office Location Information</h6>	
                                    <p>{{$office->location}}</p>	
                                </div>					
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <br>
                @if(count($offices)>2)
                @else
                <div class="pull-right text-right">
                    <div class="addProfileBtn "><span class="icon icon-plus"></span>Add total of 1 locations</div>
                </div>
                @endif
            </div>
        </div>
    </form>	
</div>    
@endsection
