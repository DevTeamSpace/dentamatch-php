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
                                    <input type="hidden" value="{{ $office->officetype_id }}" id="hiddenofficeTypeId{{$office->id}}">
                                    <input type="hidden" value="{{ json_encode($officeType,true) }}" id="hiddenofficeTypesJson">
                                    <input type="hidden" value="{{$office->id}}" id="hiddenEditId">
                                    <div class="detailTitleBlock">
                                        <h5>OFFICE DETAILS</h5>
                                    </div>
                                    <h6>Dental Office Type</h6>	
                                    <p>{{$office->officetype_names}}</p>	
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Dental Office Address</h6>	
                                    <input type="hidden" value="{{ $office->address }}" id="hiddenofficeaddress{{$office->id}}">
                                    <input type="hidden" value="{{ $office->zipcode }}" id="hiddenzipcode{{$office->id}}">
                                    <input type="hidden" value="{{ $office->latitude }}" id="hiddenlat{{$office->id}}">
                                    <input type="hidden" value="{{ $office->longitude }}" id="hiddenlng{{$office->id}}">
                                    <p>{{$office->address}}</p>	
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Phone Number</h6>	
                                    <input type="hidden" value="{{ preg_replace('/^(\d{3})(\d{3})(\d{4})$/i', '($1) $2-$3', $office->phone_no) }}" id="hiddenphone{{$office->id}}">
                                    <p>{{ preg_replace('/^(\d{3})(\d{3})(\d{4})$/i', '($1) $2-$3', $office->phone_no) }}</p>	
                                </div>	
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Working Hours</h6>
                                    @if($office->work_everyday_start!=null)
                                    <input type="hidden" value="1" id="hiddeneveryday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->work_everyday_start))}}" id="hiddeneverystart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->work_everyday_end))}}" id="hiddeneveryend{{$office->id}}">
                                    <p>Eveyday : {{date('h:i A', strtotime($office->work_everyday_start))}} to {{date('h:i A', strtotime($office->work_everyday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddeneverystart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddeneveryend{{$office->id}}">
                                    @endif
                                    @if($office->monday_start!=NULL)
                                    <input type="hidden" value="1" id="hiddenmonday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->monday_start))}}" id="hiddenmonstart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->monday_end))}}" id="hiddenmonend{{$office->id}}">
                                    <p>Monday : {{date('h:i A', strtotime($office->monday_start))}} to {{date('h:i A', strtotime($office->monday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddenmonstart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddenmonend{{$office->id}}">
                                    @endif
                                    @if($office->tuesday_start!=NULL)
                                    <input type="hidden" value="1" id="hiddentuesday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->tuesday_start))}}" id="hiddentuestart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->tuesday_end))}}" id="hiddentueend{{$office->id}}">
                                    <p>Tuesday : {{date('h:i A', strtotime($office->tuesday_start))}} to {{date('h:i A', strtotime($office->tuesday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddentuestart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddentueend{{$office->id}}">
                                    @endif
                                    @if($office->wednesday_start!=NULL)
                                    <input type="hidden" value="1" id="hiddenwednesday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->wednesday_start))}}" id="hiddenwedstart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->wednesday_end))}}" id="hiddenwedend{{$office->id}}">
                                    <p>Wednesday : {{date('h:i A', strtotime($office->wednesday_start))}} to {{date('h:i A', strtotime($office->wednesday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddenwedstart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddenwedend{{$office->id}}">
                                    @endif
                                    @if($office->thursday_start!=NULL)
                                    <input type="hidden" value="1" id="hiddenthursday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->thursday_start))}}" id="hiddenthustart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->thursday_end))}}" id="hiddenthuend{{$office->id}}">
                                    <p>Thursday : {{date('h:i A', strtotime($office->thursday_start))}} to {{date('h:i A', strtotime($office->thursday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddenthustart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddenthuend{{$office->id}}">
                                    @endif
                                    @if($office->friday_start!=NULL)
                                    <input type="hidden" value="1" id="hiddenfriday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->friday_start))}}" id="hiddenfristart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->friday_end))}}" id="hiddenfriend{{$office->id}}">
                                    <p>Friday : {{date('h:i A', strtotime($office->friday_start))}} to {{date('h:i A', strtotime($office->friday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddenfristart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddenfriend{{$office->id}}">
                                    @endif
                                    @if($office->saturday_start!=NULL)
                                    <input type="hidden" value="1" id="hiddensaturday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->saturday_start))}}" id="hiddensatstart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->saturday_end))}}" id="hiddensatend{{$office->id}}">
                                    <p>Saturday : {{date('h:i A', strtotime($office->saturday_start))}} to {{date('h:i A', strtotime($office->saturday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddensatstart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddensatend{{$office->id}}">
                                    @endif
                                    @if($office->sunday_start!=NULL)
                                    <input type="hidden" value="1" id="hiddensunday{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->sunday_start))}}" id="hiddensunstart{{$office->id}}">
                                    <input type="hidden" value="{{date('h:i A', strtotime($office->sunday_end))}}" id="hiddensunend{{$office->id}}">
                                    <p>Sunday : {{date('h:i A', strtotime($office->sunday_start))}} to {{date('h:i A', strtotime($office->sunday_end))}}</p>	
                                    @else
                                    <input type="hidden" value="" id="hiddensunstart{{$office->id}}">
                                    <input type="hidden" value="" id="hiddensunend{{$office->id}}">
                                    @endif
                                </div>					
                                <div class="viewProfileRightCard pd-b-25">
                                    <input type="hidden" value="{{ $office->office_location }}" id="hiddenlocation{{$office->id}}">
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
                    <div class="addProfileBtn "><span class="icon icon-plus"></span></div>
                </div>
                @endif
            </div>
        </div>
    </form>	
</div>    
@endsection
